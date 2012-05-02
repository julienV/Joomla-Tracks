<?php
/**
* @version    $Id: list.php 7 2008-01-30 10:37:37Z julienv $ 
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

jimport('joomla.application.component.model');

/**
 * Joomla Tracks Component Admin Model
 *
 * @author Julien Vonthron <julien.vonthron@gmail.com>
 * @package   Tracks
 * @since 2.5
 */
class TracksModelMenu extends JModel
{
	protected $_p, $_s, $_c;
	
	protected $_data = null;
		
	public function __construct($config = array())
	{
		parent::__construct($config);
		
		$app = JFactory::getApplication();
		$option = JRequest::getCmd('option');
		$this->setState('project', $app->getUserState($option.'project', 0));
		$this->setState('season', $app->getUserState($option.'season', 0));
		$this->setState('competition', $app->getUserState($option.'competition', 0));
	}
	
	/**
	 * return project data
	 * 
	 * @return object
	 */
	public function getData()
	{
		if (!$this->_data)
		{
			$db = &JFactory::getDbo();
			$query = $db->getQuery(true);
			
			$query->select('p.id, p.name');
			$query->from('#__tracks_projects AS p');
			$query->where('p.id = '.$this->getState('project'));
			$db->setQuery($query);
			$this->_data = $db->loadObject();
		}
		return $this->_data;
	}
	
	/**
	 * return projects as options
	 * 
	 * @return array
	 */
	public function getProjectsOptions()
	{
		$db = &JFactory::getDbo();
		$query = $db->getQuery(true);
		
		$query->select('p.id AS value, p.name AS text');
		$query->from('#__tracks_projects AS p');
		$query->order('p.name');
		$db->setQuery($query);
		$res = $db->loadObjectList();
		
		return $res;
	}
}