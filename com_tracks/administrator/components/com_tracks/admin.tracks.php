<?php
/**
* @version    $Id: admin.tracks.php 125 2008-06-05 21:11:14Z julienv $ 
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
defined( '_JEXEC' ) or die( 'Restricted access' );

/*
 * Make sure the user is authorized to view this page
 */
 /*
$user = & JFactory::getUser();
if (!$user->authorize( 'com_tracks', 'manage' )) {
	$mainframe->redirect( 'index.php', JText::_('COM_TRACKS_ALERTNOTAUTH') );
}*/

// Require the base controller
require_once (JPATH_COMPONENT.DS.'controllers'.DS.'base.php');
require_once (JPATH_COMPONENT.DS.'controller.php');
// the helpers
require_once(JPATH_COMPONENT.DS.'helper.php');
require_once(JPATH_COMPONENT.DS.'helpers'.DS.'imageselect.php');
require_once(JPATH_COMPONENT_SITE.DS.'helpers'.DS.'countries.php');

// and the abstract view
require_once (JPATH_COMPONENT.DS.'abstract'.DS.'tracksview.php');

// Require specific controller if requested
if($controller = JRequest::getWord('controller')) {
	$path = JPATH_COMPONENT.DS.'controllers'.DS.$controller.'.php';
	if (file_exists($path)) {
		require_once $path;
	} else {
		$controller = '';
	}
}

// Set the table directory
JTable::addIncludePath( JPATH_COMPONENT.DS.'tables' );

// Create the controller
$classname	= 'TracksController'.ucfirst($controller);
$controller	= new $classname( );

// Perform the Request task
$controller->execute( JRequest::getCmd('task'));
$controller->redirect();

?>
