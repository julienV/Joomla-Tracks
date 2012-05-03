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

/**
 * Joomla Tracks Component Controller
 *
 * @package		Tracks
 * @since 0.1
 */
class TracksControllerregional_requests extends JController
{
  
  function __construct()
	{
		parent::__construct();

		// Register Extra tasks
		$this->registerTask( 'add',  'edit' );
		$this->registerTask( 'apply', 'save' );
	}
	
	/**
	 * display the edit form
	 * @return void
	 */

  function addrequest()
  {

  	$from_id = JRequest::getVar('from', '', 'post', 'string');
  	$from_name = JRequest::getVar('from_name', '', 'post', 'string');
  	$to_id = JRequest::getVar('to', '', 'post', 'string');

      $db = &JFactory::getDbo();
			$db->setQuery("INSERT INTO #__tracks_regional_requests (from_id, from_name, to_id) VALUES ('$from_id', '$from_name' , '$to_id')");
			$db->query();

	mail('notifications@xjetski.com',  'ID confirmation' , $from_id . '-' . $from_name  );

    // Add to the pit wall
//    require_once (JPATH_SITE.DS.'components'.DS.'com_tracks'.DS.'helpers'.DS.'tools.php');
//    TracksHelperTools::addUpdate("created Rider's connection",1,'',17);

  	self::display();
  }
  
  function deleterequest()
  {

  	$id = JRequest::getVar('id', '', 'post', 'string');

      $db = &JFactory::getDbo();
			$db->setQuery("DELETE FROM #__tracks_regional_requests WHERE id = '" . $id . "'");
			$db->query();

  	self::display();
  }
  

  function approvedecline()
  {

  	$id = JRequest::getVar('id', '', 'post', 'string');
  	$approvedecline = JRequest::getVar('approve', '', 'post', 'string');

      $db = &JFactory::getDbo();
			$db->setQuery("UPDATE #__tracks_regional_requests SET response = '" . $approvedecline . "' WHERE id = '" . $id . "'");
			$db->query();

	mail('notifications@xjetski.com',  'ID confirmation' , $id  );

  	self::display();
  }

}
?>
