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
 * Class Drivers
 * @package TracksF1\Import
 */
class Drivers
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
		$drivers = $this->client->getDrivers($this->season);

		foreach ($drivers as $driver)
		{
			$table = Table::getInstance('Individual', 'TracksTable');
			$table->load(
				[
					'last_name' => $driver->last_name,
					'first_name' => $driver->first_name
				]
			);

			$table->bind(get_object_vars($driver));

			if (!$table->store())
			{
				throw new \RuntimeException($table->getError());
			}


		}
	}
}