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

require_once (JPATH_SITE.DS.'components'.DS.'com_tracks'.DS.'helpers'.DS.'tools.php');

jimport('joomla.application.component.controller');

/**
 * Joomla Tracks Component Controller
 *
 * @package		Tracks
 * @since 0.1
 */
class TracksControllerIndividual extends BaseController
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
		JRequest::setVar( 'view', 'individual' );
		JRequest::setVar( 'layout', 'form'  );
		JRequest::setVar( 'hidemainmenu', 1);

		parent::display();
	}
	
  function save()
	{
		$post	= JRequest::get('post');
		$cid	= JRequest::getVar( 'cid', array(0), 'post', 'array' );
		$post['id'] = (int) $cid[0];
		// data from editor must be retrieved as raw
    $post['description'] = JRequest::getVar('description', '', 'post', 'string', JREQUEST_ALLOWRAW);

    if (!isset($post['has_other_sponsor'])) {
    	$post['sponsor_other'] = '';
    }
    
		$model = $this->getModel('individual');

		if ($returnid = $model->store($post)) {
			$msg = JText::_('COM_TRACKS_Individual_Saved' );
		} else {
			$msg = JText::_('COM_TRACKS_Error_Saving_Individual' ).$model->getError();
		}

		// Check the table in so it can be edited.... we are done with it anyway
		$model->checkin();
		 
    //redirect
    if ( !$returnid || $this->getTask() == 'save' ) {
      $link = 'index.php?option=com_tracks&view=individuals';
    }
    else {
      $link = 'index.php?option=com_tracks&controller=individual&task=edit&cid[]='.$returnid;
    }
    
    if (JRequest::getInt('emailuser'))
    {
    	$mailer = JFactory::getMailer();
    	$mailer->isHtml(true);
    	 
    	$model->setId($returnid);
    	$ind = $model->getData();
    	 
    	$user = JFactory::getUser($ind->user_id);
    	 
    	$mailer->addRecipient($user->email);
    	$mailer->setSubject('Welcome to xjetski');
    	$mailer->setBody(sprintf('A profile was created for you on xjetski, you can visit it here: %s',
    			JHTML::link(JURI::root().TracksHelperRoute::getIndividualRoute($returnid), $ind->first_name.' '.$ind->last_name)));
    	if ($mailer->send()) {
    		$msg .= '<br/>'.Jtext::_('COM_TRACKS_XJ_WELCOME_EMAIL_SENT');
    	}
    }
    
    // Add to the pit wall
    TracksHelperTools::addUpdate("New rider added" , 0 , '' , 1);
    // add fancy domain name
//    TracksHelperTools::addVanityURL( $post['first_name'] . $post['last_name'] , $returnid);

		$this->setRedirect($link, $msg);
	}

	/**
	 * remove record(s)
	 * @return void
	 */
	function remove()
	{
		$model = $this->getModel('individual');
		if(!$model->delete()) {
			$msg = JText::_('COM_TRACKS_Error_One_or_More_Individuals_Could_not_be_Deleted: ' . $model->getError());
		} else {
			$msg = JText::_( 'COM_TRACKS_Individuals_Deleted' );
		}

		$this->setRedirect( 'index.php?option=com_tracks&view=individuals', $msg );
	}

	/**
	 * cancel editing a record
	 * @return void
	 */
	function cancel()
	{
    // Checkin the project
    $model = $this->getModel('individual');
    $model->checkin();
    
		$msg = JText::_('COM_TRACKS_Operation_Cancelled' );
		$this->setRedirect( 'index.php?option=com_tracks&view=individuals', $msg );
	}
	
	
	/**
	 * individual element
	 */
	function element()
	{
		$model  = &$this->getModel( 'individuals' );
		$view = &$this->getView( 'individuals', 'html' );
		$view->setLayout( 'element' );
		$view->setModel( $model, true );
		$view->display();
	}
}
