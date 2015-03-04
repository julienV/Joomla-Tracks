<?php
/**
* @version    $Id: tracks.php 109 2008-05-24 11:05:07Z julienv $
* @package    JoomlaTracks
* @subpackage RankingModule
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
require_once (dirname(__FILE__). '/' .'helper.php');
include_once (JPATH_SITE. '/' .'components'. '/' .'com_tracks'. '/' .'helpers'. '/' .'route.php');
include_once (JPATH_SITE. '/' .'components'. '/' .'com_tracks'. '/' .'lib'. '/' .'JLVImageTool.php');

$limit = intval( $params->get('count', 5) );
$showteams = intval( $params->get('showteams', 1) );

if ($params->get('usecurrent', 0) && JRequest::getInt('p')) {
	$params->set('project_id', JRequest::getInt('p'));
}
if (!$params->get('project_id')) return JText::_('No_project_specified');

$helper = new modTracksRanking();

$project = $helper->getProject($params);
$list = $helper->getList($params);

//add css file
$document = JFactory::getDocument();
$document->addStyleSheet(JURI::base().'modules/mod_tracks_ranking/mod_tracks_ranking.css');

require(JModuleHelper::getLayoutPath('mod_tracks_ranking'));
