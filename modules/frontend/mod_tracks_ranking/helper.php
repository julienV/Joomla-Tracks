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
	var $_model = null;

	/**
	 * Get model
	 *
	 * @param   array  $params  params
	 *
	 * @return object|TracksModelRanking
	 */
	protected function _getModel($params)
	{
		if ($this->_model == null)
		{
			require_once JPATH_SITE . '/components/com_tracks/models/ranking.php';
			$this->_model = new TracksModelRanking;
			$this->_model->setProjectId($params->get('project_id'));
		}

		return $this->_model;
	}

	/**
	 * Get data
	 *
	 * @param   object  &$params  params
	 *
	 * @return array
	 */
	public function getList(&$params)
	{
		$model = $this->_getModel($params);

		return $model->getRankings();
	}

	/**
	 * Get project
	 *
	 * @param   object  &$params  params
	 *
	 * @return object
	 */
	public function getProject(&$params)
	{
		$model = $this->_getModel($params);

		return $model->getProject();
	}
}
