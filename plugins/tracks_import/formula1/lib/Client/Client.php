<?php
/**
 * @package     Tracks.Plugin
 * @subpackage  imports
 *
 * @copyright   Copyright (C) 2020 Julien Vonthron. All rights reserved.
 * @license     GNU General Public License version 3 or later, see LICENSE.
 */

namespace TracksF1\Client;

use TracksF1\Helper\Countries;
use TracksF1\Import\Data\Race;
use TracksF1\Import\Data\Driver;
use TracksF1\Import\Data\Team;

defined('_JEXEC') or die('Restricted access');

/**
 * Class Client
 * @package TracksF1\Client
 */
class Client
{
	/**
	 * @var string
	 */
	private $baseUrl = 'https://ergast.com/api/f1/';

	/**
	 * @var \GuzzleHttp\Client
	 */
	private $client;

	/**
	 * Client constructor.
	 */
	private function __construct()
	{
		$this->client = new \GuzzleHttp\Client;
	}

	/**
	 * Get an instance
	 *
	 * @return static
	 */
	public static function getInstance()
	{
		static $instance;

		if ($instance == null)
		{
			$instance = new static;
		}

		return $instance;
	}

	/**
	 * Get drivers of the season
	 *
	 * @param   int  $season  season
	 *
	 * @return Race[]
	 *
	 * @throws \GuzzleHttp\Exception\GuzzleException
	 */
	public function getRaces($season)
	{
		$query = $this->baseUrl . $season . '.json';
		$res = $this->client->request('GET', $query);

		if (!$res->getStatusCode() == 200)
		{
			throw new \RuntimeException('Error fetching races');
		}

		$data = json_decode($res->getBody());

		return array_map(
			function ($row)
			{
				$obj               = new Race;
				$obj->name         = $row->raceName;
				$obj->date         = $row->date;
				$obj->round_number = $row->round;
				$obj->country      = Countries::getCodeFromName($row->Circuit->Location->country);

				return $obj;
			},
			$data->MRData->RaceTable->Races
		);
	}

	/**
	 * Get drivers of the season
	 *
	 * @param   integer  $season  season
	 * @param   string   $team    team api code
	 *
	 * @return Driver[]
	 * @throws \GuzzleHttp\Exception\GuzzleException
	 */
	public function getDrivers($season, $team = null)
	{
		$query = $this->baseUrl . $season;

		if ($team)
		{
			$query .= '/constructors/' . $team;
		}

		$query .= '/drivers.json';

		$res = $this->client->request('GET', $query);

		if (!$res->getStatusCode() == 200)
		{
			throw new \RuntimeException('Error fetching drivers');
		}

		$data = json_decode($res->getBody());

		return array_map(
			function ($row)
			{
				$driver               = new Driver;
				$driver->apiId        = $row->driverId;
				$driver->first_name   = $row->givenName;
				$driver->last_name    = $row->familyName;
				$driver->dob          = $row->dateOfBirth;
				$driver->country_code = Countries::getFromNationality($row->nationality);
				$driver->number       = $row->permanentNumber;

				return $driver;
			},
			$data->MRData->DriverTable->Drivers
		);
	}

	/**
	 * Get teams of the season
	 *
	 * @param   int  $season  season
	 *
	 * @return Team[]
	 *
	 * @throws \Exception
	 */
	public function getTeams($season)
	{
		$res = $this->client->request('GET', $this->baseUrl . $season . '/constructors.json');

		if (!$res->getStatusCode() == 200)
		{
			throw new \RuntimeException('Error fetching teams');
		}

		$data = json_decode($res->getBody());

		return array_map(
			function ($row)
			{
				$team               = new Team;
				$team->apiId        = $row->constructorId;
				$team->name         = $row->name;
				$team->country_code = Countries::getFromNationality($row->nationality);

				return $team;
			},
			$data->MRData->ConstructorTable->Constructors
		);
	}

	public function getRaceResults($season, $apiRoundNumber)
	{
		$res = $this->client->request('GET', $this->baseUrl . $season . '/' . $apiRoundNumber . '/results.json');

		if (!$res->getStatusCode() == 200)
		{
			throw new \RuntimeException('Error fetching teams');
		}

		$data = json_decode($res->getBody());

		return $data->MRData->RaceTable->Races[0]->Results;
	}
}