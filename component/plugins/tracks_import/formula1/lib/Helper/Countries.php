<?php
/**
 * @package     Tracks.Plugin
 * @subpackage  imports
 *
 * @copyright   Copyright (C) 2020 Julien Vonthron. All rights reserved.
 * @license     GNU General Public License version 3 or later, see LICENSE.
 */

namespace TracksF1\Helper;

abstract class Countries
{
	public static function getAll()
	{
		static $csvdata;

		if (!$csvdata)
		{
			$csvdata = [];

			if (($handle = fopen(__DIR__ . "/countries.csv", "r")) !== false)
			{
				while (($data = fgetcsv($handle, 1000, ",")) !== false)
				{
					$csvdata[] = [
						'cca2'        => $data[0],
						'name'        => $data[1],
						'cca3'        => $data[2],
						'nationality' => $data[3],
					];
				}
			}
		}

		return $csvdata;
	}

	/**
	 * Get iso2 country code from nationality
	 *
	 * @param   string  $nationality
	 *
	 * @return string|boolean
	 */
	public static function getFromNationality($nationality)
	{
		foreach (static::getAll() as $row)
		{
			if (strtolower($row['nationality']) == strtolower($nationality))
			{
				return $row['cca2'];
			}
		}

		return false;
	}

	/**
	 * Get iso2 country code from nationality
	 *
	 * @param   string  $country
	 *
	 * @return string|boolean
	 */
	public static function getCodeFromName($country)
	{
		foreach (static::getAll() as $row)
		{
			if (strtolower($row['name']) == strtolower($country))
			{
				return $row['cca2'];
			}
		}

		return false;
	}
}