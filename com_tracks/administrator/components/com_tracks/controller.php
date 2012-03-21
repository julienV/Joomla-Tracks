<?php
/**
* @version    $Id: controller.php 133 2008-06-08 10:24:29Z julienv $ 
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

/**
 * Tracks Component Controller
 *
 * @package		Tracks
 * @since 0.1
 */
class TracksController extends BaseController
{
  function display()
  {
		parent::display();
		return $this;
	}
	
	public function ctlast()
	{
		$db = JFactory::getDbo();
		$query = ' CREATE TABLE IF NOT EXISTS `#__tracks_latest_update` (
		  `id` int(11) NOT NULL AUTO_INCREMENT,
		  `individual_id` int(11) NOT NULL,
		  `text` text NOT NULL,
		  `time` datetime NOT NULL,
		  PRIMARY KEY (`id`)
		) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ; ';
		$db->setQuery($query);
		$res = $db->query();
	}
	
	public function ctride()
	{
		$db = JFactory::getDbo();
		$query = ' CREATE TABLE IF NOT EXISTS `#__tracks_result_ride` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `result_id` int(11) NOT NULL,
  `file` varchar(255) NOT NULL,
  `comment` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `result_id` (`result_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ; ';
		$db->setQuery($query);
		$res = $db->query();
	}
}
