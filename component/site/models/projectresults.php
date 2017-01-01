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
class TracksModelProjectresults extends TrackslibModelFrontbase
{
	/**
	 * associated project
	 */
	var $project = null;

	/**
	 * reference to ranking class
	 * @var unknown_type
	 */
	var $rankingtool = null;

	var $_data = null;

	/**
	 * project id
	 */
	protected $project_id = null;

	/**
	 * TracksModelProjectresults constructor.
	 *
	 * @param   int  $projectid  project id
	 */
	public function __construct($projectid = null)
	{
		parent::__construct();

		$projectid = $projectid ? $projectid : JFactory::getApplication()->input->getInt('p', 0);

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
	 * Get data
	 *
	 * @return array|null
	 */
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
	 * Gets the project individuals ranking
	 *
	 * @return array of objects
	 */
	protected function _getRankings()
	{
		return $this->_getRankingTool()->getIndividualsRankings();
	}

	/**
	 * Get results
	 *
	 * @return bool|null
	 */
	protected function _getResults()
	{
		$db    = JFactory::getDbo();
		$query = $db->getQuery(true);

		$query->select('psr.id AS event_id, rr.rank, rr.individual_id');
		$query->from('#__tracks_events_results AS rr');
		$query->join('INNER', '#__tracks_events AS psr ON psr.id = rr.event_id');
		$query->join('INNER', '#__tracks_eventtypes AS srt ON srt.id = psr.type');
		$query->join('INNER', '#__tracks_projects_rounds AS pr ON pr.id = psr.projectround_id');
		$query->join('INNER', '#__tracks_rounds AS r ON r.id = pr.round_id');
		$query->where('pr.project_id = ' . $this->project_id);
		$query->where('pr.published = 1');
		$query->where('psr.published = 1');
		$db->setQuery($query);
		$res = $db->loadObjectList();

		if (!$res)
		{
			return false;
		}

		foreach ($res as $r)
		{
			if (!isset($this->_data[$r->individual_id]))
			{
				continue;
			}

			$this->_data[$r->individual_id]->results[$r->event_id] = $r->rank;
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
		$db    = JFactory::getDbo();
		$query = $db->getQuery(true);

		$query->select('psr.id AS event_id, srt.name AS subround_name');
		$query->select('r.id AS round_id, r.name AS round_name');
		$query->select('CASE WHEN CHAR_LENGTH(r.short_name) THEN r.short_name ELSE r.name END AS short_name');
		$query->from('#__tracks_events AS psr');
		$query->join('INNER', '#__tracks_eventtypes AS srt ON srt.id = psr.type');
		$query->join('INNER', '#__tracks_projects_rounds AS pr ON pr.id = psr.projectround_id');
		$query->join('INNER', '#__tracks_rounds AS r ON r.id = pr.round_id');
		$query->where('pr.project_id = ' . $this->project_id);
		$query->where('srt.count_points > 0 ');
		$query->where('pr.published = 1');
		$query->where('psr.published = 1');
		$query->order('pr.ordering, psr.ordering');
		$db->setQuery($query);
		$res = $db->loadObjectList();

		if (!$res)
		{
			return false;
		}

		// Group by rounds
		$rounds = array();

		foreach ($res as $r)
		{
			if (isset($rounds[$r->round_id]))
			{
				$rounds[$r->round_id]->subrounds[] = $r;
			}
			else
			{
				$obj                  = new stdClass;
				$obj->round_id        = $r->round_id;
				$obj->round_name      = $r->round_name;
				$obj->short_name      = $r->short_name;
				$obj->subrounds       = array($r);
				$rounds[$r->round_id] = $obj;
			}
		}

		return $rounds;
	}
}
