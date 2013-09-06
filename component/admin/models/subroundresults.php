<?php
/**
 * @version    0.2
 * @package    JoomlaTracks
 * @copyright  Copyright (C) 2008 Julien Vonthron. All rights reserved.
 * @license    GNU/GPL, see LICENSE.php
 *             Joomla Tracks is free software. This version may have been modified pursuant
 *             to the GNU General Public License, and as distributed it includes or
 *             is derivative of works licensed under the GNU General Public License or
 *             other free or open source software licenses.
 *             See COPYRIGHT.php for copyright notices and details.
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();

/**
 * Joomla Tracks Component sub-round results Model
 *
 * @package  Tracks
 * @since    0.2
 */
class TracksModelSubroundResults extends FOFModel
{
	/**
	 * Builds the SELECT query
	 *
	 * @param   boolean $overrideLimits  Are we requested to override the set limits?
	 *
	 * @return  JDatabaseQuery
	 */
	public function buildQuery($overrideLimits = false)
	{
		$table = $this->getTable();
		$db = $this->getDbo();

		// Current
		$subround_id = $this->getState('subround_id');

		$query = $db->getQuery(true);

		$query->select('rr.individual_id, rr.team_id, rr.subround_id, rr.rank');
		$query->select('rr.performance, rr.bonus_points, rr.comment');
		$query->select('CASE WHEN CHAR_LENGTH(rr.number) THEN rr.number ELSE pi.number END AS number');
		$query->select('pi.id AS piid');
		$query->select('i.first_name, i.last_name');
		$query->select('t.name as team_name, u.name AS editor');

		$query->from('#__tracks_rounds_results AS rr');
		$query->join('inner', '#__tracks_projects_subrounds AS sr ON sr.id = rr.subround_id');
		$query->join('inner', '#__tracks_projects_rounds AS pr ON pr.id = sr.projectround_id');
		$query->join('inner', '#__tracks_individuals AS i ON i.id = rr.individual_id');
		$query->join('inner', '#__tracks_projects_individuals AS pi ON (pi.individual_id = rr.individual_id AND pr.project_id = pi.project_id )');
		$query->join('left', '#__tracks_teams AS t ON t.id = pi.team_id');
		$query->join('left', '#__users AS u ON u.id = rr.checked_out');

		$query->where('rr.subround_id = ' . (int) $subround_id);

		if (!$overrideLimits)
		{
			$order = $this->getState('filter_order', null, 'cmd');

			$allowed = array_keys($table->getData());
			$allowed[] = 'team_name';
			$allowed[] = 'last_name';

			if (!in_array($order, $allowed))
			{
				$order = 'rr.rank';
			}

			$order = $db->qn($order);

			$dir = $this->getState('filter_order_Dir', 'ASC', 'cmd');
			$query->order($order . ' ' . $dir);
		}

		$filter = $this->getState('filter_state', '');
		if ($filter != '')
		{
			$query->where('rr.subround_id = ' . (int) $subround_id);
		}

		return $query;
	}

	function _buildQuery()
	{
		// Get the WHERE and ORDER BY clauses for the query
		$where = $this->_buildContentWhere();
		$orderby = $this->_buildContentOrderBy();

		$query = ' SELECT rr.*,
								pi.id AS piid, pi.team_id, pi.individual_id, pi.number,
								i.first_name, i.last_name,
								t.name as team_name, u.name AS editor '
			. ' FROM #__tracks_rounds_results AS rr '
			. ' INNER JOIN #__tracks_projects_subrounds AS sr ON sr.id = rr.subround_id '
			. ' INNER JOIN #__tracks_projects_rounds AS pr ON pr.id = sr.projectround_id '
			. ' INNER JOIN #__tracks_individuals AS i ON (i.id = rr.individual_id) '
			. ' INNER JOIN #__tracks_projects_individuals AS pi ON (pi.individual_id = rr.individual_id AND pr.project_id = pi.project_id ) '
			. ' LEFT JOIN #__tracks_teams AS t ON (t.id = pi.team_id) '
			. ' LEFT JOIN #__users AS u ON u.id = rr.checked_out '
			. $where
			. $orderby;
		return $query;
	}

	function _buildContentOrderBy()
	{
		$mainframe = JFactory::getApplication();
		$option = JRequest::getCmd('option');

		$filter_order = $mainframe->getUserStateFromRequest($option . '.viewsubroundresults.filter_order', 'filter_order', 'rr.rank', 'cmd');
		$filter_order_Dir = $mainframe->getUserStateFromRequest($option . '.viewsubroundresults.filter_order_Dir', 'filter_order_Dir', '', 'word');

		if ($filter_order == 'rr.rank')
		{
			$orderby = ' ORDER BY rr.rank ' . $filter_order_Dir . ', i.last_name ASC, i.first_name ASC ';
		}
		else
		{
			$orderby = ' ORDER BY ' . $filter_order . ' ' . $filter_order_Dir;
		}

		return $orderby;
	}

	public function getSubroundInfo()
	{
		$query = ' SELECT sr.projectround_id, '
			. ' r.name AS roundname, '
			. ' srt.name AS subroundname, sr.id AS subround_id, '
			. ' p.name AS projectname '
			. ' FROM #__tracks_projects_rounds AS pr '
			. ' INNER JOIN #__tracks_rounds AS r ON r.id = pr.round_id '
			. ' INNER JOIN #__tracks_projects_subrounds AS sr ON sr.projectround_id = pr.id '
			. ' INNER JOIN #__tracks_subroundtypes AS srt ON sr.type = srt.id '
			. ' INNER JOIN #__tracks_projects AS p ON p.id = pr.project_id '
			. ' WHERE sr.id=' . $this->subround_id;
		$this->_db->setQuery($query);
		$res = $this->_db->loadObject();
		//echo "debug: ";	print_r($res);exit;
		return $res;
	}

	/**
	 * Method to save ranks
	 *
	 * @access    public
	 *
	 * @return    boolean    True on success
	 *
	 * @since     1.5
	 */
	public function saveranks($cid = array(), $rank, $bonus_points, $performance, $individual, $team, $subround_id)
	{
		$row = $this->getTable('projectroundresult');

		// update ordering values
		for ($i = 0; $i < count($cid); $i++)
		{
			if (!$row->load((int) $cid[$i]))
			{
				// no entry yet for this player/round/project, create it
				$row->reset();
				$row->individual_id = $individual[$i];
				$row->team_id = $team[$i];
				$row->subround_id = $subround_id;
				$row->rank = $rank[$i];
				$row->bonus_points = $bonus_points[$i];
				$row->performance = $performance[$i];

				if (!$row->store())
				{
					$this->setError($this->_db->getErrorMsg());
					return false;
				}
			}
			else
			{
				if ($row->rank != $rank[$i]
					|| $row->bonus_points != $bonus_points[$i]
					|| $row->performance != $performance[$i]
				)
				{
					$row->rank = $rank[$i];
					$row->bonus_points = $bonus_points[$i];
					$row->performance = $performance[$i];
					$row->team_id = $team[$i];

					if (!$row->store())
					{
						$this->setError($this->_db->getErrorMsg());
						return false;
					}
				}
			}
		}
		return true;
	}

	protected function getProjectId()
	{
		if (!$this->project_id)
		{
			// get project id
			$this->_db->setQuery(
				' SELECT pr.project_id FROM #__tracks_projects_rounds AS pr '
				. ' INNER JOIN #__tracks_projects_subrounds AS sr ON sr.projectround_id = pr.id '
				. ' WHERE sr.id=' . $this->subround_id
			);
			$this->project_id = $this->_db->loadResult();
		}

		return $this->project_id;
	}

	/**
	 * Add individuals from project to sub round results.
	 *
	 */
	public function addAll()
	{
		$project_id = $this->getProjectId();

		if ($project_id)
		{
			$query = ' INSERT IGNORE INTO #__tracks_rounds_results (individual_id, team_id, subround_id) '
				. ' SELECT pi.individual_id, pi.team_id, ' . $this->subround_id
				. ' FROM #__tracks_projects_individuals AS pi '
				. ' WHERE pi.project_id = ' . $project_id;
			$this->_db->setQuery($query);

			$this->_db->query();

			if ($this->_db->getErrorNum())
			{
				echo $this->_db->getErrorMsg();
				$this->setError($this->_db->getErrorMsg());

				return false;
			}
		}

		return true;
	}
}
