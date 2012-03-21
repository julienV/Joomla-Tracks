<?php
/**
* @version    $Id: individual.php 140 2008-06-10 16:47:22Z julienv $ 
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

jimport('joomla.application.component.controller');
jimport('joomla.filesystem.folder');

/**
 * Joomla Tracks Component Controller
 *
 * @package		Tracks
 * @since 0.1
 */
class TracksFrontControllerAddride extends JController
{	
	public function add()
	{
		JRequest::setVar('view', 'addride');
		$this->display();
	}
	
	public function submit()
	{
		$params = JComponentHelper::getParams('com_tracks');
		$result_id = JRequest::getInt('rid');
		$id = JRequest::getInt('id');
		
		$maxwidth = 960;
		
		$file	= JRequest::getVar( 'file', '', 'files', 'array' );
		if (!$file) {
			echo Jtext::_('COM_TRACKS_RIDE_NO_IMAGE');
			return false;
		}
		
		$size = getimagesize($file['tmp_name']);
		if (!$size) {
			echo Jtext::_('COM_TRACKS_RIDE_NOT_AN_IMAGE');
			return false;
		}
		
		$model = $this->getModel('addride');
		if (!$model->store($file, $result_id)) {
			echo $model->getError();
			return false;
		}
		
		// saved ok, load the js to close the window
		echo JText::_('COM_TRACKS_RIDE_SAVED');
		$document = JFactory::getDocument();
		$document->addScript('components/com_tracks/assets/js/closeride.js');
	}
}
