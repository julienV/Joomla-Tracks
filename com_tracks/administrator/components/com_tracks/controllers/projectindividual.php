<?php
/**
* @version    $Id: projectindividual.php 140 2008-06-10 16:47:22Z julienv $ 
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
class TracksControllerProjectindividual extends BaseController
{
  
  function __construct()
	{
		parent::__construct();

		// Register Extra tasks
		$this->registerTask( 'add',  'edit' );
		$this->registerTask( 'apply', 'save' );
	}
  
  
	function display() 
	{
		parent::display();
	}
	
	function edit()
	{
		JRequest::setVar( 'hidemainmenu', 1 );
		JRequest::setVar( 'layout', 'form'  );
		JRequest::setVar( 'view'  , 'projectindividual');
		JRequest::setVar( 'edit', $this->getTask() == 'edit' );

		// Checkout the project
		$model = $this->getModel('projectindividual');
		$model->checkout();
    parent::display();
	}
	
  function save()
	{
		$post	= JRequest::get('post');
		$cid	= JRequest::getVar( 'cid', array(0), 'post', 'array' );
		$post['id'] = (int) $cid[0];

		$model = $this->getModel('projectindividual');

		if ($returnid = $model->store($post)) {
			$msg = JText::_('COM_TRACKS_Participant_Saved' );
		} else {
			$msg = JText::_('COM_TRACKS_Error_Saving_Project_Round' ).$model->getError();
		}

		// Check the table in so it can be edited.... we are done with it anyway
		$model->checkin();
		  
		//redirect
    if ( !$returnid || $this->getTask() == 'save' ) {
      $link = 'index.php?option=com_tracks&view=projectindividuals';
    }
    else {
      $link = 'index.php?option=com_tracks&controller=projectindividual&task=edit&cid[]='.$returnid;
    }
		$this->setRedirect($link, $msg);
	}

  function saveassign()
  {   
    $cid = JRequest::getVar( 'cid', array(), 'post', 'array' );
    $team_id = JRequest::getVar( 'team_id', array(), 'post', 'array' );
    $numbers = JRequest::getVar( 'number', array(), 'post', 'array' );
    $project_id = JRequest::getVar( 'project_id', 0, 'post', 'int' );
    JArrayHelper::toInteger($cid);
    JArrayHelper::toInteger($team_id);
    
    if (count( $cid ) < 1) {
      JError::raiseError(500, JText::_('COM_TRACKS_Select_an_individual_to_assign' ) );
    }
    
    $rows = array();
    foreach ($cid as $k => $id) 
    {
    	$row = new stdclass();
    	$row->individual_id = $cid[$k];
      $row->team_id = $team_id[$k];
      $row->number = $numbers[$k];
      $row->project_id = $project_id;
      $rows[] = $row;
    }
    $msg='';
    $model = $this->getModel('projectindividual');
    if(!$model->assign($rows)) {
      $msg = $model->getError(true);
    }
    $link = 'index.php?option=com_tracks&view=projectindividuals';
    $this->setRedirect($link, $msg);
  }
  
	function remove()
	{
	  $cid = JRequest::getVar( 'cid', array(), 'post', 'array' );
		JArrayHelper::toInteger($cid);

		if (count( $cid ) < 1) {
			JError::raiseError(500, JText::_('COM_TRACKS_Select_an_item_to_delete' ) );
		}

		$model = $this->getModel('projectindividual');
		
		if(!$count = $model->delete($cid)) {
			echo "<script> alert('".$model->getError(true)."'); window.history.go(-1); </script>\n";
		}
		$msg = $count . ' ' . JText::_('COM_TRACKS_individuals_removed_from_project');

		$this->setRedirect( 'index.php?option=com_tracks&view=projectindividuals', $msg);
	}


	function publish()
	{		
	  $cid = JRequest::getVar( 'cid', array(), 'post', 'array' );
		JArrayHelper::toInteger($cid);

		if (count( $cid ) < 1) {
			JError::raiseError(500, JText::_('COM_TRACKS_Select_an_item_to_publish' ) );
		}

		$model = $this->getModel('projectindividual');
		if(!$model->publish($cid, 1)) {
			echo "<script> alert('".$model->getError(true)."'); window.history.go(-1); </script>\n";
		}
    $link = 'index.php?option=com_tracks&view=projectindividuals';
		$this->setRedirect($link);
	}


	function unpublish()
	{
		$cid = JRequest::getVar( 'cid', array(), 'post', 'array' );
		JArrayHelper::toInteger($cid);

		if (count( $cid ) < 1) {
			JError::raiseError(500, JText::_('COM_TRACKS_Select_an_item_to_unpublish' ) );
		}

		$model = $this->getModel('projectindividual');
		if(!$model->publish($cid, 0)) {
			echo "<script> alert('".$model->getError(true)."'); window.history.go(-1); </script>\n";
		}
    $link = 'index.php?option=com_tracks&view=projectindividuals';
		$this->setRedirect($link);
	}

	function cancel()
	{
		// Checkin the project
		$model = $this->getModel('projectindividual');
		$model->checkin();

		$this->setRedirect( 'index.php?option=com_tracks&view=projectindividuals' );
	}


	function orderup()
	{
		$model = $this->getModel('projectindividual');
		$model->move(-1);

		$this->setRedirect( 'index.php?option=com_tracks&view=projectindividuals');
	}

	function orderdown()
	{
		$model = $this->getModel('projectindividual');
		$model->move(1);

		$this->setRedirect( 'index.php?option=com_tracks&view=projectindividuals');
	}

	function saveorder()
	{
		$cid 	= JRequest::getVar( 'cid', array(), 'post', 'array' );
		$order 	= JRequest::getVar( 'order', array(), 'post', 'array' );
		JArrayHelper::toInteger($cid);
		JArrayHelper::toInteger($order);

		$model = $this->getModel('projectindividual');
		$model->saveorder($cid, $order);

		$msg = 'New ordering saved';
		$this->setRedirect( 'index.php?option=com_tracks&view=projectindividuals', $msg );
	}
}
?>
