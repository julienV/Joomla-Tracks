<?php
/**
* @package   JoomlaTracks
* @copyright Copyright (C) 2008 Julien Vonthron. All rights reserved.
* @license   GNU/GPL, see LICENSE.php
* Joomla Tracks is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/

// No direct access
defined('_JEXEC') or die('Restricted access');

// Access check.
if (!JFactory::getUser()->authorise('core.manage', 'com_tracks'))
{
	throw new Exception(JText::_('JERROR_ALERTNOAUTHOR'), 404);
}

// Require the base controller
require_once (JPATH_COMPONENT.DS.'controllers'.DS.'base.php');
require_once (JPATH_COMPONENT.DS.'controller.php');
// the helpers
require_once(JPATH_COMPONENT.DS.'helper.php');
require_once(JPATH_COMPONENT.DS.'helpers'.DS.'imageselect.php');
require_once(JPATH_COMPONENT_SITE.DS.'helpers'.DS.'countries.php');

// and the abstract view
require_once (JPATH_COMPONENT.DS.'abstract'.DS.'tracksview.php');

defined('_JEXEC') or die();

// Load FOF
include_once JPATH_LIBRARIES.'/fof/include.php';
if(!defined('FOF_INCLUDED'))
{
	throw new Exception('FOF is not installed', 500);

	return;
}

FOFDispatcher::getTmpInstance('com_tracks')->dispatch();
