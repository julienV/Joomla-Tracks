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

// ACL rules

if (version_compare(JVERSION, '1.6.0', '<'))
{
	$acl = JFactory::getACL();
	$acl->addACL( 'com_tracks', 'manage', 'users', 'super administrator' );
	/* Additional access groups */
	$acl->addACL( 'com_tracks', 'manage', 'users', 'administrator' );
}

require_once (JPATH_SITE.DS.'components'.DS.'com_tracks'.DS.'helpers'.DS.'trackshtml.php');
require_once (JPATH_SITE.DS.'components'.DS.'com_tracks'.DS.'helpers'.DS.'countries.php');
require_once (JPATH_SITE.DS.'components'.DS.'com_tracks'.DS.'helpers'.DS.'route.php');
require_once (JPATH_SITE.DS.'components'.DS.'com_tracks'.DS.'helpers'.DS.'tools.php');
require_once (JPATH_SITE.DS.'components'.DS.'com_tracks'.DS.'lib'.DS.'JLVImageTool.php');
