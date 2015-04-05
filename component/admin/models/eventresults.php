<?php
/**
 * @package     Tracks
 * @subpackage  Admin
 * @copyright   Tracks (C) 2008-2015 Julien Vonthron. All rights reserved.
 * @license     GNU General Public License version 2 or later
 */

defined('_JEXEC') or die();

/**
 * Tracks Component Event Results Model
 *
 * @package     Tracks
 * @subpackage  Admin
 * @since       3.0
 */
class TracksModelEventResults extends RModelList
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
		$event_id = $this->getState('event_id');

		$query = $db->getQuery(true);

		$query->select('rr.id, rr.individual_id, rr.team_id, rr.event_id, rr.rank');
		$query->select('rr.performance, rr.bonus_points, rr.comment');
		$query->select('CASE WHEN CHAR_LENGTH(rr.number) THEN rr.number ELSE pi.number END AS number');
		$query->select('pi.id AS piid');
		$query->select('i.first_name, i.last_name');

		$query->from('#__tracks_rounds_results AS rr');
		$query->join('inner', '#__tracks_projects_events AS sr ON sr.id = rr.event_id');
		$query->join('inner', '#__tracks_projects_rounds AS pr ON pr.id = sr.projectround_id');
		$query->join('inner', '#__tracks_individuals AS i ON i.id = rr.individual_id');
		$query->join('inner', '#__tracks_projects_individuals AS pi ON (pi.individual_id = rr.individual_id AND pr.project_id = pi.project_id )');
		$query->join('left', '#__tracks_teams AS t ON t.id = pi.team_id');
		$query->join('left', '#__users AS u ON u.id = rr.checked_out');

		$query->where('rr.event_id = ' . (int) $event_id);

		if (!$overrideLimits)
		{
			$order = $this->getState('filter_order', null, 'cmd');
			$dir = $this->getState('filter_order_Dir', 'ASC', 'cmd');

			$alpha = $db->qn('i.last_name') . ' ASC, ' . $db->qn('i.first_name') . ' ASC';

			switch ($order)
			{
				case 'number':
					$col = $db->qn('number');
					$query->order($col . ' ' . $dir . ', ' . $alpha);
					break;

				case 'participant':
					$order = $db->qn('i.last_name') . ' ' . $dir . ', ' . $db->qn('i.first_name') . ' ' . $dir;
					$query->order($order);
					break;

				case 'team':
					$col = $db->qn('t.name');
					$query->order($col . ' ' . $dir . ', ' . $alpha);
					break;

				case 'performance':
					$order = $db->qn('rr.performance');
					$query->order($order . ' ' . $dir . ', ' . $alpha);
					break;

				case 'bonus_points':
					$order = $db->qn('rr.bonus_points');
					$query->order($order . ' ' . $dir . ', ' . $alpha);
					break;

				case 'rank':
				default:
					$query->order($db->qn('rr.rank') . ' = 0 ' . $dir . ', ' . $db->qn('rr.rank') . ' ' . $dir . ', ' . $alpha);
					break;
			}
		}

		$filter = $this->getState('filter_state', '');
		if ($filter != '')
		{
			$query->where('rr.event_id = ' . (int) $event_id);
		}

		return $query;
	}

	public function getEventInfo()
	{
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);

		$query->select('sr.projectround_id, sr.id AS event_id');
		$query->select('r.name AS roundname');
		$query->select('p.name AS projectname');
		$query->select('srt.name AS eventname');
		$query->from('#__tracks_projects_rounds AS pr');
		$query->join('INNER', '#__tracks_rounds AS r ON r.id = pr.round_id');
		$query->join('INNER', '#__tracks_projects_events AS sr ON sr.projectround_id = pr.id');
		$query->join('INNER', '#__tracks_eventtypes AS srt ON sr.type = srt.id');
		$query->join('INNER', '#__tracks_projects AS p ON p.id = pr.project_id');
		$query->where('sr.id = ' . $this->getState('event_id'));

		$db->setQuery($query);
		$res = $db->loadObject();

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
	public function saveranks($cid = array(), $rank, $bonus_points, $performance, $individual, $team, $event_id)
	{
		$row = $this->getTable('eventresult');

		// update ordering values
		for ($i = 0; $i < count($cid); $i++)
		{
			if (!$row->load((int) $cid[$i]))
			{
				// no entry yet for this player/round/project, create it
				$row->reset();
				$row->individual_id = $individual[$i];
				$row->team_id = $team[$i];
				$row->event_id = $event_id;
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
				. ' INNER JOIN #__tracks_projects_events AS sr ON sr.projectround_id = pr.id '
				. ' WHERE sr.id=' . $this->event_id
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
			$query = ' INSERT IGNORE INTO #__tracks_rounds_results (individual_id, team_id, event_id) '
				. ' SELECT pi.individual_id, pi.team_id, ' . $this->event_id
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
