<?php
/**
* @version    $Id: mod_tracks_results.php 137 2008-06-10 06:12:03Z julienv $ 
* @package    JoomlaTracks
* @subpackage ResultsModule
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

// Include the syndicate functions only once
require_once (dirname(__FILE__).DS.'helper.php');
include_once (JPATH_SITE.DS.'components'.DS.'com_tracks'.DS.'helpers'.DS.'route.php');

$limit = intval( $params->get('count', 5) );
$showteams = intval( $params->get('showteams', 1) );
$showpoints = intval( $params->get('showpoints', 1) );

if (!$params->get('project_id')) return JText::_('No project specified');

$helper = new modTracksResults();

$project = $helper->getProject($params);
$round = $helper->getRound($params);
$list = $helper->getList($params);

require(JModuleHelper::getLayoutPath('mod_tracks_results'));
