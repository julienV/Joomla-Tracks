<<<<<<< HEAD
<?php
/**
* @version    $Id: individuals.php 15 2008-02-06 00:37:43Z julienv $ 
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
require_once (JPATH_SITE.'/administrator/components/com_tracks/models/list.php');

/**
 * Joomla Tracks Component Individuals Model
 *
 * @package		Tracks
 * @since 0.1
 */
class TracksModelIndividuals extends TracksModelList
{
	function _buildQuery()
	{
		// Get the WHERE and ORDER BY clauses for the query
		$where		= $this->_buildContentWhere();
		$orderby	= $this->_buildContentOrderBy();
		
		$query = ' SELECT obj.id, obj.first_name, obj.last_name, obj.country_code , obj.gender , obj.alias, obj.checked_out, obj.checked_out_time, '
      . ' u.name AS editor, '
      . ' ui.name AS individual_user '
			. ' FROM #__tracks_individuals AS obj '
			. ' LEFT JOIN #__users AS u ON u.id = obj.checked_out '
			. ' LEFT JOIN #__users AS ui ON ui.id = obj.user_id '
			. $where
			. $orderby
		;

		return $query;
	}

	function _buildContentOrderBy()
	{
		$mainframe = &JFactory::getApplication();
		$option = JRequest::getCmd('option');

		$filter_order		= $mainframe->getUserStateFromRequest( $option.'.viewindividuals.filter_order',		'filter_order',		'obj.last_name',	'cmd' );
		$filter_order_Dir	= $mainframe->getUserStateFromRequest( $option.'.viewindividuals.filter_order_Dir',	'filter_order_Dir',	'ASC',				'word' );

		if ($filter_order == 'obj.last_name'){
			$orderby 	= ' ORDER BY obj.last_name '.$filter_order_Dir;
		} else {
			$orderby 	= ' ORDER BY '.$filter_order.' '.$filter_order_Dir.' , obj.last_name ';
		}

		return $orderby;
	}

	function _buildContentWhere()
	{
		$mainframe = &JFactory::getApplication();
		$option = JRequest::getCmd('option');

		$search				= $mainframe->getUserStateFromRequest( $option.'.viewindividuals.search',			'search',			'',				'string' );
		$search				= JString::strtolower( $search );

		$where = array();

		if ($search) {
			$where[] = 'LOWER( CONCAT( obj.first_name, " ", obj.last_name ) )'
                  . ' LIKE '.$this->_db->Quote('%'.$search.'%');
		}

		$where 		= ( count( $where ) ? ' WHERE '. implode( ' AND ', $where ) : '' );

		return $where;
	}

}
?>
