<?php
/**
 * @package     JoomlaTracks
 * @subpackage  Modules.site
 * @copyright   Copyright (C) 2008-2015 Julien Vonthron. All rights reserved.
 * @license     GNU General Public License version 2 or later
 */

use Tracks\Helper\Helper;

defined('_JEXEC') or die('Restricted access');

/**
 * Class modTracksResults
 *
 * @package     JoomlaTracks
 * @subpackage  Modules.site
 * @since       1.0
 */
class ModTracksResults
{
	/**
	 * instance of the ranking model
	 *
	 * @var object
	 */
	var $_model = null;

	/**
	 * Round to display
	 *
	 * @var TrackslibEntityProjectround
	 */
	var $_round = null;

	/**
	 * Get model
	 *
	 * @return object|TracksModelRoundResult
	 */
	protected function _getModel()
	{
		if ($this->_model == null)
		{
			require_once JPATH_SITE . '/components/com_tracks/models/roundresult.php';
			$this->_model = new TracksModelRoundResult;
		}

		return $this->_model;
	}

	/**
	 * Get list
	 *
	 * @param   JRegistry  $params  params
	 *
	 * @return null
	 */
	public function getList($params)
	{
		$model = $this->_getModel();
		$round = $this->getRound($params);
		$result = $model->getSubrounds($round->id, $params->get('subroundtype_id'));

		if ($result)
		{
			return $result[0];
		}
		else
		{
			return null;
		}
	}

	/**
	 * Get project
	 *
	 * @param   JRegistry  $params  params
	 *
	 * @return object
	 */
	public function getProject($params)
	{
		return TrackslibEntityProject::load($params->get('project_id'));
	}

	/**
	 * Get latest round
	 *
	 * @param   JRegistry  $params  params
	 *
	 * @return TrackslibEntityProjectround
	 */
	public function getRound($params)
	{
		if ($this->_round)
		{
			return $this->_round;
		}

		$db = \JFactory::getDbo();
		$query = $db->getQuery(true)
			->select('pr.*')
			->from('#__tracks_projects_rounds AS pr')
			->where('pr.project_id = ' . (int) $params->get('project_id'))
			->where('pr.published = 1')
			->order('pr.start_date ASC, pr.ordering ASC');

		$db->setQuery($query);

		/**
		 * @var TrackslibEntityProjectround[] $projectRounds
		 */
		$projectRounds = TrackslibEntityProjectround::loadArray($db->loadObjectList());

		if (!empty($projectRounds))
		{
			$this->_round = $projectRounds[0];

			// Advance round until round start date is in the future.
			foreach ($projectRounds as $r)
			{
				$date = Helper::parseDatetime($r->start_date);

				if ($date)
				{
					if ($date->getTimestamp() > time())
					{
						break;
					}
				}
				else
				{
					// Rounds need to have at least a start date for this module !
					break;
				}

				$this->_round = $r;
			}
		}

		return $this->_round;
	}
}
