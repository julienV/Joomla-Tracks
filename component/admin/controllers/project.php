<?php
/**
 * @version    $Id: project.php 94 2008-05-02 10:28:05Z julienv $
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

/**
 * Joomla Tracks project Controller
 *
 * @package  Tracks
 * @since    0.1
 */
class TracksControllerProject extends FOFController
{
	/**
	 * make select project active for edition
	 *
	 * @return void
	 */
	function select()
	{
		$app = JFactory::getApplication();
		$option = $app->input->getCmd('option', 'com_tracks');

		// get cid array from address
		$id	= $app->input->getInt('id', 0);

		// update session value for project
		if ($app->setUserState($option . 'project', $id))
		{
			$this->setRedirect('index.php?option=com_tracks', JText::_('COM_TRACKS_Project_selected'));
		}
		else
		{
			$this->setRedirect('index.php?option=com_tracks', JText::_('COM_TRACKS_Error_while_selecting_project'), 'error');
		}
	}
}
