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
class TracksControllerIndividual extends JController
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
	function edit()
	{
    $user   =& JFactory::getUser();
    if (!$user->get('id')) {
      $this->setRedirect(JURI::base(), JText::_('COM_TRACKS_Please_login_to_be_able_to_edit_your_tracks_profile'), 'error' );
      $this->redirect();
    }
    
		JRequest::setVar( 'view', 'individual' );
		JRequest::setVar( 'layout', 'form'  );
		JRequest::setVar( 'hidemainmenu', 1);
		
		parent::display();
	}
	

  function save()
  {
    // Check for request forgeries
    JRequest::checkToken() or jexit( 'Invalid Token' );

    $user  =& JFactory::getUser();
    $id = JRequest::getVar( 'i', 0, 'post', 'int' );

    // perform security checks
//     if ( !$user->get('id') ) {
//       JError::raiseError( 403, JText::_('COM_TRACKS_Access_Forbidden') );
//       return;
//     }
    
    // only autorize users can specify the user_id.
    if (!$user->authorise('core.manage', 'com_tracks')) {
    	$user_id = $user->get('id');
    }
    else {
    	$user_id = JRequest::getVar( 'user_id', 0, 'post', 'int' );    	
    }
    
    //clean request
    // first, save description...
    $post = JRequest::get( 'post' );
    
    if (!isset($post['has_other_sponsor'])) {
    	$post['sponsor_other'] = '';
    }
    
    $post['id'] = $id;
    $post['user_id'] = $user_id;
    $post['full_name'] = JRequest::getVar('full_name', '', 'post', 'string');
    $post['last_name'] = JRequest::getVar('last_name', '', 'post', 'string');
    $post['country_code'] = JRequest::getVar('country_code', '', 'post', 'string');
    $post['dob'] = JRequest::getVar('dob', '', 'post', 'string');
    $post['address'] = JRequest::getVar('address', '', 'post', 'string');
    $post['postcode'] = JRequest::getVar('postcode', '', 'post', 'string');
    $post['city'] = JRequest::getVar('city', '', 'post', 'string');
    $post['state'] = JRequest::getVar('state', '', 'post', 'string');
    $post['country'] = JRequest::getVar('country', '', 'post', 'string');
    $post['description'] = JRequest::getVar('description', '', 'post', 'string', JREQUEST_ALLOWRAW);
        
    $picture           = JRequest::getVar( 'picture', '', 'files', 'array' );
    $picture_small     = JRequest::getVar( 'picture_small', '', 'files', 'array' );
    $picture_background     = JRequest::getVar( 'picture_background', '', 'files', 'array' );
    
    // store data
    $model = $this->getModel('individual');

    if ($model->store($post, $picture, $picture_small, $picture_background)) {
      $msg  = JText::_('COM_TRACKS_Profile_has_been_saved' );
    } else {
      $msg  = $model->getError();
    }

    // Add to the pit wall
    require_once (JPATH_SITE.DS.'components'.DS.'com_tracks'.DS.'helpers'.DS.'tools.php');
    TracksHelperTools::addUpdate("Updated profile", 1 , TracksHelperRoute::getIndividualRoute($model->getId()), 10 );
    // add vanity URL
    $vanityname = str_replace(" ", "", $post['first_name'] . $post['last_name'] );
    TracksHelperTools::addVanityURL( $vanityname , $id);

// AP originally save was returning to individual's profile
//    $this->setRedirect( JRoute::_(TracksHelperRoute::getIndividualRoute($model->getId())) , $msg );
    $this->setRedirect(JRoute::_( TracksHelperRoute::getEditIndividualRoute($model->getId()).'&task=edit', $msg ));
  }
  
  function savecountry()
  {
  	$id = JRequest::getVar('id', '', 'post', 'string');
  	$country = JRequest::getVar('countryupdate', '', 'post', 'string');

		mail('notifications@xjetski.com',  'id='.$id.' country='.$country, 'user=' . JFactory::getUser()->name );

		// Require the base controller
		require_once (JPATH_COMPONENT_ADMINISTRATOR.DS.'tables'.DS.'individual.php');
  	$table = JTable::getInstance('individual', 'table');
  	$table->load($id);
  	$table->country_code = $country;
  	$table->store();
  	self::display();
  }
  
  public function delpic()
  {
  	$user = &JFactory::getUser();
  	if (!$user->get('id')) {
  		Jerror::raiseError(403, 'not allowed');
  	}
  	$id = JRequest::getInt('i');
  	if (!$id) {
  		JError::raiseError(0, 'id is 0');
  		return false;
  	}
  	$model = $this->getModel('individual');
  	$model->delpic();
  	$this->setRedirect( JRoute::_(TracksHelperRoute::getEditIndividualRoute($id)) , $msg );
  }
  
  public function delpicsmall()
  {
  	$user = &JFactory::getUser();
  	if (!$user->get('id')) {
//  		Jerror::raiseError(403, 'not allowed');
  	}
  	$id = JRequest::getInt('i');
  	if (!$id) {
  		JError::raiseError(0, 'id is 0');
  		return false;
  	}
  	$model = $this->getModel('individual');
  	$model->delpic('small');
  	$this->setRedirect( JRoute::_(TracksHelperRoute::getEditIndividualRoute($id)) , $msg );
  }
  
  public function delpicback()
  {
  	$user = &JFactory::getUser();
  	if (!$user->get('id')) {
  		Jerror::raiseError(403, 'not allowed');
  	}
  	$id = JRequest::getInt('i');
  	if (!$id) {
  		JError::raiseError(0, 'id is 0');
  		return false;
  	}
  	$model = $this->getModel('individual');
  	$model->delpic('small');
  	$this->setRedirect( JRoute::_(TracksHelperRoute::getEditIndividualRoute($id)) , $msg );
  }
  /**
   * add a tip to tips table
   */
  public function newtip()
  {
  	$category      = JRequest::getVar('category', null, 'post', 'word');
  	$tip           = JRequest::getVar('tip', null, 'post', 'string');
  	$individual_id = Jrequest::getInt('id');

  	$msg = '';
  	$msgtype = 'message'; 
  	if ($category && $tip && $individual_id) 
  	{  	
	  	$model = $this->getModel('individual');
	  	if (!$model->storeTip($individual_id, $category, $tip)) {
	  		$msg = $model->getError();
	  		$msgtype = 'error';
	  	}
	  	else {
	  		$msg = JText::_('COM_TRACKS_TIPS_TIP_SAVED');
	  		// Add to the pit wall
        TracksHelperTools::addUpdate("added tip",1 , '', 12);
	  	}
  	}
 		$this->setRedirect(JRoute::_(TracksHelperRoute::getIndividualRoute($individual_id)), $msg, $msgtype);
  }
  
	public function deltip()
	{
		$model = $this->getModel('Tipsfrompro');
		$tip = JREquest::getInt('id');
		$msg = '';
		$msgtype = 'message';
		if ($model->removetip($tip)) {
			$msg = JText::_('COM_TRACKS_TIPSFROMPRO_TIP_REMOVED');
		}
		else {
			$msg = $model->getError();
			$msgtype = 'error';
		}
		$this->setRedirect(JRoute::_(TracksHelperRoute::getTipsRoute()), $msg, $msgtype);
	}  
}
