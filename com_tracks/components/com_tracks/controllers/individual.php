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
class TracksFrontControllerIndividual extends JController
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
    if ( !$user->get('id') ) {
      JError::raiseError( 403, JText::_('COM_TRACKS_Access_Forbidden') );
      return;
    }
    
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

    $this->setRedirect( JRoute::_(TracksHelperRoute::getIndividualRoute($model->getId())) , $msg );
  }
}
?>
