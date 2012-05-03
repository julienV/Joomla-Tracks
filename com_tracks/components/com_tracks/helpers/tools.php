<?php
/**
* @version    $Id$ 
* @package    JoomlaTracks
* @copyright  Copyright (C) 2008 Julien Vonthron. All rights reserved.
* @license    GNU/GPL, see LICENSE.php
* Joomla Tracks is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/

// no direct access
defined('_JEXEC') or die('Restricted access');

class TracksHelperTools
{
	/**
	 * return earned points from result object 
	 * @param object result (requires bonus_points, points_attribution, rank)
	 * @return float points
	 */
	public static function getSubroundPoints($result)
	{
		$points = $result->bonus_points;
		if (!empty($result->points_attribution) && $result->rank)
		{
			$points_attrib = explode(',', $result->points_attribution);
			JArrayHelper::toInteger($points_attrib);
			if ( isset( $points_attrib[$result->rank-1] ) ) {
				$points += $points_attrib[$result->rank-1];
			}
		}
		return $points;
	}
	
	/**
	 * get total starts for individual
	 * 
	 * @param int $individual_id
	 * @return int
	 */
	public static function getStarts($individual_id)
	{
		return count(self::getFilteredHeats($individual_id));
	}
	
	/**
	 * get total wins for individual
	 * 
	 * @param int $individual_id
	 * @return int
	 */
	public static function getWins($individual_id)
	{
		$results = self::getFilteredHeats($individual_id);
		$res = 0;
		foreach ($results as $r) {
			if ($r->rank == 1) {
				$res++;
			}
		}
		return $res;
	}
	
	/**
	 * get total podiums for individual
	 * 
	 * @param int $individual_id
	 * @return int
	 */
	public static function getPodiums($individual_id)
	{
		$results = self::getFilteredHeats($individual_id);
		
		$res = 0;
		foreach ($results as $r) {
			if ($r->rank > 0 && $r->rank <= 3) {
				$res++;
			}
		}
		return $res;
	}
	
	/**
	 * get total Top5 for individual
	 * 
	 * @param int $individual_id
	 * @return int
	 */
	public static function getTop5($individual_id)
	{
		$results = self::getFilteredHeats($individual_id);
		
		$res = 0;
		foreach ($results as $r) {
			if ($r->rank > 0 && $r->rank <= 5) {
				$res++;
			}
		}
		return $res;
	}
	
	/**
	 * get total Top10 for individual
	 * 
	 * @param int $individual_id
	 * @return int
	 */
	public static function getTop10($individual_id)
	{
		$results = self::getFilteredHeats($individual_id);
		
		$res = 0;
		foreach ($results as $r) {
			if ($r->rank > 0 && $r->rank <= 10) {
				$res++;
			}
		}
		return $res;
	}
	
	/**
	 * get average finish for individual
	 * 
	 * @param int $individual_id
	 * @return int
	 */
	public static function getAverageFinish($individual_id)
	{
		$results = self::getFilteredHeats($individual_id);
		
		$num = 0;
		$starts = 0;
		foreach ($results as $r) 
		{
			if ($r->rank > 0) {
				$starts++;
				$num += $r->rank;
			}
		}
		if (!$starts) {
			return 0;
		}
		return $num/$starts;
	}
	
	/**
	 * return results for individual id, filtering final heats if other subrounds
	 * 
	 * @param unknown_type $individual_id
	 * @return array
	 */
	private static function getFilteredHeats($individual_id)
	{
		static $indres;
				
		if ($indres && isset($indres[$individual_id])) {
			return $indres[$individual_id];
		}
		
		$db = &JFactory::getDbo();
		
		$query = ' SELECT rr.rank, srt.name, sr.projectround_id '
				       . ' FROM #__tracks_rounds_results AS rr ' 
				       . ' INNER JOIN #__tracks_projects_subrounds AS sr ON sr.id = rr.subround_id '
				       . ' INNER JOIN #__tracks_subroundtypes AS srt ON srt.id = sr.type'
				       . ' WHERE rr.individual_id = ' . intval($individual_id);
		$db->setQuery($query);
		$res = $db->loadObjectList();
		
		if (!count($res)) {
			@$indres[$individual_id] = array();
			return array();
		}
		
		// we need to filter the 'finals' subround when there are other subrounds
		$rounds = array();
		foreach ($res as $r)
		{
			@$rounds[$r->projectround_id][] = $r;
		}
		$filtered = array();
		foreach ($rounds as $round)
		{
			if (count($round) > 1)
			{
				foreach ($round as $sr)
				{
					if (strstr($sr->name, 'final') === false) {
						$filtered[] = $sr;
					}
				}
			}
			else {
				$filtered[] = $round[0];
			}
		}
		@$indres[$individual_id] = $filtered;
		return $filtered;
	}
	
	/**
	 * returns compared stats for 2 individuals
	 * 
	 * @param int $id1
	 * @param int $id2
	 * @return array
	 */
	public static function racervs($id1, $id2)
	{
		$stats = array();
		$stats[$id1] = self::allStats($id1);
		$stats[$id1]->beatsother = 0;
		$stats[$id2] = self::allStats($id2);
		$stats[$id2]->beatsother = 0;
		
		// get common competitions
		
		$db = &JFactory::getDbo();
		$query = ' SELECT rr1.rank AS rank1, rr2.rank AS rank2, srt.name, sr.projectround_id '
		       . ' FROM #__tracks_rounds_results AS rr1 ' 
		       . ' INNER JOIN #__tracks_rounds_results AS rr2 ON rr1.subround_id = rr2.subround_id'
		       . ' INNER JOIN #__tracks_projects_subrounds AS sr ON sr.id = rr1.subround_id '
		       . ' INNER JOIN #__tracks_subroundtypes AS srt ON srt.id = sr.type'
		       . ' WHERE rr1.individual_id = ' . intval($id1)
		       . '   AND rr2.individual_id = ' . intval($id2)
		;
		$db->setQuery($query);
		$res = $db->loadObjectList();
		
		
		// we need to filter the 'finals' subround when there are other subrounds
		$rounds = array();
		foreach ($res as $r)
		{
			@$rounds[$r->projectround_id][] = $r;
		}
		$filtered = array();
		foreach ((array) $rounds as $round)
		{
			if (count($round) > 1)
			{
				foreach ($round as $sr)
				{
					if (strstr($sr->name, 'final') === false) {
						$filtered[] = $sr;
					}
				}
			}
			else {
				$filtered[] = $round[0];
			}
		}			
		
		foreach ((array) $filtered as $result)
		{
			if (!$result->rank1 && !$result->rank2) {
				continue;
			}
			else if ($result->rank1 == $result->rank2) {
				continue;
			}
			else if (!$result->rank1) {
				$stats[$id2]->beatsother++;
			}
			else if (!$result->rank2) {
				$stats[$id1]->beatsother++;
			}
			else if ($result->rank1 < $result->rank2) {
				$stats[$id1]->beatsother++;
			}
			else {
				$stats[$id2]->beatsother++;
			}
		}
		return $stats;
	}
	
	/**
	 * returns an object with group of stats
	 * 
	 * @param int $individual_id
	 * @return object
	 */
	public static function allStats($individual_id) 
	{
		$stats = new stdclass();
		$stats->starts  = self::getStarts($individual_id);
		$stats->wins    = self::getWins($individual_id);
		$stats->podiums = self::getPodiums($individual_id);
		$stats->top5    = self::getTop5($individual_id);
		$stats->top10   = self::getTop10($individual_id);
		$stats->average = self::getAverageFinish($individual_id);
		
		$db = &JFactory::getDbo();
			$query =  ' SELECT i.* '
			. ' FROM #__tracks_individuals as i '
			. ' WHERE i.id = ' . $individual_id;
		$db->setQuery($query);
		$res = $db->loadObjectList();

    $stats->name=$res[0]->last_name;
    $stats->picture=$res[0]->picture;
    
		return $stats;
	}
	
	/**
	 * Returns true if user has an individual, and competed against specified individual
	 * 
	 * @param int $id individual id
	 * @param object $user current user if null
	 * @return boolean
	 */
	public static function competedAgainst($id, $user = null)
	{		
		if (!$user) {
			$user = &Jfactory::getUser();
		}
		if (!$user->get('id')) {
			return false;
		}
		$db = &JFactory::getDbo();
		$query = ' SELECT rr1.id '
				       . ' FROM #__tracks_rounds_results AS rr1 ' 
				       . ' INNER JOIN #__tracks_rounds_results AS rr2 ON rr1.subround_id = rr2.subround_id'
				       . ' INNER JOIN #__tracks_individuals AS i ON i.id = rr2.individual_id'
				       . ' INNER JOIN #__tracks_projects_subrounds AS sr ON sr.id = rr1.subround_id '
				       . ' INNER JOIN #__tracks_subroundtypes AS srt ON srt.id = sr.type'
				       . ' WHERE rr1.individual_id = ' . intval($id)
		           . '   AND i.user_id = ' . intval($user->get('id'))
		;
		$db->setQuery($query, 0, 1);
		$res = $db->loadObject();
		return $res ? true : false;
	}
	
	public static function getProjectRoundsLeader($projectid)
	{
		$db = &JFactory::getDbo();
		$query = $db->getQuery(true);
		
		$query->select('pr.id, r.name');
		$query->from('#__tracks_projects_rounds AS pr');
		$query->innerjoin('#__tracks_rounds AS r On r.id = pr.round_id');
		$query->where('pr.project_id = '.$projectid);
		$query->order('pr.ordering ASC');
		$db->setQuery($query);
		$res = $db->loadObjectList();

		require_once (JPATH_SITE.DS.'components'.DS.'com_tracks'.DS.'sports'.DS.'default'.DS.'rankingtool.php');
		$rankingtool =  new TracksRankingTool($projectid);
		
		foreach ($res as $k => $round) {
			$ranking = $rankingtool->getIndividualsRankings(null, null, $round->id);
			$res[$k]->leader = reset($ranking);
			echo '<p>'.$round->name.': '.$res[$k]->leader->last_name.'<p>';
		}
		exit;
	}
	
  // AP
	public static function getRiderVsRider()
	{

	$today = getdate();

  $currentweek = floor(($today['yday']-57)/7); //cos we started on Mar 1;
  $currentday = $today['yday']-57; //cos we started on Mar 1;

  $rider1=array(103,5318,16,111,175,4492,1835,535,4190,3320);
  $rider2=array(
         1,1,1,3320,2441,70,5318
        ,157,4329,733,3182,2454,1141,338
        ,556,289,1,4,5323,526,44
        ,50,5537,5541,227,119,1141,128,
        157,1125,5175,5568,4024,338,214,
        1896,5350,4034,3410,5541,3182,2433,
        50,3387,119,5396,2859,54,4938,
        520,16,338,567,570,289,1,
        50,227,5396,1835,46,3410,128,
        157,1125,5175,5568,4024,4024,5515
        );

    $database = &JFactory::getDbo();
  	$sql = "select id , last_name , picture_small from #__tracks_individuals WHERE id = '$rider1[$currentweek]' limit 1";
   		$database->setQuery( $sql );
  	$rider1 = $database->loadObjectList();

    $database = &JFactory::getDbo();
  	$sql = "select id , last_name , picture_small from #__tracks_individuals WHERE id = '$rider2[$currentday]' limit 1";
   		$database->setQuery( $sql );
  	$rider2 = $database->loadObjectList();

		$result = array();
		$result[1]=$rider1[0];
		$result[2]=$rider2[0];
		return $result;
	}
	/**
	 * Add a 'latest updates' item to the table
	 * 
	 * @param string $text
	 * @param boolean $logindividual log individual id 
	 * @return boolean true on sucess
	 */
	public static function addUpdate($text, $logindividual = 1 , $urltogo = '' , $icon = '' )
	{
		$db = JFactory::getDbo();
		
		if (!trim($text)) {
			$this->setError(JTExt::_('COM_TRACKS_UPDATE_ERROR_NO_TEXT'));
			return false;
		}
		
		$individual_id = $logindividual ? self::getIndividualId() : 0;
				
		// first make sure it's not identical to the previous one
		$query	= $db->getQuery(true);
		$query->select('text , individual_id');
		$query->from($db->nameQuote('#__tracks_latest_update'));
//		$query->where('individual_id = '.intval($individual_id));
		$query->order('id DESC');
		$db->setQuery($query, 0, 1);
		if ($res = $db->loadObjectList())
		{
			if ($res[0]->text == $text && $res[0]->individual_id == $individual_id) {
				return true;
			}
		}
		
		// not the same
		$query = sprintf('INSERT INTO #__tracks_latest_update '
		               . '   SET individual_id = %d, text = %s, time = NOW() , icon = "' . $icon . '" ,  url = "' . $urltogo . '"' , $individual_id, $db->quote($text));
		
		$db->setQuery($query);
		if (!$res = $db->query())
		{
			throw new Exception($db->getErrorMsg());
		}

	// house keeping
		$query = 'DELETE FROM #__tracks_latest_update WHERE DATEDIFF(NOW(), time) > 3';
		$db->setQuery($query);
		if (!$res = $db->query())
		{
			throw new Exception($db->getErrorMsg());
		}
		
		return true;	
	}
	
	/**
	 * returns true if the user is a rider
	 * @param int $user_id
	 * @return boolean
	 */
	public static function isRider($user_id = null)
	{
		$user = JFactory::getUser($user_id);
		
		if (!$user->get('id')) {
			return false;
		}
		
		$db = &JFactory::getDbo();
		$query = $db->getQuery(true);
		
		$query->select('id');
		$query->from('#__tracks_individuals');
		$query->where('user_id = '.$user->get('id'));
		$db->setQuery($query);
		$res = $db->loadResult();
		return $res ? $res : 0;
	}
	
	/**
	 * returns individual id if the user is a rider
	 * @param int $user_id
	 * @return int individual 
	 */
	public static function getIndividualId($user_id = null)
	{
		$res = self::isRider();
		return $res ? $res : 0;
	}
	
	/**
	 * checks if individual competed in any freestyle project
	 * @param int $individual_id
	 * @return boolean
	 */
	public static function isFreestyler($individual_id)
	{
		$db = &JFactory::getDbo();
		$query = $db->getQuery(true);
		
		$query->select('i.id');
		$query->from('#__tracks_individuals AS i');
		$query->join('INNER', '#__tracks_rounds_results AS rr ON rr.individual_id = i.id ');
		$query->join('INNER', '#__tracks_projects_subrounds AS sr ON sr.id = rr.subround_id ');
		$query->join('INNER', '#__tracks_projects_rounds AS pr ON pr.id = sr.projectround_id ');
		$query->join('INNER', '#__tracks_projects AS p ON p.id = pr.project_id ');
		$query->where('LOWER(p.name) LIKE ("%freestyle%")');
		$query->where('i.id = '.$individual_id);
		$db->setQuery($query, 0, 1);
		$res = $db->loadResult();
		return $res ? true : false;
	}
	
	/**
	 * checks if individual competed in any Freeride project
	 * @param int $individual_id
	 * @return boolean
	 */
	public static function isFreerider($individual_id)
	{
		$db = &JFactory::getDbo();
		$query = $db->getQuery(true);
		
		$query->select('i.id');
		$query->from('#__tracks_individuals AS i');
		$query->join('INNER', '#__tracks_rounds_results AS rr ON rr.individual_id = i.id ');
		$query->join('INNER', '#__tracks_projects_subrounds AS sr ON sr.id = rr.subround_id ');
		$query->join('INNER', '#__tracks_projects_rounds AS pr ON pr.id = sr.projectround_id ');
		$query->join('INNER', '#__tracks_projects AS p ON p.id = pr.project_id ');
		$query->where('LOWER(p.name) LIKE ("%freeride%")');
		$query->where('i.id = '.$individual_id);
		$db->setQuery($query, 0, 1);
		$res = $db->loadResult();
		return $res ? true : false;
	}

	/**
	 * checks if individual competed in any Freeride project
	 * @param int $individual_id
	 * @return boolean
	 */
	public static function isRacer($individual_id)
	{
		$db = &JFactory::getDbo();
		$query = $db->getQuery(true);
		
		$query->select('i.id');
		$query->from('#__tracks_individuals AS i');
		$query->join('INNER', '#__tracks_rounds_results AS rr ON rr.individual_id = i.id ');
		$query->join('INNER', '#__tracks_projects_subrounds AS sr ON sr.id = rr.subround_id ');
		$query->join('INNER', '#__tracks_projects_rounds AS pr ON pr.id = sr.projectround_id ');
		$query->join('INNER', '#__tracks_projects AS p ON p.id = pr.project_id ');
		$query->where('LOWER(p.name) NOT LIKE ("%freeride%") AND LOWER(p.name) NOT LIKE ("%freestyle%")');
		$query->where('i.id = '.$individual_id);
		$db->setQuery($query, 0, 1);
		$res = $db->loadResult();
		return $res ? true : false;
	}

  public static function addVanityURL ($name,$id)
  {
        $today =  date("Y-m-d");
        $database = &JFactory::getDbo();
				$sql = "INSERT INTO #__redirect_links (old_url, new_url , published , created_date) VALUES('http://xjetski.com/$name', 'http://xjetski.com/index.php?option=com_tracks&view=individual&i=$id' , '1' , '$today' )" ;
				$database->setQuery($sql);
				$database->query();
				$sql = "INSERT INTO #__redirect_links (old_url, new_url , published , created_date) VALUES('http://xjetski.com/en/$name', 'http://xjetski.com/index.php?option=com_tracks&view=individual&i=$id' , '1' , '$today')" ;
				$database->setQuery($sql);
				$database->query();
  }

}