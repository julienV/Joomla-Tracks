<?php
/**
* @version    $Id: individual.php 140 2008-06-10 16:47:22Z julienv $
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

/**
 * Joomla Tracks Component Controller
 *
 * @package  Tracks
 * @since    0.1
 */
class TracksControllerIndividual extends FOFController
{
	/**
	 * Public constructor of the Controller class
	 *
	 * @param   array  $config  Optional configuration parameters
	 */
	public function __construct($config = array())
	{
		parent::__construct($config);
		$this->registerTask('assign', 'assign');
	}

	/**
	 * assign multiple individual to a project
	 */
	public function assign()
	{
		$cid = $this->input->get('cid', '', 'array');
		JArrayHelper::toInteger($cid);

		$model = $this->getThisModel();
		$model->setState('cid', $cid);

		$this->layout = 'assign';

		return $this->browse();
	}

	/**
	 * save assigned individuals
	 *
	 * @return bool
	 *
	 * @throws Exception
	 */
	public function saveassign()
	{
		$project_id = $this->input->getInt('project_id', 0);
		$cid        = $this->input->get('cid', '', 'array');
		$numbers    = $this->input->get('number', '', 'array');
		$team_id    = $this->input->get('team_id', '', 'array');
		JArrayHelper::toInteger($cid);
		JArrayHelper::toInteger($team_id);

		if (count($cid) < 1)
		{
			throw new Exception(JText::_('COM_TRACKS_Select_an_individual_to_assign'), 500);
		}

		$rows = array();

		foreach ($cid as $k => $id)
		{
			$row = new stdclass();
			$row->individual_id = $cid[$k];
			$row->team_id       = $team_id[$k];
			$row->number        = $numbers[$k];
			$row->project_id    = $project_id;
			$rows[] = $row;
		}

		$msg = '';
		$model = $this->getModel('projectindividuals');
		$model->setState('project_id', $project_id);
		$assigned = $model->assign($rows);

		if ($assigned === false)
		{
			$msg = $model->getError(true);
			$link = 'index.php?option=com_tracks&view=individuals';
			$this->setRedirect($link, $msg);
		}
		else
		{
			$msg = Jtext::sprintf('COM_TRACKS_D_INDIVIDUALS_ADDED_TO_PROJECT', $assigned);
			$app = JFactory::getApplication();
			$option = $app->input->getCmd('option', 'com_tracks');
			$app->setUserState($option . 'project', $project_id);

			$link = 'index.php?option=com_tracks&view=projectindividuals';
			$this->setRedirect($link, $msg);
		}

		return true;
	}

	/**
	 * individual element
	 */
	public function element()
	{
		$model  = $this->getModel( 'individuals' );
		$view = $this->getView( 'individuals', 'html' );
		$view->setLayout( 'element' );
		$view->setModel( $model, true );
		$view->display();
	}
}
