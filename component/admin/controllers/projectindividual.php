<?php
/**
* @version    $Id: projectindividual.php 94 2008-05-02 10:28:05Z julienv $
* @package    JoomlaTracks
* @copyright  Copyright (C) 2008 Julien Vonthron. All rights reserved.
* @license    GNU/GPL, see LICENSE.php
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
 * @package  Tracks
 * @since    0.1
 */
class TracksControllerProjectindividual extends FOFController
{
	/**
	 * Public constructor of the Controller class
	 *
	 * @param   array  $config  Optional configuration parameters
	 */
	public function __construct($config = array())
	{
		parent::__construct($config);

		// Current project
		$app = JFactory::getApplication();
		$option = $app->input->getCmd('option', 'com_tracks');
		$project_id = $app->getUserState($option . 'project');

		// Do I have a form?
		$model = $this->getThisModel();

		if (!$project_id)
		{
			throw new Exception('project_id is required', 500);
		}

		$model->setState('project_id', $project_id);
	}

	/**
	 * save and assign
	 *
	 * @return void
	 */
	public function saveassign()
	{
		$cid = $this->input->get( 'cid', array(), 'post', 'array' );
		$team_id = $this->input->get( 'team_id', array(), 'post', 'array' );
		$numbers = $this->input->get( 'number', array(), 'post', 'array' );
		$project_id = $this->input->get( 'project_id', 0, 'post', 'int' );
		JArrayHelper::toInteger($cid);
		JArrayHelper::toInteger($team_id);

		if (count( $cid ) < 1)
		{
			throw new Exception(JText::_('COM_TRACKS_Select_an_individual_to_assign'), 500);
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

		if(!$model->assign($rows))
		{
			$msg = $model->getError(true);
		}

		$link = 'index.php?option=com_tracks&view=projectindividuals';
		$this->setRedirect($link, $msg);
	}
}
