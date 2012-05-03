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
class TracksFrontControllerConnections extends JController
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

  function addconnection()
  {

  	$id1 = JRequest::getVar('id1', '', 'post', 'string');
  	$connection_id = JRequest::getVar('connection_id', '', 'post', 'string');
  	$id2 = JRequest::getVar('id2', '', 'post', 'string');

  	$facebook = JRequest::getVar('facebook', '', 'post', 'string');
  	
      $db = &JFactory::getDbo();
			$db->setQuery("INSERT INTO #__tracks_connections (id1, connection_id , id2) VALUES ('$id1', '$connection_id' , '$id2')");
			$db->query();

		/* POST ON FACEBOOK WALL and send me email*/
	mail('notifications@xjetski.com',  'Connection' , 'http://xjetski.com/' . TracksHelperRoute::getIndividualRoute($id1)  );

  if ($facebook)
    {
    require_once JPATH_ROOT.DS.'components'.DS.'com_jfbconnect'.DS.'libraries'.DS.'facebook.php';
    $jfbcLibrary = JFBConnectFacebookLibrary::getInstance();
    $post['message'] = 'Added connection to ' . JRequest::getVar('id2name', '', 'post', 'string') ;
    $post['link'] = 'http://xjetski.com/' . TracksHelperRoute::getIndividualRoute($id1) ;
    $post['name'] = "see it on my profile on XJETSKI.com";
    $post['description'] = "showcasing world's best jetski competitors (racing,freestyle,freeride) and all competition results worldwide";
    $post['picture'] = JRequest::getVar('picture', '', 'post', 'string');

    if ($jfbcLibrary->getUserId()) // Check if there is a Facebook user logged in
       $jfbcLibrary->setFacebookMessage($post);
    }
    /* END POST ON FACEBOOK WALL */
    
    // Add to the pit wall
    require_once (JPATH_SITE.DS.'components'.DS.'com_tracks'.DS.'helpers'.DS.'tools.php');
    TracksHelperTools::addUpdate("created Rider's connection",1,'',17);

  	self::display();
  }
  
  function deleteconnection()
  {

  	$id = JRequest::getVar('id', '', 'post', 'string');

      $db = &JFactory::getDbo();
			$db->setQuery("DELETE FROM #__tracks_connections WHERE id = '" . $id . "'");
			$db->query();

  	self::display();
  }
  

}
?>
