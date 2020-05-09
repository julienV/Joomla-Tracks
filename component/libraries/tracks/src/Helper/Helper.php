<?php
/**
 * @package    Tracks.Library
 * @copyright  Tracks (C) 2008-2020 Julien Vonthron. All rights reserved.
 * @license    GNU General Public License version 2 or later
 */

namespace Tracks\Helper;

use Joomla\CMS\Factory;
use Joomla\CMS\Plugin\PluginHelper;

defined('_JEXEC') or die('Restricted access');

/**
 * Tracks config helper
 *
 * @package  Tracks.Library
 * @since    3.0.12
 */
abstract class Helper
{
	/**
	 * Get parsed date
	 *
	 * @param   string  $date    date to parse
	 * @param   string  $format  date format
	 *
	 * @return \Joomla\CMS\Date\Date|boolean
	 */
	public static function parseDate($date, $format = 'Y-m-d')
	{
		$f     = \DateTime::createFromFormat($format, $date);
		$valid = \DateTime::getLastErrors();

		return ($valid['warning_count'] == 0 && $valid['error_count'] == 0) ? Factory::getDate($date) : false;
	}

	/**
	 * Return formated start-end date of a round
	 *
	 * @param   \Object  $round  round data
	 *
	 * @return string
	 */
	public static function formatRoundStartEnd($round)
	{
		$startDate = self::parseDate($round->start_date, "Y-m-d H:i:s");
		$endDate   = self::parseDate($round->end_date, "Y-m-d H:i:s");

		if ($startDate)
		{
			if ($endDate && $endDate->format('Ymd') != $startDate->format('Ymd'))
			{
				// Both dates are defined.
				$formatEnd = 'j F Y';

				if ($startDate->format('Ym') == $endDate->format('Ym'))
				{
					// No need to display twice the month and year here
					$formatStart = 'j';
				}
				else
				{
					$formatStart = 'j F Y';
				}

				return $startDate->format($formatStart) . ' - ' . $endDate->format($formatEnd);
			}

			return $startDate->format('j F Y');
		}
		else
		{
			return '';
		}
	}
}
