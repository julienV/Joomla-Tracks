<?php
/**
* @version    $Id$ 
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
 * Joomla Tracks Component Projectrounds Model
 *
 * @package		Tracks
 * @since 0.1
 */
class TracksModelSubrounds extends TracksModelList
{	
 
  var $projectround_id = 0;
	
  /**
   * Constructor
   *
   * @since 0.2
   */
  function __construct()
  {
    parent::__construct();
    
    $prid = JRequest::getVar('prid', 0, '', 'int');
    $this->projectround_id = $prid;
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
		global $mainframe, $option;

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
		global $mainframe, $option;
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
            . ' WHERE pr.id=' . $this->projectround_id 
            ;
    $this->_db->setQuery( $query );
    $res = $this->_db->loadObject();
    //echo "debug: "; print_r($res);exit;
    return $res;
	}
}
?>
