<?php
/**
* @version    $Id: tracks.php 109 2008-05-24 11:05:07Z julienv $
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

// Load FOF
include_once JPATH_LIBRARIES.'/fof/include.php';
if(!defined('FOF_INCLUDED'))
{
	throw new Exception('FOF is not installed', 500);

	return;
}

// require core
require_once (JPATH_COMPONENT. '/' .'tracks.core.php');

// Require the base controller
require_once (JPATH_COMPONENT. '/' .'controller.php');

switch (JFactory::getApplication()->input->getCmd('view', ''))
{
	case 'teamedit':
	case 'individualedit':
	case 'profile':
		FOFDispatcher::getTmpInstance('com_tracks')->dispatch();
		return;
}

// Require specific controller if requested
if($controller = JRequest::getWord('controller')) {
    $path = JPATH_COMPONENT. '/' .'controllers'. '/' .$controller.'.php';
    if (file_exists($path)) {
        require_once $path;
    } else {
        $controller = '';
    }
}

// Create the controller
$classname	= 'TracksController'.ucfirst($controller);
$controller = new $classname( );

// Perform the Request task
$controller->execute( JRequest::getVar('task'));

// Redirect if set by the controller
$controller->redirect();
