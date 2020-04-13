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
 * Class Races
 * @package TracksF1\Import
 */
class Races
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

	private $raceEventId;

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

	public function setRaceEventId($id)
	{
		$this->raceEventId = $id;
	}

	public function import()
	{
		$this->writeSection('importing races');
		$races = $this->client->getRaces($this->season);

		foreach ($races as $race)
		{
			$this->writeText('importing ' . $race->name);
			$table = Table::getInstance('Round', 'TracksTable');
			$table->load(
				[
					'name' => $race->name,
				]
			);

			$table->bind(get_object_vars($race));

			if (!$table->id)
			{
				$table->published = 1;
			}

			if (!$table->check() || !$table->store())
			{
				throw new \RuntimeException($table->getError());
			}

			// Add to project
			$this->writeText(' - adding to project');
			$projectround = Table::getInstance('Projectround', 'TracksTable');
			$projectround->load(
				[
					'project_id'    => $this->projectId,
					'round_id'      => $table->id
				]
			);

			$projectround->bind(
				[
					'project_id'    => $this->projectId,
					'round_id'      => $table->id,
					'ordering'      => $race->round_number,
					'start_date'    => $race->date,
					'published'     => 1,
				]
			);

			if (!$projectround->check() || !$projectround->store())
			{
				throw new \RuntimeException($table->getError());
			}

			$raceRoundEvent = Table::getInstance('Event', 'TracksTable');
			$raceRoundEvent->load(
				[
					'projectround_id'    => $projectround->id,
					'type'               => $this->raceEventId
				]
			);

			if (!$raceRoundEvent->id)
			{
				$raceRoundEvent->bind(
					[
						'projectround_id' => $projectround->id,
						'type'            => $this->raceEventId,
						'ordering'        => 1,
						'start_date'      => $race->date,
						'published'       => 1,
					]
				);

				if (!$raceRoundEvent->check() || !$raceRoundEvent->store())
				{
					throw new \RuntimeException($raceRoundEvent->getError());
				}
			}

			$this->addResults($race->round_number, $raceRoundEvent->id);
		}
	}

	private function addResults($apiRoundNumber, $raceRoundEventId)
	{
		$results = $this->client->getRaceResults($this->season, $apiRoundNumber);

		if (empty($results))
		{
			$this->writeText('no results');

			return;
		}

		foreach ($results as $result)
		{
			$table = Table::getInstance('Eventresult', 'TracksTable');
			$table->load(
				[
					'individual_id' => $this->cache['individuals'][$result->Driver->driverId],
					'team_id'       => $this->cache['teams'][$result->Constructor->constructorId],
					'event_id'      => $raceRoundEventId,
				]
			);

			$table->bind(
				[
					'individual_id' => $this->cache['individuals'][$result->Driver->driverId],
					'team_id'       => $this->cache['teams'][$result->Constructor->constructorId],
					'event_id'      => $raceRoundEventId,
					'number'        => $result->number,
					'rank'          => $result->position,
					'performance'   => $result->status == 'Finished' ? $result->Time->time : 'laps: ' . $result->laps . ' (' . $result->status . ')',
					'bonus_points'  => $result->points,
				]
			);

			if (!$table->check() || !$table->store())
			{
				throw new \RuntimeException($table->getError());
			}
		}
	}
}