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

$team_id = 0;

// Find team id
if ($params->get('team_id'))
{
	$team_id = $params->get('team_id');
}
else
{
	// Check if we are displaying a tracks team page, and use id
	$input = JFactory::getApplication()->input;

	if ($input->getCmd('option', '') == 'com_tracks' && $input->getCmd('view', 'team'))
	{
		$team_id = $input->getInt('id', 0);
	}
}

if (!$team_id)
{
	// Nothing to display
	return;
}

$helper = new ModTracksTeamsocial;

$links = $helper->getTeamLinks($team_id);

require JModuleHelper::getLayoutPath('mod_tracks_teamsocial');
