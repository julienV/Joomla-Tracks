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

// no direct access
defined('_JEXEC') or die('Restricted access');

jimport( 'joomla.application.component.helper');
jimport( 'joomla.application.component.model');

/**
 * Joomla Tracks Component ProjectroundElement Model
 *
 * @author Julien Vonthron <julien.vonthron@gmail.com>
 * @package   Tracks
 * @since 0.2
 */
class TracksModelProjectroundElement extends JModel
{
  /**
   * rounds
   *
   * @var array
   */
  var $_list = null;

  var $_page = null;

  /**
   * Method to get rounds
   *
   * @since 1.5
   */
  function getList()
  {
    $mainframe = &JFactory::getApplication();
$option = JRequest::getCmd('option');

    if (!empty($this->_list)) {
      return $this->_list;
    }

    // Initialize variables
    $db   =& $this->getDBO();
    $filter = null;

    // Get some variables from the request
    $projectid      = JRequest::getVar( 'projectid', -1, '', 'int' );
    $competitionid = $mainframe->getUserStateFromRequest('projectroundelement.competitionid',  'competitionid', -1, 'int');
    $seasonid = $mainframe->getUserStateFromRequest('projectroundelement.seasonid',  'seasonid', -1, 'int');
    $redirect     = $projectid;
    $option       = JRequest::getCmd( 'option' );
    $filter_order   = $mainframe->getUserStateFromRequest('projectroundelement.filter_order',    'filter_order',   '', 'cmd');
    $filter_order_Dir = $mainframe->getUserStateFromRequest('projectroundelement.filter_order_Dir',  'filter_order_Dir', '', 'word');
    $filter_state   = $mainframe->getUserStateFromRequest('projectroundelement.filter_state',    'filter_state',   '', 'word');
    $filter_competitionid = $mainframe->getUserStateFromRequest('projectroundelement.filter_competitionid',  'filter_competitionid', -1, 'int');
    $limit        = $mainframe->getUserStateFromRequest('global.list.limit',          'limit', $mainframe->getCfg('list_limit'), 'int');
    $limitstart     = $mainframe->getUserStateFromRequest('projectroundelement.limitstart',      'limitstart',   0,  'int');
    $search       = $mainframe->getUserStateFromRequest('projectroundelement.search',        'search',     '', 'string');
    $search       = JString::strtolower($search);

    $where[] = "pr.published != 0";

    if (!$filter_order) {
      $filter_order = 'p.name, r.name';
    }
    $order = ' ORDER BY '. $filter_order .' '. $filter_order_Dir .', p.ordering';
    $all = 1;

    /*
     * Add the filter specific information to the where clause
     */
    // Section filter
    if ($projectid > 0) {
      $where[] = 'pr.project_id = '.(int) $projectid;
    }
    if ($competitionid > 0) {
      $where[] = 'p.competition_id = '.$db->Quote($competitionid);
    }
    if ($seasonid > 0) {
      $where[] = 'p.season_id = '.$db->Quote($seasonid);
    }

    // Only published projects
    $where[] = 'p.published = 1';

    // Keyword filter
    if ($search) {
      $where[] = 'LOWER( r.name ) LIKE '.$db->Quote( '%'.$db->getEscaped( $search, true ).'%', false );
    }

    // Build the where clause of the content record query
    $where = (count($where) ? ' WHERE '.implode(' AND ', $where) : '');

    // Get the total number of records
    $query = 'SELECT pr.id, r.name, p.name AS project, c.name AS competition, s.name AS season' .
        ' FROM #__tracks_projects_rounds AS pr' .
        ' INNER JOIN #__tracks_rounds AS r ON r.id = pr.round_id' .
        ' INNER JOIN #__tracks_projects AS p ON p.id = pr.project_id' .
        ' INNER JOIN #__tracks_competitions AS c ON c.id = p.competition_id' .
        ' INNER JOIN #__tracks_seasons AS s ON s.id = p.season_id' .
        $where;
    $db->setQuery($query);
    $total = $db->loadResult();

    // Create the pagination object
    jimport('joomla.html.pagination');
    $this->_page = new JPagination($total, $limitstart, $limit);

    // Get the project rounds
    $query = 'SELECT pr.id, r.name, p.name AS project, c.name AS competition, s.name AS season' .
        ' FROM #__tracks_projects_rounds AS pr' .
        ' INNER JOIN #__tracks_rounds AS r ON r.id = pr.round_id' .
        ' INNER JOIN #__tracks_projects AS p ON p.id = pr.project_id' .
        ' INNER JOIN #__tracks_competitions AS c ON c.id = p.competition_id' .
        ' INNER JOIN #__tracks_seasons AS s ON s.id = p.season_id' .
        $where .
        $order;
    $db->setQuery($query, $this->_page->limitstart, $this->_page->limit);
    $this->_list = $db->loadObjectList();

    // If there is a db query error, throw a HTTP 500 and exit
    if ($db->getErrorNum()) {
      JError::raiseError( 500, $db->stderr() );
      return false;
    }

    return $this->_list;
  }

  function getPagination()
  {
    if (is_null($this->_list) || is_null($this->_page)) {
      $this->getList();
    }
    return $this->_page;
  }
}
?>
