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
class TracksModelProjectresults extends baseModel
{
	/**
	 * associated project
	 */
	var $project = null;

	/**
	 * reference to ranking class
	 * @var unknown_type
	 */
	var $_rankingtool = null;

	/**
	 * project id
	 */
	var $_project_id = null;

	var $_data = null;

	public function __construct($projectid = null)
	{
		parent::__construct();

		$projectid = $projectid ? $projectid : JRequest::getInt('p');

		if ($projectid) {
			$this->setProjectId($projectid);
		}
	}

	public function setProjectId($projectid)
	{
		if ($this->_project_id == $projectid) {
			return true;
		}
		$this->_project_id = intval($projectid);
		$this->project = null;
		$this->_rankingtool = null;
		return true;
	}

	public function getData()
	{
		if (!$this->_data)
		{
			$this->_data = $this->_getRankings();
			$this->_getResults();
		}
		return $this->_data;
	}

	/**
	 * return project rounds and subrounds
	 *
	 * @return array
	 */
	public function getRounds()
	{
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);

		$query->select('psr.id AS event_id, srt.name AS subround_name');
		$query->select('r.id AS round_id, r.name AS round_name');
		$query->select('CASE WHEN CHAR_LENGTH(r.short_name) THEN r.short_name ELSE r.name END AS short_name');
		$query->from('#__tracks_events AS psr');
		$query->join('INNER', '#__tracks_eventtypes AS srt ON srt.id = psr.type');
		$query->join('INNER', '#__tracks_projects_rounds AS pr ON pr.id = psr.projectround_id');
		$query->join('INNER', '#__tracks_rounds AS r ON r.id = pr.round_id');
		$query->where('pr.project_id = '.$this->_project_id);
		$query->where('srt.count_points > 0 ');
		$query->where('pr.published = 1');
		$query->where('psr.published = 1');
		$query->order('pr.ordering, psr.ordering');
		$db->setQuery($query);
		$res = $db->loadObjectList();

		if (!$res) {
			return false;
		}

		// group by rounds
		$rounds = array();
		foreach ($res as $r)
		{
			if (isset($rounds[$r->round_id])) {
				$rounds[$r->round_id]->subrounds[] = $r;
			}
			else
			{
				$obj = new stdClass();
				$obj->round_id   = $r->round_id;
				$obj->round_name = $r->round_name;
				$obj->short_name = $r->short_name;
				$obj->subrounds = array($r);
				$rounds[$r->round_id]= $obj;
			}
		}
		return $rounds;
	}


	protected function _getResults()
	{
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);

		$query->select('psr.id AS event_id, rr.rank, rr.individual_id');
		$query->from('#__tracks_events_results AS rr');
		$query->join('INNER', '#__tracks_events AS psr ON psr.id = rr.event_id');
		$query->join('INNER', '#__tracks_eventtypes AS srt ON srt.id = psr.type');
		$query->join('INNER', '#__tracks_projects_rounds AS pr ON pr.id = psr.projectround_id');
		$query->join('INNER', '#__tracks_rounds AS r ON r.id = pr.round_id');
		$query->where('pr.project_id = '.$this->_project_id);
		$query->where('pr.published = 1');
		$query->where('psr.published = 1');
		$db->setQuery($query);
		$res = $db->loadObjectList();

		if (!$res) {
			return false;
		}

		// index by individual, then event_id
		$individuals = array();
		foreach ($res as $r)
		{
			if (!isset($this->_data[$r->individual_id])) {
				continue;
			}
			@$this->_data[$r->individual_id]->results[$r->event_id] = $r->rank;
		}

		return $this->_data;
	}


	/**
	 * Gets the project individuals ranking
	 *
	 * @param int project_id
	 * @return array of objects
	 */
	protected function _getRankings()
	{
		return $this->_getRankingTool()->getIndividualsRankings();
	}


}
