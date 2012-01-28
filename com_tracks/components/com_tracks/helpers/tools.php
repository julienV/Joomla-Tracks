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
		$db = &JFactory::getDbo();
		
		$query = ' SELECT count(*) ' 
		       . ' FROM #__tracks_rounds_results ' 
		       . ' WHERE individual_id = ' . intval($individual_id);
		$db->setQuery($query);
		return $db->loadResult();
	}
	
	/**
	 * get total wins for individual
	 * 
	 * @param int $individual_id
	 * @return int
	 */
	public static function getWins($individual_id)
	{
		$db = &JFactory::getDbo();
		
		$query = ' SELECT count(*) ' 
		       . ' FROM #__tracks_rounds_results ' 
		       . ' WHERE individual_id = ' . intval($individual_id)
		       . '   AND rank = 1 ';
		$db->setQuery($query);
		return $db->loadResult();
	}
	
	/**
	 * get total podiums for individual
	 * 
	 * @param int $individual_id
	 * @return int
	 */
	public static function getPodiums($individual_id)
	{
		$db = &JFactory::getDbo();
		
		$query = ' SELECT count(*) ' 
		       . ' FROM #__tracks_rounds_results ' 
		       . ' WHERE individual_id = ' . intval($individual_id)
		       . '   AND rank IN (1,2,3) ';
		$db->setQuery($query);
		return $db->loadResult();
	}
	
	/**
	 * get total Top5 for individual
	 * 
	 * @param int $individual_id
	 * @return int
	 */
	public static function getTop5($individual_id)
	{
		$db = &JFactory::getDbo();
		
		$query = ' SELECT count(*) ' 
		       . ' FROM #__tracks_rounds_results ' 
		       . ' WHERE individual_id = ' . intval($individual_id)
		       . '   AND rank IN (1,2,3,4,5) ';
		$db->setQuery($query);
		return $db->loadResult();
	}
	
	/**
	 * get total Top10 for individual
	 * 
	 * @param int $individual_id
	 * @return int
	 */
	public static function getTop10($individual_id)
	{
		$db = &JFactory::getDbo();
		
		$query = ' SELECT count(*) ' 
		       . ' FROM #__tracks_rounds_results ' 
		       . ' WHERE individual_id = ' . intval($individual_id)
		       . '   AND rank IN (1,2,3,4,5,6,7,8,9,10) ';
		$db->setQuery($query);
		return $db->loadResult();
	}
	
	/**
	 * get average finish for individual
	 * 
	 * @param int $individual_id
	 * @return int
	 */
	public static function getAverageFinish($individual_id)
	{
		$db = &JFactory::getDbo();
		
		$query = ' SELECT rank ' 
		       . ' FROM #__tracks_rounds_results ' 
		       . ' WHERE individual_id = ' . intval($individual_id)
		       . '   AND rank > 0 ';
		$db->setQuery($query);
		$results = $db->loadResultArray();
		if (count($results)) {
			return array_sum($results)/count($results);
		}
		else {
			return 0;
		}
	}
	
}

?>