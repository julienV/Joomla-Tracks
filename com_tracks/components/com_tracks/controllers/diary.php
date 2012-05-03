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
class TracksControllerDiary extends JController
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

  function savediary()
  {

  	$id = JRequest::getVar('id', '', 'post', 'string');
  	$diary = JRequest::getVar('diaryupdate', '', 'post', 'string');

  	$facebook = JRequest::getVar('facebook', '', 'post', 'string');
  	
		// Require the base controller
		require_once (JPATH_COMPONENT_ADMINISTRATOR.DS.'tables'.DS.'diary.php');
  	$table = JTable::getInstance('diary', 'table');
  	$table->load($id);


if (!$table->id)
{
      $db = &JFactory::getDbo();
			$db->setQuery("INSERT INTO #__tracks_diary (id, diary_text) VALUES ('$id', '$diary')");
			$db->query();
}
else
{
  	$table->diary_text = $diary;
  	$table->store();
}

		/* POST ON FACEBOOK WALL and send me email*/
	mail('notifications@xjetski.com',  'Diary' , $diary.$table->diary_text  );

  if ($facebook)
    {
    require_once JPATH_ROOT.DS.'components'.DS.'com_jfbconnect'.DS.'libraries'.DS.'facebook.php';
    $jfbcLibrary = JFBConnectFacebookLibrary::getInstance();
    $post['message'] = 'Added more info about my competition';
    $post['link'] = 'http://xjetski.com/';
    $post['name'] = "my profile on XJETSKI.com";
    $post['description'] = "showcasing world's best jetski competitors (racing,freestyle,freeride) and all competition results worldwide";
    $post['picture'] = JRequest::getVar('picture', '', 'post', 'string');

    if ($jfbcLibrary->getUserId()) // Check if there is a Facebook user logged in
       $jfbcLibrary->setFacebookMessage($post);
    }
    /* END POST ON FACEBOOK WALL */

    // Add to the pit wall
    require_once (JPATH_SITE.DS.'components'.DS.'com_tracks'.DS.'helpers'.DS.'tools.php');
    TracksHelperTools::addUpdate("added Rider's notes",1,'',13);

  	self::display();
  }

}
?>
