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
	
	public function testStats($individual_id) 
	{
		$res = array(
		  self::getStarts($individual_id),
		  self::getWins($individual_id),
		  self::getPodiums($individual_id),
		  self::getTop5($individual_id),
		  self::getTop10($individual_id),
		  self::getAverageFinish($individual_id),
		);
		return implode(" / ", $res);
	}
	
}