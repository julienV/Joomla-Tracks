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

// no direct access
defined('_JEXEC') or die('Restricted access');

class HTMLTracks
{
	function footer()
	{		
    echo 'Tracks powered by <a href="http://www.jlv-solutions.com">JLV Solutions</a>';
	}
	
 /**
  * Select list of active users
  */
  function usersSelect( $name, $active, $nouser = 0, $javascript = NULL, $order = 'name', $reg = 1 )
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
  
  /**
   * returns thumbnail url
   * 
   * @param object $individual
   * @param int $size
   * @return string url
   */
  public static function getIndividualThumb($individual, $size)
  {
  	$path = JPATH_SITE.DS.'media'.DS.'com_tracks'.DS.'images'.DS.'individuals'.DS.'small'.DS.$individual->picture_small;
  	if ($individual->picture_small && file_exists($path)) {
  		return JLVImageTool::getThumbUrl($path, $size);
  	}
  	else if ($individual->gender == 2) {
  		return JLVImageTool::getThumbUrl(JPATH_SITE.DS.'media'.DS.'com_tracks'.DS.'images'.DS.'individuals'.DS.'tnnophoto2.jpg', $size);
  	}
  	else {
  		return JLVImageTool::getThumbUrl(JPATH_SITE.DS.'media'.DS.'com_tracks'.DS.'images'.DS.'individuals'.DS.'tnnophoto.jpg', $size);  		
  	}
  }
}

?>