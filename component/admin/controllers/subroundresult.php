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
 * @since 0.1
 */
class TracksControllerSubroundresult extends BaseController
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
				JRequest::setVar( 'view'  , 'subroundResult');
				JRequest::setVar( 'edit', false );

				// Checkout the project
				$model = $this->getModel('subroundResult');
				$model->checkout();
			} break;
			case 'edit'    :
			{
				JRequest::setVar( 'hidemainmenu', 1 );
				JRequest::setVar( 'layout', 'form'  );
				JRequest::setVar( 'view'  , 'subroundResult');
				JRequest::setVar( 'edit', true );

				// Checkout the project
				$model = $this->getModel('subroundResult');
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

		$model = $this->getModel('subroundResult');

		if ($model->store($post)) {
			$msg = JText::_('COM_TRACKS_Subround_result_saved' );
			$msgtype = 'message';
		} else {
			$msg = JText::_('COM_TRACKS_Error_Saving_subround_result' ).$model->getError();
			$msgtype = 'error';
		}

		// Check the table in so it can be edited.... we are done with it anyway
		$model->checkin();
		if ( $post['id'] ==0 || $this->getTask() == 'save' ) {
		  $link = 'index.php?option=com_tracks&view=subroundresults&srid='.$post['subround_id'];
		}
		else {
		  $link = 'index.php?option=com_tracks&controller=subroundresult&task=edit&cid[]='.$post['id'];
		}
		$this->setRedirect($link, $msg, $msgtype);
	}

	function addall()
	{
		$model = $this->getModel('subroundResult');

		if ($model->addAll()) {
			$msg = JText::_('COM_TRACKS_Imported_participants' );
      $msgtype = 'message';
		}
		else {
      $msg = JText::_('COM_TRACKS_Error_importing_participants' ).$model->getError();
      $msgtype = 'error';
      $this->setError($msg);
		}
    parent::display();
	}

	function remove()
	{
	  $cid = JRequest::getVar( 'cid', array(), 'post', 'array' );
	  $srid = JRequest::getVar( 'srid', 0 );
		JArrayHelper::toInteger($cid);

		if (count( $cid ) < 1) {
			JError::raiseError(500, JText::_('COM_TRACKS_Select_an_item_to_delete' ) );
		}

		$model = $this->getModel('subroundresult');

		$msg = '';

		if(!$model->delete($cid)) {
			$msg = JText::_('COM_TRACKS_Error_while_removing_participants');
		}

		$this->setRedirect( 'index.php?option=com_tracks&view=subroundresults&srid='.$srid, $msg, 'error');
	}

	function cancel()
	{
		// Checkin the project
		$model = $this->getModel('subroundresult');
		$model->checkin();
    $post = JRequest::get('post');
		$this->setRedirect( 'index.php?option=com_tracks&view=subroundresults&srid='.$post['subround_id']);
	}

  function back()
  {
    $model = $this->getModel('subroundresults');
    $infos = $model->getSubroundInfo();
    $this->setRedirect( 'index.php?option=com_tracks&view=subrounds&projectround_id='.$infos->projectround_id);
  }

	function saveorder()
	{
		$cid 	= JRequest::getVar( 'cid', array(), 'post', 'array' );
		$order 	= JRequest::getVar( 'order', array(), 'post', 'array' );
		JArrayHelper::toInteger($cid);
		JArrayHelper::toInteger($order);

		$model = $this->getModel('subroundresult');
		$model->saveorder($cid, $order);

		$msg = 'New ordering saved';
		$this->setRedirect( 'index.php?option=com_tracks&view=subroundresult', $msg );
	}

	function saveranks()
	{
		$mainframe = JFactory::getApplication();
		$option = JRequest::getCmd('option');

		$cid 	= JRequest::getVar( 'cid', array(), 'post', 'array' );
		$rank = JRequest::getVar( 'rank', array(), 'post', 'array' );
    $bonus_points = JRequest::getVar( 'bonus_points', array(), 'post', 'array' );
		$performance = JRequest::getVar( 'performance', array(), 'post', 'array' );
		$individual = JRequest::getVar( 'individual', array(), 'post', 'array' );
		$team = JRequest::getVar( 'team', array(), 'post', 'array' );
		$subround_id = JRequest::getVar( 'subround_id', 0, 'post', 'int' );

		JArrayHelper::toInteger($cid);
		JArrayHelper::toInteger($rank);
//    JArrayHelper::toInteger($bonus_points);
		JArrayHelper::toInteger($individual);
		JArrayHelper::toInteger($team);

		$model = $this->getModel('subroundresult');
		//print_r($model); exit('model');
		if ( $model->saveranks($cid, $rank, $bonus_points, $performance, $individual, $team, $subround_id) ) {
		  $msg = 'Results saved';
		}
		else $msg = 'Error saving results';
		$this->setRedirect( 'index.php?option=com_tracks&view=subroundresults&srid='.$subround_id, $msg );
	}
}
?>
