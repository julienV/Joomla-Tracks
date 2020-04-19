<?php
/**
 * @package    Tracks.Site
 * @copyright  Tracks (C) 2008-2015 Julien Vonthron. All rights reserved.
 * @license    GNU General Public License version 2 or later
 */

// No direct access
defined('_JEXEC') or die('Restricted access');

/**
 * Joomla Tracks Component Front page Model
 *
 * @package  Tracks
 * @since    0.1
 */
class TracksModelTeamRanking extends TrackslibModelFrontbase
{
	/**
	 * TracksModelTeamRanking constructor.
	 *
	 * @param   int  $projectid  project id
	 */
	public function __construct($projectid = null)
	{
		parent::__construct();

		$projectid = $projectid ? $projectid : JRequest::getInt('p');

		if ($projectid)
		{
			$this->setProjectId($projectid);
		}
	}

	/**
	 * Set project id
	 *
	 * @param   int  $projectid  project id
	 *
	 * @return bool
	 */
	public function setProjectId($projectid)
	{
		if ($this->project_id == $projectid)
		{
			return true;
		}

		$this->project_id  = intval($projectid);
		$this->project     = null;
		$this->rankingtool = null;

		return true;
	}

	/**
	 * Gets the project teams ranking
	 *
	 * @param   int  $project_id  project id
	 *
	 * @return string The projects to be displayed to the user
	 */
	public function getTeamRankings($project_id = 0)
	{
		return $this->_getRankingTool()->getTeamsRankings();
	}

	/**
	 * Get team ranking
	 *
	 * @param   int  $teamid  team id
	 *
	 * @return mixed
	 */
	public function getTeamRanking($teamid)
	{
		return $this->_getRankingTool()->getTeamRanking($teamid);
	}
}
