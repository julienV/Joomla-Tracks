<?php
/**
* @version    0.2 $Id$
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
 * HTML View class for the Tracks component
 *
 * @static
 * @package  Tracks
 * @since    0.1
 */
class TracksViewSubrounds extends FOFViewHtml
{
	protected function preRender()
	{
		parent::preRender();

		$input = array(
			'task' => 'read',
			'layout' => 'start'
		);
		FOFDispatcher::getTmpInstance('com_tracks', 'menu', array('input' => $input))->dispatch();
	}

	/**
	 * Runs after rendering the view template, echoing HTML to put after the
	 * view template's generated HTML
	 *
	 * @return  void
	 */
	protected function postRender()
	{
		parent::postRender();

		$input = array(
			'task' => 'read',
			'layout' => 'end'
		);
		FOFDispatcher::getTmpInstance('com_tracks', 'menu', array('input' => $input))->dispatch();
	}
}
