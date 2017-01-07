<?php
/**
* @version    $Id: ranking.php 126 2008-06-05 21:17:18Z julienv $
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
class TracksModelRanking extends baseModel
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

	public function setProjectId($projectid)
	{
		if ($this->project_id == $projectid)
		{
			return true;
		}

		$this->project_id = intval($projectid);
		$this->project = null;
		$this->rankingtool = null;

		return true;
	}

	/**
	 * Gets the project individuals ranking
	 *
	 * @param int project_id
	 * @return array of objects
	 */
	function getRankings()
	{
		return $this->_getRankingTool()->getIndividualsRankings();
	}


	function getIndividualRanking($project_id, $individual_id)
	{
		return $this->_getRankingTool()->getIndividualRanking($individual_id);
	}
}
