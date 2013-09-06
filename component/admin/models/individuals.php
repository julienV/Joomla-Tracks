<?php
/**
 * @version    .2 $Id$
 * @package    JoomlaTracks
 * @copyright	Copyright (C) 2008 Julien Vonthron. All rights reserved.
 * @license		GNU/GPL, see LICENSE.php
 * Joomla Tracks is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * See COPYRIGHT.php for copyright notices and details.
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();

class TracksModelIndividuals extends FOFModel
{
	/**
	 * Builds the SELECT query
	 *
	 * @param   boolean $overrideLimits  Are we requested to override the set limits?
	 *
	 * @return  JDatabaseQuery
	 */
	public function buildQuery($overrideLimits = false)
	{
		$db = JFactory::getDbo();
		$query = parent::buildQuery($overrideLimits);

		$filter = $this->getState('search');
		if ($filter)
		{
			$query->where('LOWER( CONCAT(first_name, last_name) ) LIKE ' . $db->quote('%' . $filter . '%'));
		}

		return $query;
	}
}
