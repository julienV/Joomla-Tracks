<?php
/**
* @version    $Id: teams.php 23 2008-02-15 08:36:46Z julienv $ 
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
require_once (JPATH_COMPONENT.DS.'models'.DS.'list.php');

/**
 * Joomla Tracks Component Teams Model
 *
 * @package		Tracks
 * @since 0.1
 */
class TracksModelTeams extends TracksModelList
{
	function _buildQuery()
	{
		// Get the WHERE and ORDER BY clauses for the query
		$where		= $this->_buildContentWhere();
		$orderby	= $this->_buildContentOrderBy();

		$query = ' SELECT obj.id, obj.name, obj.acronym, obj.alias, obj.checked_out, obj.checked_out_time, '
      . ' u.name AS editor '
			. ' FROM #__tracks_teams AS obj '
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

		$filter_order		= $mainframe->getUserStateFromRequest( $option.'.viewteams.filter_order',		'filter_order',		'obj.name',	'cmd' );
		$filter_order_Dir	= $mainframe->getUserStateFromRequest( $option.'.viewteams.filter_order_Dir',	'filter_order_Dir',	'ASC',				'word' );

		if ($filter_order == 'obj.name'){
			$orderby 	= ' ORDER BY obj.name '.$filter_order_Dir;
		} else {
			$orderby 	= ' ORDER BY '.$filter_order.' '.$filter_order_Dir.' , obj.name ';
		}

		return $orderby;
	}

	function _buildContentWhere()
	{
		$mainframe = JFactory::getApplication();
		$option = JRequest::getCmd('option');

		$search				= $mainframe->getUserStateFromRequest( $option.'.viewteams.search',			'search',			'',				'string' );
		$search				= JString::strtolower( $search );

		$where = array();

		if ($search) {
			$where[] = 'LOWER( obj.name )'
                  . ' LIKE '.$this->_db->Quote('%'.$search.'%');
		}

		$where 		= ( count( $where ) ? ' WHERE '. implode( ' AND ', $where ) : '' );

		return $where;
	}

}
?>

