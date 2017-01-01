<?php
/**
 * @package     JoomlaTracks
 * @subpackage  Modules.site
 * @copyright   Copyright (C) 2008-2015 Julien Vonthron. All rights reserved.
 * @license     GNU General Public License version 2 or later
 */

defined('_JEXEC') or die('Restricted access');

$tracksLoader = JPATH_LIBRARIES . '/tracks/bootstrap.php';

if (!file_exists($tracksLoader))
{
	throw new Exception(JText::_('COM_TRACKS_LIB_INIT_FAILED'), 404);
}

include_once $tracksLoader;

// Bootstraps Tracks
TrackslibBootstrap::bootstrap();

// Include the syndicate functions only once
require_once dirname(__FILE__) . '/helper.php';

$limit = intval($params->get('count', 5));

if (!$params->get('project_id'))
{
	return JText::_('MOD_TRACKS_TEAM_RANKING_No_project_specified');
}

$helper = new modTracksTeamRanking;

$project = $helper->getProject($params);

if (!$project)
{
	echo JText::_('MOD_TRACKS_TEAMRANKING_PROJECT_NOT_FOUND');

	return;
}

$list = $helper->getList($params);

require(JModuleHelper::getLayoutPath('mod_tracks_teamranking'));
