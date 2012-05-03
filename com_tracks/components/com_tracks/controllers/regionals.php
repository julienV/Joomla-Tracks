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
class TracksControllerRegionals extends JController
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

  function newregional()
  {

  	$id = JRequest::getVar('individual_id', '', 'post', 'string');
  	$year = JRequest::getVar('year', '', 'post', 'string');
  	$competition = JRequest::getVar('competition', '', 'post', 'string');
  	$class = JRequest::getVar('class', '', 'post', 'string');
  	$position = JRequest::getVar('position', '', 'post', 'string');

  	$facebook = JRequest::getVar('facebook', '', 'post', 'string');

		// Require the base controller
//		require_once (JPATH_COMPONENT_ADMINISTRATOR.DS.'tables'.DS.'regionals.php');
//  	$table = JTable::getInstance('regionals', 'table');
//  	$table->load($id);

      $db = &JFactory::getDbo();
			$db->setQuery("INSERT INTO #__tracks_regionals (individual_id, year,competition,class,position) VALUES ('$id', '$year', '$competition', '$class', '$position')");
			$db->query();

		/* POST ON FACEBOOK WALL and send me email*/
  	mail('notifications@xjetski.com',  'Regionals' ,  'http://xjetski.com/' . TracksHelperRoute::getIndividualRoute($id) . '  ' );

  if ($facebook)
    {
    require_once JPATH_ROOT.DS.'components'.DS.'com_jfbconnect'.DS.'libraries'.DS.'facebook.php';
    $jfbcLibrary = JFBConnectFacebookLibrary::getInstance();
    $post['message'] = 'Added regional results';
    $post['link'] = 'http://xjetski.com/' . TracksHelperRoute::getIndividualRoute($id) ;
    $post['name'] = "my profile on XJETSKI.com";
    $post['description'] = "showcasing world's best jetski competitors (racing,freestyle,freeride) and all competition results worldwide";
    $post['picture'] = JRequest::getVar('picture', '', 'post', 'string');

    if ($jfbcLibrary->getUserId()) // Check if there is a Facebook user logged in
       $jfbcLibrary->setFacebookMessage($post);
    }
    /* END POST ON FACEBOOK WALL */


    // Add to the pit wall
  require_once (JPATH_SITE.DS.'components'.DS.'com_tracks'.DS.'helpers'.DS.'tools.php');
  TracksHelperTools::addUpdate("added regional results",1,'',3);

  	self::display();
  }
  
	function removeregional()
	{
  	$id = $_GET['id'];
		$db = &JFactory::getDbo();
		$query = " DELETE FROM #__tracks_regionals WHERE regional_id = '" . $id . "'";
				$db->setQuery($query);
				$db->query();

		$ind = JRequest::getInt('ind');

		$this->setRedirect(TracksHelperRoute::getIndividualRoute($ind));
	}

}
?>
