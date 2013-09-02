<?php
/**
* @version    $Id: projects.php 110 2008-05-26 16:47:13Z julienv $
* @package    JoomlaTracks
* @copyright  Copyright (C) 2008 Julien Vonthron. All rights reserved.
* @license    GNU/GPL, see LICENSE.php
* Joomla Tracks is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();

jimport('joomla.application.component.model');

/**
 * Joomla Tracks Component Projects Model
 *
 * @package  Tracks
 * @since    0.1
 */
class TracksModelProjects extends FOFModel
{
	/**
	 * Builds the SELECT query
	 *
	 * @param   boolean  $overrideLimits  Are we requested to override the set limits?
	 *
	 * @return  JDatabaseQuery
	 */
	public function buildQuery($overrideLimits = false)
	{
		$table = $this->getTable();
		$db = $this->getDbo();

		$query = $db->getQuery(true);

		$query->select('p.*');
		$query->from('#__tracks_projects AS p');

		$query->select('c.name as competition_name');
		$query->join('left', '#__tracks_competitions AS c on c.id = p.competition_id');

		$query->select('s.name as season_name');
		$query->join('left', '#__tracks_seasons AS s on s.id = p.season_id');

		if (!$overrideLimits)
		{
			$order = $this->getState('filter_order', null, 'cmd');

			if (!in_array($order, array_keys($table->getData())))
			{
				$order = 'id';
			}

			$order = $db->qn($order);

			$dir = $this->getState('filter_order_Dir', 'ASC', 'cmd');
			$query->order($order . ' ' . $dir);

			$filter = $this->getState('competition_name');
			if ($filter)
			{
				$query->where('c.name LIKE (' . $db->quote('%' . $filter . '%')  . ')');
			}

			$filter = $this->getState('season_name');
			if ($filter)
			{
				$query->where('s.name LIKE (' . $db->quote('%' . $filter . '%')  . ')');
			}

			$filter = $this->getState('published', '');
			if ($filter != '')
			{
				$query->where('p.published = ' . $db->quote($filter));
			}
		}

		return $query;
	}
}
