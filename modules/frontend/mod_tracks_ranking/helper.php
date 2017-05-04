<?php
/**
 * @package     JoomlaTracks
 * @subpackage  Modules.site
 * @copyright   Copyright (C) 2008-2015 Julien Vonthron. All rights reserved.
 * @license     GNU General Public License version 2 or later
 */

defined('_JEXEC') or die('Restricted access');

/**
 * Class modTracksRanking
 *
 * @package     JoomlaTracks
 * @subpackage  Modules.site
 * @since       1.0
 */
class ModTracksRanking
{
	/**
	 * instance of the ranking model
	 *
	 * @var object
	 */
	var $model = null;

	/**
	 * @var JRegistry
	 */
	private $params;

	/**
	 * ModTracksRanking constructor.
	 *
	 * @param   JRegistry  $params  module parameters
	 */
	public function __construct($params)
	{
		$this->params = $params;
	}

	/**
	 * Get model
	 *
	 * @param   array  $params  params
	 *
	 * @return object|TracksModelRanking
	 */
	protected function getModel()
	{
		if ($this->model == null)
		{
			RModel::addIncludePath(JPATH_SITE . '/components/com_tracks/models');
			$this->model = RModel::getFrontInstance('Ranking', array('ignore_request' => true), 'com_tracks');

			if (!$projectId = $this->params->get('project_id'))
			{
				if ($this->params->get('uselatest'))
				{
					$event = $this->getLatestResultsEvent();
					$projectId = $event ? $event->getProjectRound()->project_id : $this->params->get('project_id');
				}
			}

			if (!$projectId)
			{
				throw new Exception('project id no set');
			}

			$this->model->setProjectId($projectId);
		}

		return $this->model;
	}

	/**
	 * Get data
	 *
	 * @param   object  &$params  params
	 *
	 * @return array
	 */
	public function getList()
	{
		$model = $this->getModel();

		return $model->getRankings();
	}

	/**
	 * Get project
	 *
	 * @param   object  &$params  params
	 *
	 * @return object
	 */
	public function getProject()
	{
		$model = $this->getModel();

		return $model->getProject();
	}

	/**
	 * Get event with latest results
	 *
	 * @return TrackslibEntityEvent
	 */
	public function getLatestResultsEvent()
	{
			$db    = JFactory::getDbo();
			$query = $db->getQuery(true)
				->select('e.*')
				->from('#__tracks_events_results AS res')
				->innerJoin('#__tracks_events AS e ON e.id = res.event_id')
				->order('modified_date DESC');

			$db->setQuery($query);

			if (!$res = $db->loadObject())
			{
				return false;
			}

			$entity = TrackslibEntityEvent::getInstance($res->id);

			return $entity->bind($res);
	}
}
