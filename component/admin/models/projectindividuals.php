<?php
/**
 * @version        $Id: projectindividuals.php 23 2008-02-15 08:36:46Z julienv $
 * @package        JoomlaTracks
 * @copyright      Copyright (C) 2008 Julien Vonthron. All rights reserved.
 * @license        GNU/GPL, see LICENSE.php
 *                 Joomla Tracks is free software. This version may have been modified pursuant
 *                 to the GNU General Public License, and as distributed it includes or
 *                 is derivative of works licensed under the GNU General Public License or
 *                 other free or open source software licenses.
 *                 See COPYRIGHT.php for copyright notices and details.
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();

jimport('joomla.application.component.model');
require_once(JPATH_COMPONENT . '/' . 'models' . '/' . 'list.php');

/**
 * Joomla Tracks Component Projectindividuals Model
 *
 * @package        Tracks
 * @since          0.1
 */
class TracksModelProjectindividuals extends FOFModel
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

		// Current project
		$project_id = (int) $this->getState('project_id');

		if (!$project_id)
		{
			$app = JFactory::getApplication();
			$option = $app->input->getCmd('option', 'com_tracks');
			$project_id = $app->getUserState($option. 'project');
		}

		$query = $db->getQuery(true);

		$query->select('obj.*, i.first_name, i.last_name, t.name as team_name, u.name AS editor');
		$query->select('CASE WHEN CHAR_LENGTH(i.first_name) THEN CONCAT(i.last_name, ' . $db->quote(', ') . ', i.first_name) ELSE i.last_name END AS name');

		$query->from('#__tracks_projects_individuals AS obj');
		$query->join('inner', '#__tracks_individuals AS i ON i.id = obj.individual_id');
		$query->join('left', '#__tracks_teams AS t ON t.id = obj.team_id');
		$query->join('left', '#__users AS u ON u.id = obj.checked_out');

		$query->where('obj.project_id = ' . $project_id);

			$order = $this->getState('filter_order', null, 'cmd');
			$dir = $this->getState('filter_order_Dir', 'ASC', 'cmd');

			switch ($order)
			{
				case 'number':
					$order = $db->qn('number');
					$query->order($order . ' ' . $dir);
					break;

				case 'team':
					$order = $db->qn('t.name');
					$query->order($order . ' ' . $dir);
					break;

				case 'id':
					$order = $db->qn('obj.id');
					$query->order($order . ' ' . $dir);
					break;

				case 'name':
				default:
					$order = $db->qn('i.last_name') . ' ' . $dir . ', ' . $db->qn('i.first_name') . ' ' . $dir;
					$query->order($order);
					break;
			}

		$filter = $this->getState('search');
		if ($filter)
		{
			$query->where('LOWER( CONCAT(i.first_name, i.last_name) ) LIKE ' . $db->quote('%' . $filter . '%'));
		}

		return $query;
	}

	/**
	 * This method runs before the record with key value of $id is deleted from $table
	 *
	 * @param   integer  &$id     The ID of the record being deleted
	 * @param   FOFTable &$table  The table instance used to delete the record
	 *
	 * @return  boolean
	 */
	protected function onBeforeDelete(&$id, &$table)
	{
		$db = $this->getDbo();

		// delete their results first
		$query = ' DELETE rr FROM #__tracks_rounds_results AS rr '
			. ' INNER JOIN #__tracks_projects_subrounds AS psr ON psr.id = rr.subround_id '
			. ' INNER JOIN #__tracks_projects_rounds AS pr ON pr.id = psr.projectround_id '
			. ' INNER JOIN #__tracks_projects_individuals AS pi ON pi.individual_id = rr.individual_id AND pi.project_id = pr.project_id '
			. ' WHERE pi.id = ' . (int) $id;

		$db->setQuery($query);
		if (!$db->query())
		{
			$this->setError($db->getErrorMsg());
			return false;
		}

		return true;
	}

	/**
	 * Method to store mutliple rows
	 *
	 * @access  public
	 *
	 * @param array rows to be inserted
	 *
	 * @return  false|int count of inserted rows
	 */
	public function assign($rows)
	{
		$rec = 0;
		$new = $this->getTable();
		for ($i = 0, $n = count($rows); $i < $n; $i++)
		{
			$row =& $rows[$i];

			$query = 'SELECT id '
				. ' FROM #__tracks_projects_individuals '
				. ' WHERE individual_id = ' . intval($row->individual_id)
				. ' AND project_id = ' . intval($row->project_id);
			$this->_db->setQuery($query);

			if (count($this->_db->loadObjectList()))
			{
				// Already assigned
				continue;
			}

			$new->reset();
			$new->set('id', null);
			$new->bind($row);

			// Store the item to the database
			if (!$new->store())
			{
				$this->setError($this->_db->getErrorMsg());
				return false;
			}

			$rec++;
		}

		return $rec;
	}

	/**
	 * return list of players in the submitted assign list
	 *
	 *
	 */
	function getAssignList()
	{
		$cids = JRequest::getVar('cid', NULL, 'post', 'array');
		if (!$cids) return NULL;
		$query = ' SELECT id, last_name, first_name '
			. ' FROM #__tracks_individuals AS i '
			. ' WHERE id IN (' . implode(',', $cids) . ')';
		$this->_db->setQuery($query);
		$res = $this->_db->loadObjectList();
		return $res;
	}

	/**
	 * return list of projects for select.
	 *
	 *
	 */
	function getProjectsListOptions()
	{
		$query = ' SELECT id AS value, name AS text '
			. ' FROM #__tracks_projects '
			. ' ORDER BY name';
		$this->_db->setQuery($query);
		$res = $this->_db->loadObjectList();
		return $res;
	}

	/**
	 * Method to return a teams array (id, name)
	 *
	 * @access  public
	 * @return  array seasons
	 * @since   1.5
	 */
	function getTeams()
	{
		$query = 'SELECT id, name'
			. ' FROM #__tracks_teams '
			. ' ORDER BY name ASC ';
		$this->_db->setQuery($query);
		if (!$result = $this->_db->loadObjectList())
		{
			$this->setError($this->_db->getErrorMsg());
			return false;
		}
		else return $result;
	}
}
