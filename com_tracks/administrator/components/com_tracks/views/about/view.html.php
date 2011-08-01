<?php
/**
* @version    $Id: view.html.php 7 2008-01-30 10:37:37Z julienv $ 
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

jimport( 'joomla.application.component.view');

require_once (JPATH_COMPONENT.DS.'abstract'.DS.'tracksview.php');

/**
 * HTML View class for the Tracks component
 *
 * @static
 * @package		Tracks
 * @since 0.1
 */
class TracksViewAbout extends TracksView
{
	function display($tpl = null)
	{
		$mainframe = &JFactory::getApplication();
		$option = JRequest::getCmd('option');

        // Set toolbar items for the page
        JToolBarHelper::title(   JText::_( 'About Tracks' ), 'help_header' );
        JToolBarHelper::back();
        
        $db     =& JFactory::getDBO();
        $uri    =& JFactory::getURI();
        $user   =& JFactory::getUser();
        
        parent::display($tpl);
	}
}
?>
