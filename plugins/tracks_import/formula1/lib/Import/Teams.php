<?php
/**
 * @package     Tracks.Plugin
 * @subpackage  imports
 *
 * @copyright   Copyright (C) 2020 Julien Vonthron. All rights reserved.
 * @license     GNU General Public License version 3 or later, see LICENSE.
 */

namespace TracksF1\Import;

use Joomla\CMS\Table\Table;
use TracksF1\Client\Client;
use TracksF1\Output\TraitHasOutput;

defined('_JEXEC') or die('Restricted access');

/**
 * Class Teams
 * @package TracksF1\Import
 */
class Teams
{
	use TraitHasOutput, TraitHasCache;

	/**
	 * @var integer
	 */
	private $projectId;

	/**
	 * @var integer
	 */
	private $season;

	/**
	 * @var Client
	 */
	private $client;

	/**
	 * Drivers constructor.
	 *
	 * @param   int  $projectId
	 * @param   int  $season
	 */
	public function __construct($projectId, $season, $client = null)
	{
		$this->projectId = $projectId;
		$this->season    = $season;
		$this->client    = $client ?: Client::getInstance();
	}

	public function import()
	{
		$this->writeSection('Import teams');

		$teams = $this->client->getTeams($this->season);

		foreach ($teams as $team)
		{
			$this->writeText('importing ' . $team->name);
			$table = Table::getInstance('Team', 'TracksTable');
			$table->load(
				[
					'name' => $team->name,
				]
			);

			$table->bind(get_object_vars($team));

			if (!$table->check() || !$table->store())
			{
				throw new \RuntimeException($table->getError());
			}

			$this->cache['teams'][$team->apiId] = $table->id;

			$this->importDrivers($team->apiId, $table->id);
		}
	}

	private function importDrivers($teamCode, $teamId)
	{
		$drivers = $this->client->getDrivers($this->season, $teamCode);

		foreach ($drivers as $driver)
		{
			$this->writeText('attaching driver ' . $driver->first_name . ' ' . $driver->last_name);
			$table = Table::getInstance('Individual', 'TracksTable');
			$table->load(
				[
					'last_name' => $driver->last_name,
					'first_name' => $driver->first_name
				]
			);

			$table->bind(get_object_vars($driver));

			if (!$table->check() || !$table->store())
			{
				throw new \RuntimeException($table->getError());
			}

			$this->cache['individuals'][$driver->apiId] = $table->id;

			// Add to project
			$this->writeText('add as participant');
			$tableParticipant = Table::getInstance('Participant', 'TracksTable');
			$tableParticipant->load(
				[
					'individual_id' => $table->id,
					'team_id'       => $teamId,
					'project_id'    => $this->projectId,
				]
			);

			$tableParticipant->bind(
				[
					'individual_id' => $table->id,
					'team_id'       => $teamId,
					'project_id'    => $this->projectId,
					'number'        => $driver->number,
				]
			);

			if (!$tableParticipant->check() || !$tableParticipant->store())
			{
				throw new \RuntimeException($table->getError());
			}
		}
	}
}