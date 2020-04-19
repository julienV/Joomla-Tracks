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
	 * @var object
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
	 * @param   JRegistry  &$params  params
	 *
	 * @return null
	 */
	public function getList(&$params)
	{
		$model = $this->_getModel();
		$round = $this->getRound($params);
		$result = $model->getSubrounds($round->projectround_id, $params->get('subroundtype_id'));

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
	 * @param   JRegistry  &$params  params
	 *
	 * @return object
	 */
	public function getProject(&$params)
	{
		$model = $this->_getModel();

		return $model->getProject($params->get('project_id'));
	}

	/**
	 * Get round
	 *
	 * @param   JRegistry  &$params  params
	 *
	 * @return object
	 */
	public function getRound(&$params)
	{
		if ($this->_round)
		{
			return $this->_round;
		}

		$db = JFactory::getDBO();

		$sql = ' SELECT pr.id AS projectround_id, pr.start_date, pr.end_date, pr.round_id, '
			. ' r.name, '
			. ' CASE WHEN CHAR_LENGTH( r.alias ) THEN CONCAT_WS( \':\', r.id, r.alias ) ELSE r.id END AS slug '
			. ' FROM #__tracks_projects_rounds AS pr '
			. ' INNER JOIN #__tracks_rounds AS r ON r.id = pr.round_id '
			. ' WHERE project_id = ' . $params->get('project_id')
			. '   AND pr.published = 1 '
			. ' ORDER BY start_date ASC, pr.ordering ASC ';
		$db->setQuery($sql);
		$rounds = $db->loadObjectList();

		if ($db->getErrorNum())
		{
			echo $db->getErrorMsg();
		}

		if ($rounds)
		{
			$this->_round = $rounds[0];

			// Advance round until round start date is in the future.
			foreach ($rounds as $r)
			{
				if ($r->start_date != '0000-00-00 00:00:00')
				{
					if (strtotime($r->start_date) > time())
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
