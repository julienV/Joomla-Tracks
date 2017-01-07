<?php
/**
* @version    $Id: teamranking.php 126 2008-06-05 21:17:18Z julienv $
* @package    JoomlaTracks
* @copyright	Copyright (C) 2008 Julien Vonthron. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* Joomla Tracks is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();

jimport('joomla.application.component.model');
require_once( 'base.php' );

/**
 * Joomla Tracks Component Front page Model
 *
 * @package		Tracks
 * @since 0.1
 */
class TracksModelTeamRanking extends baseModel
{
	function __construct($projectid = null)
	{
		parent::__construct();

		$projectid = $projectid ? $projectid : JRequest::getInt('p');

		if ($projectid) {
			$this->setProjectId($projectid);
		}
	}

	function setProjectId($projectid)
	{
		if ($this->project_id == $projectid) {
			return true;
		}
		$this->project_id = intval($projectid);
		$this->project = null;
		$this->rankingtool = null;
		return true;
	}

	/**
	 * Gets the project teams ranking
	 *
	 * @param int project_id
	 * @return string The projects to be displayed to the user
	 */
	function getTeamRankings( $project_id = 0 )
	{
		return $this->_getRankingTool()->getTeamsRankings();
	}

	function getTeamRanking($teamid)
	{
		return $this->_getRankingTool()->getTeamRanking($teamid);
	}
}
