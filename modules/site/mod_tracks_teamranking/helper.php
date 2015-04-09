<?php
/**
 * @package     JoomlaTracks
 * @subpackage  Modules.site
 * @copyright   Copyright (C) 2008-2015 Julien Vonthron. All rights reserved.
 * @license     GNU General Public License version 2 or later
 */

defined('_JEXEC') or die('Restricted access');

/**
 * Class modTracksTeamRanking
 *
 * @package     JoomlaTracks
 * @subpackage  Modules.site
 * @since       1.0
 */
class modTracksTeamRanking
{
	/**
	 * instance of the ranking model
	 *
	 * @var object
	 */
	protected $_model = null;

	protected function _getModel($params)
	{
		if ($this->_model == null)
		{
			require_once JPATH_SITE . '/components/com_tracks/models/teamranking.php';
			$this->_model = new TracksModelTeamRanking;
			$this->_model->setProjectId($params->get('project_id'));
		}

		return $this->_model;
	}

	public function getList(&$params)
	{
		$model = $this->_getModel($params);

		return $model->getTeamRankings();
	}

	public function getProject(&$params)
	{
		$model = $this->_getModel($params);

		return $model->getProject();
	}
}
