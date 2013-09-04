<?php
/**
 * @version    2.0
 * @package    JoomlaTracks
 * @copyright  Copyright (C) 2008 Julien Vonthron. All rights reserved.
 * @license    GNU/GPL, see LICENSE.php
 *                 Joomla Tracks is free software. This version may have been modified pursuant
 *                 to the GNU General Public License, and as distributed it includes or
 *                 is derivative of works licensed under the GNU General Public License or
 *                 other free or open source software licenses.
 *                 See COPYRIGHT.php for copyright notices and details.
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();

/**
 * Joomla Tracks Component Projectrounds Model
 *
 * @package  Tracks
 * @since    0.1
 */
class TracksModelProjectrounds extends FOFModel
{
	/**
	 * Builds the SELECT query
	 *
	 * @param   boolean  $overrideLimits  Are we requested to override the set limits?
	 *
	 * @return  JDatabaseQuery
	 */
	public function buildQuery($overrideLimits = false)
	{
		$table = $this->getTable();
		$db = $this->getDbo();

		// Current project
		$app = JFactory::getApplication();
		$option = $app->input->getCmd('option', 'com_tracks');
		$project_id = $app->getUserState($option . 'project');

		$query = $db->getQuery(true);

		$query->select('pr.*');
		$query->from('#__tracks_projects_rounds AS pr');

		$query->select('r.name as name');
		$query->join('inner', '#__tracks_rounds AS r ON r.id = pr.round_id');

		$query->where('pr.project_id = ' . $project_id);

		if (!$overrideLimits)
		{
			$order = $this->getState('filter_order', null, 'cmd');

			$allowed = array_keys($table->getData());
			$allowed[] = 'name';
			$allowed[] = 'r.name';

			if (!in_array($order, $allowed))
			{
				$order = 'id';
			}

			$order = $db->qn($order);

			$dir = $this->getState('filter_order_Dir', 'ASC', 'cmd');
			$query->order($order . ' ' . $dir);
		}

		$filter = $this->getState('search');
		if ($filter)
		{
			$query->where('r.name LIKE (' . $db->quote('%' . $filter . '%')  . ')');
		}

		$filter = $this->getState('filter_state', '');
		if ($filter != '')
		{
			$query->where('pr.published = ' . $db->quote($filter));
		}

		return $query;
	}

	function _buildQuery()
	{
		// Get the WHERE and ORDER BY clauses for the query
		$where = $this->_buildContentWhere();
		$orderby = $this->_buildContentOrderBy();

		$query = ' SELECT obj.*, r.name AS name, u.name AS editor '
			. ' FROM #__tracks_projects_rounds AS obj '
			. ' INNER JOIN #__tracks_rounds AS r ON (r.id = obj.round_id) '
			. ' LEFT JOIN #__users AS u ON u.id = obj.checked_out '
			. $where
			. $orderby;

		return $query;
	}

	function _buildContentOrderBy()
	{
		$mainframe = JFactory::getApplication();
		$option = JRequest::getCmd('option');

		$filter_order = $mainframe->getUserStateFromRequest($option . '.viewprojectrounds.filter_order', 'filter_order', 'obj.ordering', 'cmd');
		$filter_order_Dir = $mainframe->getUserStateFromRequest($option . '.viewprojectrounds.filter_order_Dir', 'filter_order_Dir', '', 'word');

		if ($filter_order == 'obj.ordering')
		{
			$orderby = ' ORDER BY obj.ordering ' . $filter_order_Dir;
		}
		else
		{
			$orderby = ' ORDER BY ' . $filter_order . ' ' . $filter_order_Dir . ' , obj.ordering ';
		}

		return $orderby;
	}

	function _buildContentWhere()
	{
		$mainframe = JFactory::getApplication();
		$option = JRequest::getCmd('option');
		$project_id = $mainframe->getUserState($option . 'project');

		$filter_state = $mainframe->getUserStateFromRequest($option . '.viewprojectrounds.filter_state', 'filter_state', '', 'word');
		$search = $mainframe->getUserStateFromRequest($option . '.viewprojectrounds.search', 'search', '', 'string');
		$search = JString::strtolower($search);

		$where = array();

		$where[] = ' project_id = ' . $project_id;

		if ($search)
		{
			$where[] = 'LOWER(name) LIKE ' . $this->_db->Quote('%' . $search . '%');
		}
		if ($filter_state)
		{
			if ($filter_state == 'P')
			{
				$where[] = 'obj.published = 1';
			}
			else if ($filter_state == 'U')
			{
				$where[] = 'obj.published = 0';
			}
		}

		$where = ' WHERE ' . implode(' AND ', $where);

		return $where;
	}

	/**
	 * return list of rounds in the submitted rounds list
	 *
	 *
	 */
	public function getRoundsList()
	{
		$cids = JRequest::getVar('cid', NULL, 'post', 'array');
		if (!$cids) return NULL;
		$query = ' SELECT obj.id, r.name AS name '
			. ' FROM #__tracks_projects_rounds AS obj '
			. ' INNER JOIN #__tracks_rounds AS r ON (r.id = obj.round_id) '
			. ' WHERE obj.id IN (' . implode(',', $cids) . ')';
		$this->_db->setQuery($query);
		$res = $this->_db->loadObjectList();
		return $res;
	}

	/**
	 * return list of projects for select.
	 *
	 *
	 */
	public function getProjectsListOptions()
	{
		$query = ' SELECT id AS value, name AS text '
			. ' FROM #__tracks_projects '
			. ' ORDER BY name';
		$this->_db->setQuery($query);
		$res = $this->_db->loadObjectList();
		return $res;
	}
}
