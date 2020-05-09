<?php
/**
 * @package     JoomlaTracks
 * @subpackage  Modules.site
 * @copyright   Copyright (C) 2008-2015 Julien Vonthron. All rights reserved.
 * @license     GNU General Public License version 2 or later
 */

defined('_JEXEC') or die('Restricted access');

/**
 * Class modTracksResults
 *
 * @package     JoomlaTracks
 * @subpackage  Modules.site
 * @since       1.0
 */
class ModTracksLatestResults
{
	/**
	 * instance of the result model
	 *
	 * @var object
	 */
	private $model = null;

	/**
	 * @var JRegistry
	 */
	private $params;

	/**
	 * ModTracksLatestResults constructor.
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
	 * @return object|TracksModelRoundResult
	 */
	protected function getModel()
	{
		if ($this->model == null)
		{
			$this->model = RModel::getFrontInstance('Roundresult', array('ignore_request' => true), 'com_tracks');
		}

		return $this->model;
	}

	/**
	 * Get list
	 *
	 * @return null
	 */
	public function getList()
	{
		if (!$event = $this->getEvent())
		{
			return false;
		}

		return $this->getModel()->_getSubroundResults($event->id);
	}

	/**
	 * Get project
	 *
	 * @return TrackslibEntityProject
	 */
	public function getProject()
	{
		$round = $this->getProjectround();

		return $round ? $round->getProject() : false;
	}

	/**
	 * Get project round
	 *
	 * @return TrackslibEntityProjectround
	 */
	public function getProjectround()
	{
		$event = $this->getEvent();

		return $event ? $event->getProjectRound() : false;
	}

	/**
	 * Get event with latest results
	 *
	 * @return TrackslibEntityEvent
	 */
	public function getEvent()
	{
		if (empty($this->event))
		{
			$db    = JFactory::getDbo();
			$query = $db->getQuery(true)
				->select('e.*')
				->from('#__tracks_events_results AS res')
				->innerJoin('#__tracks_events AS e ON e.id = res.event_id')
				->order('modified_date DESC');

			if ($typeId = $this->params->get('subroundtype_id'))
			{
				$query->where('e.type = ' . (int) $typeId);
			}

			$db->setQuery($query);

			if (!$res = $db->loadObject())
			{
				$this->event = false;
			}
			else
			{
				$entity = TrackslibEntityEvent::getInstance($res->id);
				$this->event = $entity->bind($res);
			}
		}

		return $this->event;
	}
}
