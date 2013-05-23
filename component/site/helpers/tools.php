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

abstract class TracksHelperTools
{
	/**
	 * return earned points from result object
	 * @param object result (requires bonus_points, points_attribution, rank)
	 * @return float points
	 */
	function getSubroundPoints($result)
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
	 * returns number of top 3 for ranking row object
	 * @param object $rankingrow
	 * @return int
	 */
	public static function getTop3($rankingrow)
	{
		$count = 0;
		foreach ((array) $rankingrow->finishes as $pos)
		{
			if ($pos <= 3) {
				$count++;
			}
		}
		return $count;
	}

	/**
	 * returns number of top 5 for ranking row object
	 * @param object $rankingrow
	 * @return int
	 */
	public static function getTop5($rankingrow)
	{
		$count = 0;
		foreach ((array) $rankingrow->finishes as $pos)
		{
			if ($pos <= 5) {
				$count++;
			}
		}
		return $count;
	}

	/**
	 * returns number of top 10 for ranking row object
	 * @param object $rankingrow
	 * @return int
	 */
	public static function getTop10($rankingrow)
	{
		$count = 0;
		foreach ((array) $rankingrow->finishes as $pos)
		{
			if ($pos <= 10) {
				$count++;
			}
		}
		return $count;
	}

	/**
	 * returns average finish for ranking row object
	 * @param object $rankingrow
	 * @return int
	 */
	public static function getAverageFinish($rankingrow, $precision = 2)
	{
		if (!count($rankingrow->finishes)) {
			return 0;
		}
		return round(array_sum($rankingrow->finishes) / count($rankingrow->finishes), 2);
	}

	/**
	 * returns total number of starts to ranking subrounds
	 *
	 * @param   object  $rankingrow  ranking object
	 * @return int
	 */
	public static function getStarts($rankingrow)
	{
		return count($rankingrow->finishes);
	}

	/**
	 * returns true if the string date is valid
	 *
	 * @param   string  $date
	 * @return boolean
	 */
	public static function isValidDate($date)
	{
		if (!$date || strstr($date, '0000-00-00'))
		{
			return false;
		}

		return strtotime($date);
	}

	/**
	 * returns a formatted date
	 *
	 * @param   string  $date    date from db
	 * @param   string  $format  format to convert to
	 *
	 * @return string
	 */
	public static function formatDate($date, $format = null)
	{
		if (!$format)
		{
			$format = JComponentHelper::getParams('com_tracks')->get('date_format', 'Y-m-d');
		}

		return date($format, strtotime($date));
	}
}
