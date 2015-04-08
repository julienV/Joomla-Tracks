<?php
/**
 * @version    $Id: view.html.php 89 2008-05-02 06:46:38Z julienv $
 * @package    JoomlaTracks
 * @copyright  Copyright (C) 2008 Julien Vonthron. All rights reserved.
 * @license    GNU/GPL, see LICENSE.php
 * Joomla Tracks is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * See COPYRIGHT.php for copyright notices and details.
 */

/**
 * transform the url passed in JRoute function for SEF display
 *
 * @param url $query
 *
 * @return array
 */
function TracksBuildRoute(&$query)
{
	$segments = array();

	// Get a menu item based on Itemid or currently active
	$app = JFactory::getApplication();
	$menu = $app->getMenu();
	$params = JComponentHelper::getParams('com_tracks');

	// We need a menu item.  Either the one specified in the query, or the current active one if none specified
	if (empty($query['Itemid']))
	{
		$menuItem = $menu->getActive();
		$menuItemGiven = false;
	}
	else
	{
		$menuItem = $menu->getItem($query['Itemid']);
		$menuItemGiven = true;
	}

	$view = '';

	if (isset($query['view']))
	{
		$segments[] = $query['view'];
		$view = $query['view'];
		unset($query['view']);
	}

	switch ($view)
	{
		case 'project':
		case 'ranking':
		case 'teamranking':
		case 'roundresult':
			if (isset($query['id']))
			{
				$segments[] = $query['id'];
				unset($query['id']);
			}
			break;

		case 'individual':
		case 'team':
		case 'round':
			if (isset($query['id']))
			{
				$segments[] = $query['id'];
				unset($query['id']);
			}

			if (isset($query['p']))
			{
				$segments[] = $query['p'];
				unset($query['p']);
			}
			break;
	}

	return $segments;
}

/**
 * interprets url segments as set in the TracksBuildRoute function
 *
 * @param array $segments
 *
 * @return array
 */
function TracksParseRoute($segments)
{
	$vars = array();

	switch ($segments[0])
	{
		case 'project':
			$vars['view'] = 'project';
			$id = explode(':', $segments[1]);
			$vars['id'] = (int) $id[0];
			break;

		case 'ranking':
			$vars['view'] = 'ranking';
			$id = explode(':', $segments[1]);
			$vars['id'] = (int) $id[0];
			break;

		case 'teamranking':
			$vars['view'] = 'teamranking';
			$id = explode(':', $segments[1]);
			$vars['id'] = (int) $id[0];
			break;

		case 'roundresult':
			$vars['view'] = 'roundresult';
			$id = explode(':', $segments[1]);
			$vars['id'] = (int) $id[0];
			break;

		case 'individual':
			$vars['view'] = 'individual';
			if (count($segments) > 1)
			{
				$id = explode(':', $segments[1]);
				$vars['id'] = (int) $id[0];
			}
			if (count($segments) > 2)
			{
				$vars['p'] = (int) $segments[2];
			}
			break;

		case 'team':
			$vars['view'] = 'team';
			$id = explode(':', $segments[1]);
			$vars['id'] = (int) $id[0];
			if (count($segments) > 2)
			{
				$vars['p'] = (int) $segments[2];
			}
			break;

		case 'round':
			$vars['view'] = 'round';
			$id = explode(':', $segments[1]);
			$vars['id'] = (int) $id[0];
			if (count($segments) > 2)
			{
				$vars['p'] = (int) $segments[2];
			}
			break;

		default:
			$vars['view'] = $segments[0];
	}

	return $vars;
}
