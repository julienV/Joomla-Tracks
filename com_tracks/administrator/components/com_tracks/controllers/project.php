<?php
/**
 * @version    $Id: project.php 94 2008-05-02 10:28:05Z julienv $
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
class TracksControllerProject extends BaseController
{

	function __construct()
	{
		parent::__construct();

		// Register Extra tasks
		$this->registerTask( 'add',  'display' );
		$this->registerTask( 'edit', 'display' );
		$this->registerTask( 'apply', 'save' );
	}


	function display() {

		switch($this->getTask())
		{
			case 'add'     :
				{
					JRequest::setVar( 'hidemainmenu', 1 );
					JRequest::setVar( 'layout', 'form'  );
					JRequest::setVar( 'view'  , 'project');
					JRequest::setVar( 'edit', false );

					// Checkout the project
					$model = $this->getModel('project');
					$model->checkout();
				} break;
			case 'edit'    :
				{
					JRequest::setVar( 'hidemainmenu', 1 );
					JRequest::setVar( 'layout', 'form'  );
					JRequest::setVar( 'view'  , 'project');
					JRequest::setVar( 'edit', true );

					// Checkout the project
					$model = $this->getModel('project');
					$model->checkout();
					/*
					 $view =& $this->getView('','html');
					 $seasonsModel  =& $this->getModel('seasons');
					 //print_r($seasonsModel);
					 $view->setModel($seasonsModel);*/
				} break;
		}
		parent::display();
	}

	/**
	 * make select project active for edition
	 *
	 */
	function select()
	{
		$mainframe = &JFactory::getApplication();
		$option = JRequest::getCmd('option');
		// get cid array from address
		$cid	= JRequest::getVar('cid', array(0), '', 'array');

		// update session value for project
		if ( $mainframe->setUserState( $option.'project', (int) $cid[0] ) ) {
			$this->setRedirect('index.php?option=com_tracks', JText::_('COM_TRACKS_Project_selected' ));
		}
		else {
			$this->setRedirect('index.php?option=com_tracks', JText::_('COM_TRACKS_Error_while_selecting_project' ), 'error');
		}
	}

	function save()
	{
		$post	= JRequest::get('post');
		$cid	= JRequest::getVar( 'cid', array(0), 'post', 'array' );
		$post['id'] = (int) $cid[0];

		$model = $this->getModel('project');

		if ($returnid = $model->store($post)) {
			$msg = JText::_('COM_TRACKS_Project_Saved' );
			$msgtype = 'message';
		} else {
			$msg = JText::_('COM_TRACKS_Error_Saving_Project' ).$model->getError();
			$msgtype = 'error';
		}

		// Check the table in so it can be edited.... we are done with it anyway
		$model->checkin();
	
    //redirect
    if ( !$returnid || $this->getTask() == 'save' ) {
      $link = 'index.php?option=com_tracks&view=projects';
    }
    else {
      $link = 'index.php?option=com_tracks&controller=project&task=edit&cid[]='.$returnid;
    }
		$this->setRedirect($link, $msg, $msgtype);
	}

	function remove()
	{
		$cid = JRequest::getVar( 'cid', array(), 'post', 'array' );
		JArrayHelper::toInteger($cid);

		if (count( $cid ) < 1) {
			JError::raiseError(500, JText::_('COM_TRACKS_Select_an_item_to_delete' ) );
		}

		$model = $this->getModel('project');

		if(!$model->delete($cid)) {
			echo "<script> alert('".$model->getError(true)."'); window.history.go(-1); </script>\n";
	  }

		$this->setRedirect( 'index.php?option=com_tracks' );
	}


	function publish()
	{
		$cid = JRequest::getVar( 'cid', array(), 'post', 'array' );
		JArrayHelper::toInteger($cid);

		if (count( $cid ) < 1) {
			JError::raiseError(500, JText::_('COM_TRACKS_Select_an_item_to_publish' ) );
		}

		$model = $this->getModel('project');
		if(!$model->publish($cid, 1)) {
			echo "<script> alert('".$model->getError(true)."'); window.history.go(-1); </script>\n";
		}

		$this->setRedirect( 'index.php?option=com_tracks' );
	}


	function unpublish()
	{
		$cid = JRequest::getVar( 'cid', array(), 'post', 'array' );
		JArrayHelper::toInteger($cid);

		if (count( $cid ) < 1) {
			JError::raiseError(500, JText::_('COM_TRACKS_Select_an_item_to_unpublish' ) );
		}

		$model = $this->getModel('project');
		if(!$model->publish($cid, 0)) {
			echo "<script> alert('".$model->getError(true)."'); window.history.go(-1); </script>\n";
		}

		$this->setRedirect( 'index.php?option=com_tracks' );
	}
	
	function finish()
	{
		$cid = JRequest::getVar( 'cid', array(), 'post', 'array' );
		JArrayHelper::toInteger($cid);
	
		if (count( $cid ) < 1) {
			JError::raiseError(500, JText::_('COM_TRACKS_Select_a_project_to_set_as_finished' ) );
		}
	
		$model = $this->getModel('project');
		if(!$model->finish($cid, 1)) {
			echo "<script> alert('".$model->getError(true)."'); window.history.go(-1); </script>\n";
		}
	
		$this->setRedirect( 'index.php?option=com_tracks' );
	}
	
	
	function unfinish()
	{
		$cid = JRequest::getVar( 'cid', array(), 'post', 'array' );
		JArrayHelper::toInteger($cid);
	
		if (count( $cid ) < 1) {
			JError::raiseError(500, JText::_('COM_TRACKS_Select_a_project_to_set_as_not_finished' ) );
		}
	
		$model = $this->getModel('project');
		if(!$model->finish($cid, 0)) {
			echo "<script> alert('".$model->getError(true)."'); window.history.go(-1); </script>\n";
		}
	
		$this->setRedirect( 'index.php?option=com_tracks' );
	}
	
	function cancel()
	{
		// Checkin the project
		$model = $this->getModel('project');
		$model->checkin();

		$this->setRedirect( 'index.php?option=com_tracks' );
	}


	function orderup()
	{
		$model = $this->getModel('project');
		$model->move(-1);

		$this->setRedirect( 'index.php?option=com_tracks');
	}

	function orderdown()
	{
		$model = $this->getModel('project');
		$model->move(1);

		$this->setRedirect( 'index.php?option=com_tracks');
	}

	function saveorder()
	{
		$cid 	= JRequest::getVar( 'cid', array(), 'post', 'array' );
		$order 	= JRequest::getVar( 'order', array(), 'post', 'array' );
		JArrayHelper::toInteger($cid);
		JArrayHelper::toInteger($order);

		$model = $this->getModel('project');
		$model->saveorder($cid, $order);

		$msg = 'New ordering saved';
		$this->setRedirect( 'index.php?option=com_tracks', $msg );
	}
	
	/**
	 * copy one or several projects
	 */
	public function copy()
	{
		$cid 	= JRequest::getVar( 'cid', array(), 'post', 'array' );
		JArrayHelper::toInteger($cid);
		
		$msgtype = '';
		$model = $this->getModel('project');
		if ($nb = $model->copy($cid)) {
			$msg = JText::sprintf('COM_TRACKS_PROJECT_COPY_SUCCESSFULL', $nb);
		}
		else {
			$msg = $model->getError();
			$msgtype = 'error';
		}
		$this->setRedirect( 'index.php?option=com_tracks', $msg, $msgtype );		
	}
}
?>
