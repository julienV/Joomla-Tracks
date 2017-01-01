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
class TracksModelRanking extends TrackslibModelFrontbase
{
	/**
	 * reference to ranking class
	 * @var unknown_type
	 */
	var $rankingtool = null;

	/**
	 * Constructor
	 *
	 * @param   array  $config  An array of configuration options (name, state, dbo, table_path, ignore_request).
	 */
	public function __construct($config = array())
	{
		parent::__construct();

		$this->setProjectId(JFactory::getApplication()->input->getInt('p', 0));
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
	 * Gets the project individuals ranking
	 *
	 * @return array of objects
	 */
	public function getRankings()
	{
		return $this->_getRankingTool()->getIndividualsRankings();
	}

	/**
	 * Get Individual Ranking
	 *
	 * @param   int  $project_id     project id
	 * @param   int  $individual_id  individual id
	 *
	 * @return array
	 */
	public function getIndividualRanking($project_id, $individual_id)
	{
		return $this->_getRankingTool()->getIndividualRanking($individual_id);
	}
}
