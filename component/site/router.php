<?php
/**
 * @package    Tracks.Site
 * @copyright  Tracks (C) 2008-2015 Julien Vonthron. All rights reserved.
 * @license    GNU General Public License version 2 or later
 */

/**
 * transform the url passed in JRoute function for SEF display
 *
 * @param   url  &$query  query
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
			if (isset($query['p']))
			{
				$segments[] = $query['p'];
				unset($query['p']);
			}

			break;

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
 * @param   array  $segments  segments
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
			$vars['p'] = (int) $id[0];
			break;

		case 'ranking':
			$vars['view'] = 'ranking';
			$id = explode(':', $segments[1]);
			$vars['p'] = (int) $id[0];
			break;

		case 'teamranking':
			$vars['view'] = 'teamranking';
			$id = explode(':', $segments[1]);
			$vars['p'] = (int) $id[0];
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
