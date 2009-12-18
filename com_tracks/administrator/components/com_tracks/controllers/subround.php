<?php
/**
* @version    0.2 $Id$ 
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
 * @since 0.2
 */
class TracksControllerSubround extends BaseController
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
				JRequest::setVar( 'view'  , 'subround');
				JRequest::setVar( 'edit', false );

				// Checkout the project
				$model = $this->getModel('subround');
				$model->checkout();
			} break;
			case 'edit'    :
			{
				JRequest::setVar( 'hidemainmenu', 1 );
				JRequest::setVar( 'layout', 'form'  );
				JRequest::setVar( 'view'  , 'subround');
				JRequest::setVar( 'edit', true );

				// Checkout the project
				$model = $this->getModel('subround');
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

		$model = $this->getModel('subround');

		if ($returnid = $model->store($post)) {
			$msg = JText::_( 'Sub-round Saved' );
		} else {
			$msg = JText::_( 'Error Saving Project sub-round' ).$model->getError();
		}

		// Check the table in so it can be edited.... we are done with it anyway
		$model->checkin();
		   
		//redirect
    if ( !$returnid || $this->getTask() == 'save' ) {
      $link = 'index.php?option=com_tracks&view=subrounds&prid='.$post['projectround_id'];
    }
    else {
      $link = 'index.php?option=com_tracks&controller=subround&task=edit&cid[]='.$returnid;
    }
		$this->setRedirect($link, $msg);
	}

	function remove()
	{
	  $cid = JRequest::getVar( 'cid', array(), 'post', 'array' );
		JArrayHelper::toInteger($cid);

		if (count( $cid ) < 1) {
			JError::raiseError(500, JText::_( 'Select an item to delete' ) );
		}

		$model = $this->getModel('subround');
		
		if(!$model->delete($cid)) {
			echo "<script> alert('".$model->getError(true)."'); window.history.go(-1); </script>\n";
		}
		
    $prid = JRequest::getVar( 'projectround', int, 'post', 0 );
		$this->setRedirect( 'index.php?option=com_tracks&view=subrounds&prid='.$prid );
	}


	function publish()
	{		
	  $cid = JRequest::getVar( 'cid', array(), 'post', 'array' );
		JArrayHelper::toInteger($cid);

		if (count( $cid ) < 1) {
			JError::raiseError(500, JText::_( 'Select an item to publish' ) );
		}

		$model = $this->getModel('subround');
		if(!$model->publish($cid, 1)) {
			echo "<script> alert('".$model->getError(true)."'); window.history.go(-1); </script>\n";
		}
		$prid = JRequest::getVar( 'projectround', int, 'post', 0 );
    $this->setRedirect( 'index.php?option=com_tracks&view=subrounds&prid='.$prid );
	}


	function unpublish()
	{
		$cid = JRequest::getVar( 'cid', array(), 'post', 'array' );
		JArrayHelper::toInteger($cid);

		if (count( $cid ) < 1) {
			JError::raiseError(500, JText::_( 'Select an item to unpublish' ) );
		}

		$model = $this->getModel('subround');
		if(!$model->publish($cid, 0)) {
			echo "<script> alert('".$model->getError(true)."'); window.history.go(-1); </script>\n";
		}
    $prid = JRequest::getVar( 'projectround', int, 'post', 0 );
    $this->setRedirect( 'index.php?option=com_tracks&view=subrounds&prid='.$prid );
	}

	function cancel()
	{
		// Checkin the project
		$model = $this->getModel('subround');
		$model->checkin();

		$prid = JRequest::getVar( 'projectround_id', int, 'post', 0 );
    $this->setRedirect( 'index.php?option=com_tracks&view=subrounds&prid='.$prid );
	}

  function back()
  {
    $this->setRedirect( 'index.php?option=com_tracks&view=projectrounds' );
  }

	function orderup()
	{
		$model = $this->getModel('subround');
		$model->move(-1);

		$prid = JRequest::getVar( 'projectround', int, 'post', 0 );
    $this->setRedirect( 'index.php?option=com_tracks&view=subrounds&prid='.$prid );
	}

	function orderdown()
	{
		$model = $this->getModel('subround');
		$model->move(1);
		
    $prid = JRequest::getVar( 'projectround', int, 'post', 0 );
    $this->setRedirect( 'index.php?option=com_tracks&view=subrounds&prid='.$prid );
	}

	function saveorder()
	{
		$cid 	= JRequest::getVar( 'cid', array(), 'post', 'array' );
		$order 	= JRequest::getVar( 'order', array(), 'post', 'array' );
		JArrayHelper::toInteger($cid);
		JArrayHelper::toInteger($order);

		$model = $this->getModel('subround');
		$model->saveorder($cid, $order);
		
    $msg = 'New ordering saved';		
    $prid = JRequest::getVar( 'projectround', int, 'post', 0 );
    $this->setRedirect( 'index.php?option=com_tracks&view=subrounds&prid='.$prid, $msg );
	}
}
?>
