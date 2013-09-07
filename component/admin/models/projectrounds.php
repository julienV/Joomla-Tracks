<?php
/**
 * @version        2.0
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
	 * @param   boolean $overrideLimits  Are we requested to override the set limits?
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
			$query->where('r.name LIKE (' . $db->quote('%' . $filter . '%') . ')');
		}

		$filter = $this->getState('filter_state', '');
		if ($filter != '')
		{
			$query->where('pr.published = ' . $db->quote($filter));
		}

		// For copy form
		$filter = $this->getState('cid');
		if ($filter && is_array($filter) && count($filter))
		{
			$query->where('pr.id IN (' . implode(', ', $filter) . ')');
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

		// delete subrounds and their results first
		$query = ' DELETE rr, sr FROM #__tracks_projects_rounds AS r '
			. ' INNER JOIN #__tracks_projects_subrounds AS sr ON r.id = sr.projectround_id '
			. ' LEFT JOIN #__tracks_rounds_results AS rr ON  rr.subround_id = sr.id'
			. ' WHERE r.id = ' . (int) $id;

		$db->setQuery($query);
		if (!$db->query())
		{
			$this->setError($db->getErrorMsg());
			return false;
		}

		return true;
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
	 * @return array
	 */
	public function getProjectsOptions()
	{
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);

		$query->select('id AS value, name AS text');
		$query->from('#__tracks_projects');
		$query->order('name');

		$db->setQuery($query);
		$res = $db->loadObjectList();

		return $res;
	}

	/**
	 * Method to store item(s)
	 *
	 * @access  public
	 *
	 * @param array the project round ids to copy
	 * @param int   the destination project id
	 *
	 * @return  boolean True on success
	 * @since   1.5
	 */
	public function assign($cids, $project_id)
	{
		$row = $this->getTable();

		$i = 0;
		for ($i = 0, $n = count($cids); $i < $n; $i++)
		{
			$cid =& $cids[$i];

			$query = 'SELECT *'
				. ' FROM #__tracks_projects_rounds '
				. ' WHERE id = ' . intval($cid);
			$this->_db->setQuery($query);
			$round = $this->_db->loadObject();
			if (!$round)
			{
				JError::raise(500, 'Round not found. ' . $this->_db->getErrorMsg());
			}
			if (!$round)
			{
				// not found...
				break;
			}

			$row->reset();
			$row->bind($round);
			$row->id = null;
			$row->project_id = $project_id;
			$row->checked_out = 0;
			$row->checked_out_time = null;

			// Store the item to the database
			if (!$row->store())
			{
				$this->setError($this->_db->getErrorMsg());
				JError::raise(500, 'Failed to copy round. ' . $this->_db->getErrorMsg());
			}

			// now copy subrounds
			$query = ' SELECT * '
				. ' FROM #__tracks_projects_subrounds'
				. ' WHERE projectround_id = ' . $cid;
			$this->_db->setQuery($query);
			$subrounds = $this->_db->loadObjectList();

			if (is_array($subrounds))
			{
				$subround = $this->getTable('Subround');

				foreach ($subrounds AS $s)
				{
					$subround->reset();
					$subround->bind($s);
					$subround->id = null;
					$subround->projectround_id = $row->id;
					$subround->checked_out = 0;
					$subround->checked_out_time = null;

					if (!$subround->store())
					{
						$this->setError($this->_db->getErrorMsg());
						JError::raise(500, 'Failed to copy subround. ' . $this->_db->getErrorMsg());
					}
				}
			}
		}

		return true;
	}
}
