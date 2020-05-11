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

$limit     = intval($params->get('count', 5));
$showteams = intval($params->get('showteams', 1));

if ($params->get('usecurrent', 0) && JRequest::getInt('p'))
{
	$params->set('project_id', JRequest::getInt('p'));
}

$helper = new ModTracksRanking($params);

$project = $helper->getProject();
$list    = $helper->getList();

if (!$project)
{
	return JText::_('No_project_specified');
}

require JModuleHelper::getLayoutPath('mod_tracks_ranking');
