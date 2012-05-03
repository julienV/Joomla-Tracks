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
class TracksModelTipsfrompro extends JModelList
{
	
	protected function getListQuery()
	{
		$db		= $this->getDbo();
		$query	= $db->getQuery(true);
		$filter_cat = JFactory::getApplication()->getParams('com_tracks')->get('category', '');
		
		$query->select('t.*');
		$query->select('i.last_name, i.first_name, i.picture, i.user_id');
		$query->select('CASE WHEN CHAR_LENGTH( i.alias ) THEN CONCAT_WS( \':\', i.id, i.alias ) ELSE i.id END AS islug');
		$query->from('#__tracks_tips AS t');
		$query->innerjoin('#__tracks_individuals AS i on i.id = t.individual_id');
		$query->order('t.time DESC');
		
		if ($filter_cat) {
			$query->where('t.category = '.$db->Quote($filter_cat));
		}
	
		return $query;
	}
	
	/**
	 * removes a tip
	 * @param int $tip
	 * @return boolean true on success
	 */
	public function removetip($tip)
	{
		$user = &JFactory::getUser();
		$db = &JFactory::getDbo();
		
		if (!$user->get('id')) {
			Jerror::raiseError(403, 'ACCESS NOT ALLOWED');
		}
		
		if (!$user->authorise('core.manage', 'com_tracks'))
		{
			$query = $db->getQuery(true);		
			$query->select('i.user_id');
			$query->from('#__tracks_tips AS t');
			$query->innerjoin('#__tracks_individuals AS i on i.id = t.individual_id');
			$query->where('t.id = '.$tip);
			$db->setQuery($query);
			$res = $db->loadResult();
			
			if (!$res == $user->get('id')) {
				Jerror::raiseError(403, 'ACCESS NOT ALLOWED');				
			}
		}
		
		$query = $db->getQuery(true);
		$query->delete('');
		$query->from('#__tracks_tips');
		$query->where('id = '.$tip);
		$db->setQuery($query);
		if (!$db->query()) {
			$this->setError($db->getErrorMsg());
			return false;
		}
		return true;		
	}
}