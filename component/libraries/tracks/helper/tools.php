<?php
/**
 * @package     Tracks
 * @subpackage  Library
 * @copyright   Tracks (C) 2008-2015 Julien Vonthron. All rights reserved.
 * @license     GNU General Public License version 2 or later
 */

defined('_JEXEC') or die;

/**
 * Tools helper
 *
 * @package     Tracks
 * @subpackage  Library
 * @since       3.0
 */
abstract class TrackslibHelperTools
{
	/**
	 * Get current project id (backend)
	 *
	 * @return int
	 */
	public static function getCurrentProjectId()
	{
		return (int) JFactory::getApplication()->getUserState('currentproject');
	}

	/**
	 * Format round date
	 *
	 * @param   Object  $round  round
	 *
	 * @return bool|string|void
	 */
	public static function formatRoundStartEnd($round)
	{
		if (!self::isValidDate($round->start_date))
		{
			return false;
		}

		if (self::isValidDate($round->end_date))
		{
			// Both dates are defined.
			$format_end = 'j F Y';

			if (JHTML::date($round->start_date, 'Ym') === JHTML::date($round->end_date, 'Ym'))
			{
				// No need to display twice the month and year here
				$format_start = 'j';
			}
			else
			{
				$format_start = 'j F Y';
			}

			return JHTML::date($round->start_date, $format_start) . ' - ' . JHTML::date($round->end_date, $format_end);
		}
		else
		{
			return JHTML::date($round->start_date, 'j F Y');
		}

		return;
	}

	/**
	 * return true is a date is valid (not null, or 0000-00...)
	 *
	 * @param   string  $date  date
	 *
	 * @return boolean
	 */
	public static function isValidDate($date)
	{
		if (!$date)
		{
			return false;
		}

		if ($date === '0000-00-00' || $date === '0000-00-00 00:00:00')
		{
			return false;
		}

		if (!strtotime($date))
		{
			return false;
		}

		return true;
	}

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
