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

  $rider1=array(103,5318,16,111,175);
  $rider2=array(
         1,1,1,3320,2441,70,5318
        ,157,4329,733,3182,2454,1141,338
        ,556,289,1,4,5323,526,44
        ,50,5537,5541,227,119,1141,128
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
	
	
}