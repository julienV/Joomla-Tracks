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

// No direct access
defined('_JEXEC') or die('Restricted access');

if (!defined('FOF_INCLUDED'))
{
	include_once JPATH_LIBRARIES.'/fof/include.php';
	if(!defined('FOF_INCLUDED') || !class_exists('FOFForm', true))
	{
		throw new Exception('Your Akeeba Release System installation is broken; please re-install. Alternatively, extract the installation archive and copy the fof directory inside your site\'s libraries directory.', 500);
	}
}

// Include the syndicate functions only once
require_once (dirname(__FILE__).DS.'helper.php');
include_once (JPATH_SITE.DS.'components'.DS.'com_tracks'.DS.'helpers'.DS.'route.php');

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
		$team_id = $input->getInt('t', 0);
	}
}

if (!$team_id)
{
	// Nothing to display
	return;
}

$helper = new modTracksTeamsocial();

$links = $helper->getTeamLinks($team_id);

//add css file
$document = JFactory::getDocument();
$document->addStyleSheet(JURI::base().'modules/mod_tracks_teamsocial/mod_tracks_teamsocial.css');

require(JModuleHelper::getLayoutPath('mod_tracks_teamsocial'));
