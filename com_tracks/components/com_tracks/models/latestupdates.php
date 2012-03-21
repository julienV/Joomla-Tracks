<?php
/**
* @version    $Id: base.php 54 2008-04-22 14:50:30Z julienv $ 
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

jimport('joomla.application.component.modellist');

/**
 * Joomla Tracks Component Front Latest Updates Model
 *
 * @package		Tracks
 * @since 0.1
 */
class TracksFrontModelLatestUpdates extends JModelList
{
	
	protected function getListQuery()
	{
		$db		= $this->getDbo();
		$query	= $db->getQuery(true);
		
		$query->select('lu.*');
		$query->select('i.last_name, i.first_name, i.picture');
		$query->from('#__tracks_latest_update AS lu');
		$query->innerjoin('#__tracks_individuals AS i on i.id = lu.individual_id');
		$query->order('lu.time DESC');
	
		return $query;
	}
}