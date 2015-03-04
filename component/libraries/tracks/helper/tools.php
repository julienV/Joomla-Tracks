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

abstract class TrackslibHelperTools
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

	/**
	 * returns team social links as objects (label, icon, link)
	 *
	 * @param   object  $team_object  team data
	 *
	 * @return array
	 */
	public static function getTeamSocialItems($team_object)
	{
		$links = array();

		if (isset($team_object->url))
		{
			$obj = new stdClass;
			$obj->label = JText::_('COM_TRACKS_TEAM_SOCIAL_URL');
			$obj->icon  = 'com_tracks/website_32.png';
			$obj->link  = $team_object->url;
			$links['url'] = $obj;
		}

		if (isset($team_object->facebook))
		{
			$obj = new stdClass;
			$obj->label = JText::_('COM_TRACKS_TEAM_SOCIAL_facebook');
			$obj->icon  = 'com_tracks/facebook_32.png';
			$obj->link  = 'http://www.facebook.com/' . $team_object->facebook;
			$links['facebook'] = $obj;
		}

		if (isset($team_object->twitter))
		{
			$obj = new stdClass;
			$obj->label = JText::_('COM_TRACKS_TEAM_SOCIAL_twitter');
			$obj->icon  = 'com_tracks/twitter_32.png';
			$obj->link  = 'http://plus.google.com/' . $team_object->googleplus;
			$links['twitter'] = $obj;
		}

		if (isset($team_object->googleplus))
		{
			$obj = new stdClass;
			$obj->label = JText::_('COM_TRACKS_TEAM_SOCIAL_googleplus');
			$obj->icon  = 'com_tracks/googleplus_32.png';
			$obj->link  = 'http://plus.google.com/' . $team_object->googleplus;
			$links['googleplus'] = $obj;
		}

		if (isset($team_object->youtube))
		{
			$obj = new stdClass;
			$obj->label = JText::_('COM_TRACKS_TEAM_SOCIAL_youtube');
			$obj->icon  = 'com_tracks/youtube_32.png';
			$obj->link  = 'http://www.youtube.com/user/' . $team_object->youtube;
			$links['youtube'] = $obj;
		}

		if (isset($team_object->instagram))
		{
			$obj = new stdClass;
			$obj->label = JText::_('COM_TRACKS_TEAM_SOCIAL_instagram');
			$obj->icon  = 'com_tracks/instagram_32.png';
			$obj->link  = 'http://www.instagram.com/' . $team_object->instagram;
			$links['instagram'] = $obj;
		}

		if (isset($team_object->pinterest))
		{
			$obj = new stdClass;
			$obj->label = JText::_('COM_TRACKS_TEAM_SOCIAL_pinterest');
			$obj->icon  = 'com_tracks/pinterest_32.png';
			$obj->link  = 'http://www.pinterest.com/' . $team_object->pinterest;
			$links['pinterest'] = $obj;
		}

		if (isset($team_object->vimeo))
		{
			$obj = new stdClass;
			$obj->label = JText::_('COM_TRACKS_TEAM_SOCIAL_vimeo');
			$obj->icon  = 'com_tracks/vimeo_32.png';
			$obj->link  = 'http://www.vimeo.com/' . $team_object->vimeo;
			$links['vimeo'] = $obj;
		}

		return $links;
	}
}
