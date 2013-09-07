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
	 * Execute something before applySave is called. Return false to prevent
	 * applySave from executing.
	 *
	 * @param   array  &$data  The data upon which applySave will act
	 *
	 * @return  boolean  True to allow applySave to run
	 */
	protected function onBeforeApplySave(&$data)
	{
		if (!isset($data['project_id']) || !$data['project_id'])
		{
			// Current project
			$app = JFactory::getApplication();
			$option = $app->input->getCmd('option', 'com_tracks');
			$data['project_id'] = $app->getUserState($option . 'project');
		}

		return true;
	}
}
