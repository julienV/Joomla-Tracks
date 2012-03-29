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

jimport('joomla.application.component.model');

/**
 * Joomla Tracks Component Front add ride Model
 *
 * @package		Tracks
 * @since 0.1
 */
class TracksFrontModelAddride extends JModel
{
	protected $_result_id;
	
	public function __construct($config = array())
	{
		parent::__construct($config);
		$this->_result_id = JRequest::getInt('r');
	}
	
	public function getItem()
	{
		if (!$this->_result_id) {
			throw new Exception('result id required');
		}
		$db = &JFactory::getDbo();
		$query = $db->getQuery(true);
		
		$query->select('*');
		$query->from('#__tracks_result_ride');
		$query->where('result_id = '.intval($this->_result_id));
		$db->setQuery($query);
		$res = $db->loadObject();
		return $res;
	}
	
	public function store($file, $result_id)
	{
		$params = JComponentHelper::getParams('com_tracks');
		
		$size = getimagesize($file['tmp_name']);
		if (!$size) {
			echo Jtext::_('COM_TRACKS_RIDE_NOT_AN_IMAGE');
		}
		
		// remove previous file and record
		$this->remove($result_id);
		
		$targetpath = 'images'.DS.$params->get('default_individual_images_folder', 'tracks/individuals').DS.'rides';
		if (!file_exists($targetpath)) {
			JFolder::create($targetpath);
		}
		$ext = pathinfo($file['name']);
		$final_name = $ext['filename'].'_'.uniqid().'.'.$ext['extension'];
		$final_path = $targetpath.DS.$final_name;
		
		$maxwidth = 960;
		
		if ($size[0] > $maxwidth) // width
		{
			// resize
			$height = ceil($maxwidth * $size[1] / $size[0]);
			JLVImageTool::thumb($file['tmp_name'], JPATH_SITE.DS.$final_path, $maxwidth, $height);
		}
		else
		{
			copy($file['tmp_name'], $final_path);
		}
		
		$query = ' INSERT INTO #__tracks_result_ride '
		       . '    SET result_id = ' . $result_id
		       . '      , file = ' . $this->_db->Quote($final_path);
		$this->_db->setQuery($query);
		if (!$res = $this->_db->query()) {
			$this->setError($this->_db->getErrorMsg());
			return false;
		}
		
		return true;
	}
	
	/**
	 * removes a ride
	 * @param unknown_type $result_id
	 */
	public function remove($result_id)
	{
		$user = JFactory::getUser();
		
		$db = &JFactory::getDbo();
		$query = $db->getQuery(true);
		
		$query->select('r.*');
		$query->select('i.user_id');
		$query->from('#__tracks_result_ride AS r');
		$query->innerjoin('#__tracks_rounds_results AS rr ON rr.id = r.result_id');
		$query->innerjoin('#__tracks_individuals AS i ON rr.individual_id = i.id');
		$query->where('r.result_id = ' . $result_id);
		$db->setQuery($query);
		$res = $db->loadObject();
		
		if (!$res) {
			$this->setError(Jtext::_('COM_TRACKS_RIDE_NOT_FOUND'));
			return false;
		}
		
		if (!$user->authorise('core.manage', 'com_tracks') && !($user->get('id') == $res->user_id)) {
			Jerror::raiseError(403, 'ACCESS NOT ALLOWED');
		}
			
		unlink(JPATH_SITE.DS.$res->file);
		$query = ' DELETE FROM #__tracks_result_ride WHERE result_id = ' . $this->_db->Quote($result_id);
		$this->_db->setQuery($query);
		if (!$res = $this->_db->query()) {
			$this->setError($this->_db->getErrorMsg());
			return false;
		}
		
		return true;		
	}
}