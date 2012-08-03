<?php
/**
* @version    $Id: projectindividuals.php 23 2008-02-15 08:36:46Z julienv $ 
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
 * Joomla Tracks Component Projectindividuals Model
 *
 * @package		Tracks
 * @since 0.1
 */
class TracksModelProjectindividuals extends TracksModelList
{
	function _buildQuery()
	{
		// Get the WHERE and ORDER BY clauses for the query
		$where		= $this->_buildContentWhere();
		$orderby	= $this->_buildContentOrderBy();

		$query = ' SELECT obj.*, i.first_name, i.last_name, t.name as team_name, u.name AS editor '
			. ' FROM #__tracks_projects_individuals AS obj '
			. ' INNER JOIN #__tracks_individuals AS i ON (i.id = obj.individual_id) '
			. ' LEFT JOIN #__tracks_teams AS t ON (t.id = obj.team_id) '
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

		$filter_order		= $mainframe->getUserStateFromRequest( $option.'.viewprojectindividuals.filter_order',		'filter_order',		't.name',	'cmd' );
		$filter_order_Dir	= $mainframe->getUserStateFromRequest( $option.'.viewprojectindividuals.filter_order_Dir',	'filter_order_Dir',	'',				'word' );

		if ($filter_order == 't.name'){
			$orderby 	= ' ORDER BY t.name '.$filter_order_Dir.', i.last_name ASC, i.first_name ASC ';
		} else {
			$orderby 	= ' ORDER BY '.$filter_order.' '.$filter_order_Dir;
		}

		return $orderby;
	}

	function _buildContentWhere()
	{
		$mainframe = JFactory::getApplication();
		$option = JRequest::getCmd('option');
    $project_id = $mainframe->getUserState( $option.'project' );
    
		//$filter_state		= $mainframe->getUserStateFromRequest( $option.'.viewprojectindividuals.filter_state',		'filter_state',		'',				'word' );
		$search				= $mainframe->getUserStateFromRequest( $option.'.viewprojectindividuals.search',			'search',			'',				'string' );
		$search				= JString::strtolower( $search );

		$where = array();
		
		$where[] = 'obj.project_id = ' . $project_id;

		if ($search) {
			$where[] = 'LOWER( CONCAT(i.first_name, i.last_name) ) LIKE '.$this->_db->Quote('%'.$search.'%');
		}
		/*
		if ( $filter_state ) {
			if ( $filter_state == 'P' ) {
				$where[] = 'obj.published = 1';
			} else if ($filter_state == 'U' ) {
				$where[] = 'obj.published = 0';
			}
		}
		*/
    
		$where 		= ' WHERE ' . implode( ' AND ', $where );

		return $where;
	}
	
	/**
	 * return list of players in the submitted assign list
	 *
	 * 
	 */
	function getAssignList()
	{
	  $cids = JRequest::getVar( 'cid', NULL, 'post', 'array' );
	  if (!$cids) return NULL;
	  $query = ' SELECT id, last_name, first_name '
	         . ' FROM #__tracks_individuals AS i '
	         . ' WHERE id IN (' . implode(',',$cids) . ')';
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
      $query  = ' SELECT id AS value, name AS text '
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
   * @since 1.5
   */
  function getTeams() 
  {
    $query = 'SELECT id, name'
          . ' FROM #__tracks_teams '
          . ' ORDER BY name ASC ';
    $this->_db->setQuery( $query );
    if ( !$result = $this->_db->loadObjectList() ) {
      $this->setError($this->_db->getErrorMsg());
      return false;
    }
    else return $result;
  }
}
?>
