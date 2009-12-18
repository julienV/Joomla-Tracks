<?php
/**
* @version    $Id: projectround.php 94 2008-05-02 10:28:05Z julienv $ 
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
class TracksControllerProjectround extends BaseController
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
				JRequest::setVar( 'view'  , 'projectround');
				JRequest::setVar( 'edit', false );

				// Checkout the project
				$model = $this->getModel('projectround');
				$model->checkout();
			} break;
			case 'edit'    :
			{
				JRequest::setVar( 'hidemainmenu', 1 );
				JRequest::setVar( 'layout', 'form'  );
				JRequest::setVar( 'view'  , 'projectround');
				JRequest::setVar( 'edit', true );

				// Checkout the project
				$model = $this->getModel('projectround');
				$model->checkout();
			} break;
		}
		parent::display();
	}
	
  function save()
	{
		$post	= JRequest::get('post');
		$cid	= JRequest::getVar( 'cid', array(0), 'post', 'array' );
		$post['id'] = (int) $cid[0];

		$model = $this->getModel('projectround');

		$type = 'message';
		if ($returnid = $model->store($post)) {
			$msg = JText::_( 'Project Round Saved' );
		} else {
			$msg = JText::_( 'Error Saving Project Round' ).': '.$model->getError();
      $type = 'error';
		}

		// Check the table in so it can be edited.... we are done with it anyway
		$model->checkin();
		   
    //redirect
    if ( !$returnid || $this->getTask() == 'save' ) {
      $link = 'index.php?option=com_tracks&view=projectrounds';
    }
    else {
      $link = 'index.php?option=com_tracks&controller=projectround&task=edit&cid[]='.$returnid;
    }
		$this->setRedirect($link, $msg, $type);
	}
	

  function savecopy()
  {   
    $cid = JRequest::getVar( 'cid', array(), 'post', 'array' );
    $project_id = JRequest::getVar( 'project_id', 0, 'post', 'int' );
    JArrayHelper::toInteger($cid);
    JArrayHelper::toInteger($team_id);

    if (count( $cid ) < 1) {
      JError::raiseError(500, JText::_( 'Select an round to copy' ) );
    }
    
    $model = $this->getModel('projectround');
    if(!$model->assign($cid, $project_id)) {
      echo "<script> alert('".$model->getError(true)."');</script>\n";
    }
    $link = 'index.php?option=com_tracks&view=projectrounds';
    $this->setRedirect($link);
  }

	function remove()
	{
	  $cid = JRequest::getVar( 'cid', array(), 'post', 'array' );
		JArrayHelper::toInteger($cid);

		if (count( $cid ) < 1) {
			JError::raiseError(500, JText::_( 'Select an item to delete' ) );
		}

		$model = $this->getModel('projectround');
		
		if(!$model->delete($cid)) {
			echo "<script> alert('".$model->getError(true)."'); window.history.go(-1); </script>\n";
		}

		$this->setRedirect( 'index.php?option=com_tracks&view=projectrounds' );
	}


	function publish()
	{		
	  $cid = JRequest::getVar( 'cid', array(), 'post', 'array' );
		JArrayHelper::toInteger($cid);

		if (count( $cid ) < 1) {
			JError::raiseError(500, JText::_( 'Select an item to publish' ) );
		}

		$model = $this->getModel('projectround');
		if(!$model->publish($cid, 1)) {
			echo "<script> alert('".$model->getError(true)."'); window.history.go(-1); </script>\n";
		}
    $link = 'index.php?option=com_tracks&view=projectrounds';
		$this->setRedirect($link);
	}


	function unpublish()
	{
		$cid = JRequest::getVar( 'cid', array(), 'post', 'array' );
		JArrayHelper::toInteger($cid);

		if (count( $cid ) < 1) {
			JError::raiseError(500, JText::_( 'Select an item to unpublish' ) );
		}

		$model = $this->getModel('projectround');
		if(!$model->publish($cid, 0)) {
			echo "<script> alert('".$model->getError(true)."'); window.history.go(-1); </script>\n";
		}
    $link = 'index.php?option=com_tracks&view=projectrounds';
		$this->setRedirect($link);
	}

	function cancel()
	{
		// Checkin the project
		$model = $this->getModel('projectround');
		$model->checkin();

		$this->setRedirect( 'index.php?option=com_tracks&view=projectrounds' );
	}


	function orderup()
	{
		$model = $this->getModel('projectround');
		$model->move(-1);

		$this->setRedirect( 'index.php?option=com_tracks&view=projectrounds');
	}

	function orderdown()
	{
		$model = $this->getModel('projectround');
		$model->move(1);

		$this->setRedirect( 'index.php?option=com_tracks&view=projectrounds');
	}

	function saveorder()
	{
		$cid 	= JRequest::getVar( 'cid', array(), 'post', 'array' );
		$order 	= JRequest::getVar( 'order', array(), 'post', 'array' );
		JArrayHelper::toInteger($cid);
		JArrayHelper::toInteger($order);

		$model = $this->getModel('projectround');
		$model->saveorder($cid, $order);

		$msg = 'New ordering saved';
		$this->setRedirect( 'index.php?option=com_tracks&view=projectrounds', $msg );
	}
	
  /**
   * Articles element
   */
  function element()
  {
    $model  = &$this->getModel( 'projectroundElement' );
    $view = &$this->getView( 'projectroundElement', 'html' );
    $view->setModel( $model, true );
    $view->display();
  }

  /**
   * display the copy form
   * @return void
   */
  function copy()
  {
    JRequest::setVar( 'view', 'projectrounds' );
    JRequest::setVar( 'layout', 'copy_form'  );
    JRequest::setVar('hidemainmenu', 1);

    parent::display();
  }
}
?>
