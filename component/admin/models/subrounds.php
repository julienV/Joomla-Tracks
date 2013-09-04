<?php
/**
* @version    $Id$
* @package    JoomlaTracks
* @copyright  Copyright (C) 2008 Julien Vonthron. All rights reserved.
* @license    GNU/GPL, see LICENSE.php
* Joomla Tracks is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();

/**
 * Joomla Tracks Component Projectrounds Model
 *
 * @package  Tracks
 * @since    0.1
 */
class TracksModelSubrounds extends FOFModel
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
		$projectround_id = $this->getState('prid');

		$query = $db->getQuery(true);

		$query->select('sr.*');
		$query->from('#__tracks_projects_subrounds AS sr');

		$query->select('srt.name as name');
		$query->join('inner', '#__tracks_subroundtypes AS srt ON srt.id = sr.type');

		$query->where('sr.projectround_id = ' . $projectround_id);

		if (!$overrideLimits)
		{
			$order = $this->getState('filter_order', null, 'cmd');

			$allowed = array_keys($table->getData());
			$allowed[] = 'name';
			$allowed[] = 'srt.name';

			if (!in_array($order, $allowed))
			{
				$order = 'sr.ordering';
			}

			$order = $db->qn($order);

			$dir = $this->getState('filter_order_Dir', 'ASC', 'cmd');
			$query->order($order . ' ' . $dir);
		}

		$filter = $this->getState('filter_state', '');
		if ($filter != '')
		{
			$query->where('sr.published = ' . $db->quote($filter));
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
		$query = ' DELETE rr FROM #__tracks_projects_subrounds AS sr, #__tracks_rounds_results AS rr '
			. ' WHERE sr.id = ' . (int) $id . ' AND sr.id = rr.subround_id ';

		$db->setQuery($query);
		if (!$db->query())
		{
			$this->setError($db->getErrorMsg());
			return false;
		}

		return true;
	}

	function _buildQuery()
	{
		// Get the WHERE and ORDER BY clauses for the query
		$where		= $this->_buildContentWhere();
		$orderby	= $this->_buildContentOrderBy();

		$query = ' SELECT obj.*, sr.name AS srtype, u.name AS editor '
			. ' FROM #__tracks_projects_subrounds AS obj '
			. ' INNER JOIN #__tracks_subroundtypes AS sr ON (sr.id = obj.type) '
			. ' LEFT JOIN #__users AS u ON u.id = obj.checked_out '
			. $where
			. $orderby
		;

		return $query;
	}

	function _buildContentOrderBy()
	{
		$mainframe = JFactory::getApplication();
		$option = JRequest::getCmd('option');

		$filter_order		= $mainframe->getUserStateFromRequest( $option.'.viewsubrounds.filter_order',		'filter_order',		'obj.ordering',	'cmd' );
		$filter_order_Dir	= $mainframe->getUserStateFromRequest( $option.'.viewsubrounds.filter_order_Dir',	'filter_order_Dir',	'',				'word' );

		if ($filter_order == 'obj.ordering'){
			$orderby 	= ' ORDER BY obj.ordering '.$filter_order_Dir;
		} else {
			$orderby 	= ' ORDER BY '.$filter_order.' '.$filter_order_Dir.' , obj.ordering ';
		}

		return $orderby;
	}

	function _buildContentWhere()
	{
		$mainframe = JFactory::getApplication();
		$option = JRequest::getCmd('option');
    $prid = JRequest::getVar('prid',0);

		$filter_state		= $mainframe->getUserStateFromRequest( $option.'.viewsubrounds.filter_state',		'filter_state',		'',				'word' );
		$search				= $mainframe->getUserStateFromRequest( $option.'.viewsubrounds.search',			'search',			'',				'string' );
		$search				= JString::strtolower( $search );

		$where = array();

		if ($search) {
			$where[] = 'LOWER(sr.name) LIKE '.$this->_db->Quote('%'.$search.'%');
		}
		if ( $filter_state ) {
			if ( $filter_state == 'P' ) {
				$where[] = 'obj.published = 1';
			} else if ($filter_state == 'U' ) {
				$where[] = 'obj.published = 0';
			}
		}

		$where 		= ' WHERE  obj.projectround_id = ' . $prid
                . implode( ' AND ', $where );

		return $where;
	}

	function getRound()
	{
		$query = ' SELECT r.name AS roundname, '
			. ' p.name AS projectname '
			. ' FROM #__tracks_projects_rounds AS pr '
			. ' INNER JOIN #__tracks_rounds AS r ON r.id = pr.round_id '
			. ' INNER JOIN #__tracks_projects AS p ON p.id = pr.project_id '
			. ' WHERE pr.id=' . $this->getState('prid');;
		$this->_db->setQuery($query);
		$res = $this->_db->loadObject();
		//echo "debug: "; print_r($res);exit;
		return $res;
	}
}
