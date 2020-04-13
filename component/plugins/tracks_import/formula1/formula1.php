<?php
/**
 * @package     Tracks.Plugin
 * @subpackage  imports
 *
 * @copyright   Copyright (C) 2020 Julien Vonthron. All rights reserved.
 * @license     GNU General Public License version 3 or later, see LICENSE.
 */

use Joomla\CMS\Factory;
use Joomla\CMS\Layout\LayoutHelper;
use Joomla\CMS\Table\Table;
use TracksF1\Import\Races;
use TracksF1\Import\Drivers;
use TracksF1\Import\Teams;

defined('JPATH_BASE') or die;

// Import library dependencies
jimport('joomla.plugin.plugin');

jimport('tracks.bootstrap');

JLoader::registerNamespace('TracksF1', __DIR__ . '/lib', false, false, 'psr4');

/**
 * Imports formula1 data
 *
 * @since  __deploy_version__
 */
class PlgTracks_importFormula1 extends JPlugin
{
	private $importer = 'formula1';

	public function onTracksGetImporters(&$importers)
	{
		$importers[] = [
			'id'          => $this->importer,
			'name'        => 'Formula 1',
			'description' => 'Imports data from https://ergast.com/',
			'developer'   => 'Julien Vonthron',
		];
	}

	public function onTracksGetImporterScreen($importer, &$html)
	{
		if ($importer !== $this->importer)
		{
			return;
		}

		$html = LayoutHelper::render('formula1.screen', null, __DIR__ . '/layouts');
	}

	public function onAjaxF1ImportSeason()
	{
		if (!class_exists('\GuzzleHttp\Client'))
		{
			require_once 'vendor/autoload.php';
		}

		$app    = Factory::getApplication();
		$input  = $app->input;
		$output =  new \TracksF1\Output\Html;
		$cache  = [];

		$projectId = $input->getInt('project_id');
		$season    = $input->getInt('season');

		if (empty($projectId))
		{
			throw new Exception('projectid is required');
		}

		if (empty($season))
		{
			throw new Exception('season is required');
		}

		$raceEvent = Table::getInstance('Eventtype', 'TracksTable');
		$raceEvent->load(
			[
				'name' => 'Race'
			]
		);

		$raceEvent->bind(
			[
				'name'         => 'Race',
				'count_points' => 1
			]
		);

		if (!$raceEvent->check() || !$raceEvent->store())
		{
			throw new \RuntimeException($raceEvent->getError());
		}

		try
		{
			$teamsHelper = new Teams($projectId, $season);
			$teamsHelper->setCache($cache);
			$teamsHelper->setOutput($output);
			$teamsHelper->import();

			$racesHelper = new Races($projectId, $season);
			$racesHelper->setCache($cache);
			$racesHelper->setOutput($output);
			$racesHelper->setRaceEventId($raceEvent->id);
			$racesHelper->import();
		}
		catch (Exception $e)
		{
			$output->write($e->getMessage());

			echo $output->output();
		}

		echo $output->output();
	}
}