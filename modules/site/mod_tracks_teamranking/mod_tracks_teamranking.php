<?php
/**
* @version    $Id: mod_tracks_teamranking.php 120 2008-05-30 01:59:54Z julienv $
* @package    JoomlaTracks
* @subpackage TeamRankingModule
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

$limit = intval( $params->get('count', 5) );

if (!$params->get('project_id')) return JText::_('MOD_TRACKS_TEAM_RANKING_No_project_specified');

$helper = new modTracksTeamRanking();

$project = $helper->getProject($params);

if (!$project) {
	echo JText::_('MOD_TRACKS_TEAMRANKING_PROJECT_NOT_FOUND');
	return;
}

$list = $helper->getList($params);

require(JModuleHelper::getLayoutPath('mod_tracks_teamranking'));
