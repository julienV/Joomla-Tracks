<?php
/**
* @version    $Id: helper.php 133 2008-06-08 10:24:29Z julienv $ 
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

jimport('joomla.application.component.controller');

/**
 * Tracks Component Helper
 *
 * @static
 * @package   JoomlaTracks
 * @since 0.2
 */
class TracksHelper
{

	/**
	 * returns select list filled with element of query
	 *
	 * @param string $query
	 * @param boolean $active
	 * @return html select
	 */
  public static function filterProject($query, $active = NULL)
  {
    // Initialize variables
    $db = & JFactory::getDBO();

    $projects[] = JHTML::_('select.option', '0', '- '.JText::_('COM_TRACKS_Select_Project').' -');
    $db->setQuery($query);
    $projects = array_merge($projects, $db->loadObjectList());

    $project = JHTML::_('select.genericlist',  $projects, 'projectid', 'class="inputbox" size="1" onchange="document.adminForm.submit( );"', 'value', 'text', $active);

    return $project;
  }
  

  /**
   * returns select list filled with element of query
   *
   * @param string $query
   * @param boolean $active
   * @return html select
   */
  public static function filterCompetition($query, $active = NULL)
  {
    // Initialize variables
    $db = & JFactory::getDBO();

    $objects[] = JHTML::_('select.option', '0', '- '.JText::_('COM_TRACKS_Select_Competition').' -');
    if ($query == '')
    {
      $query = 'SELECT id AS value, name AS text' .
        ' FROM #__tracks_competitions' .
        ' ORDER BY ordering, name';
    }
    $db->setQuery($query);
    $objects = array_merge($objects, $db->loadObjectList());

    $list = JHTML::_('select.genericlist',  $objects, 'competitionid', 'class="inputbox" size="1" onchange="document.adminForm.submit( );"', 'value', 'text', $active);

    return $list;
  }
  

  /**
   * returns select list filled with element of query
   *
   * @param string $query
   * @param boolean $active
   * @return html select
   */
  public static function filterSeason($query, $active = NULL)
  {
    // Initialize variables
    $db = & JFactory::getDBO();

    $objects[] = JHTML::_('select.option', '0', '- '.JText::_('COM_TRACKS_Select_Season').' -');
    if ($query == '')
    {
      $query = 'SELECT id AS value, name AS text' .
        ' FROM #__tracks_seasons' .
        ' ORDER BY ordering, name';
    }
    $db->setQuery($query);
    $objects = array_merge($objects, $db->loadObjectList());

    $list = JHTML::_('select.genericlist',  $objects, 'seasonid', 'class="inputbox" size="1" onchange="document.adminForm.submit( );"', 'value', 'text', $active);

    return $list;
  }
  
 /**
  * Select list of active users
  */
  public static function usersSelect( $name, $active, $nouser = 0, $javascript = NULL, $order = 'name', $reg = 1 )
  {
    $db =& JFactory::getDBO();

    $and = '';
    if ( $reg ) {
    // does not include registered users in the list
      $and = ' AND gid > 18';
    }

    $query = 'SELECT id AS value, CONCAT(name, " (", username, ")") AS text'
    . ' FROM #__users'
    . ' WHERE block = 0'
    . $and
    . ' ORDER BY '. $order
    ;
    $db->setQuery( $query );
    if ( $nouser ) {
      $users[] = JHTML::_('select.option',  '0', '- '. JText::_('COM_TRACKS_No_User' ) .' -' );
      $users = array_merge( $users, $db->loadObjectList() );
    } else {
      $users = $db->loadObjectList();
    }

    $users = JHTML::_('select.genericlist',   $users, $name, 'class="inputbox" size="1" '. $javascript, 'value', 'text', $active );

    return $users;
  }
}
?>
