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

$limit      = intval($params->get('count', 5));
$showteams  = intval($params->get('showteams', 1));
$showpoints = intval($params->get('showpoints', 1));

$helper = new ModTracksLatestResults($params);

if (!$event   = $helper->getEvent())
{
	return JText::_('MOD_TRACKS_LATEST_RESULTS_NO_RESULTS');
}

$list    = $helper->getList();
$project   = $helper->getProject();
$projectRound   = $helper->getProjectround();

require JModuleHelper::getLayoutPath('mod_tracks_latest_results');
