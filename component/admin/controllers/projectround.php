<?php
/**
 * @version        $Id: projectround.php 94 2008-05-02 10:28:05Z julienv $
 * @package        JoomlaTracks
 * @copyright      Copyright (C) 2008 Julien Vonthron. All rights reserved.
 * @license        GNU/GPL, see LICENSE.php
 *                 Joomla Tracks is free software. This version may have been modified pursuant
 *                 to the GNU General Public License, and as distributed it includes or
 *                 is derivative of works licensed under the GNU General Public License or
 *                 other free or open source software licenses.
 *                 See COPYRIGHT.php for copyright notices and details.
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();

jimport('joomla.application.component.controller');

/**
 * Joomla Tracks Component Controller
 *
 * @package        Tracks
 * @since          0.1
 */
class TracksControllerProjectround extends FOFController
{
	public function savecopy()
	{
		$option     = $this->input->getCmd('option', '');
		$cid        = $this->input->get('cid', array(), 'post', 'array');
		$project_id = $this->input->getInt('project_id', 0);
		JArrayHelper::toInteger($cid);

		if (count($cid) < 1)
		{
			throw new Exception(JText::_('COM_TRACKS_Select_an_round_to_copy'), 500);
		}

		$model = $this->getModel('projectrounds');

		if (!$model->assign($cid, $project_id))
		{
			echo "<script> alert('" . $model->getError(true) . "');</script>\n";
		}

		JFactory::getApplication()->setUserState($option . 'project', $project_id);
		$link = 'index.php?option=com_tracks&view=projectrounds';
		$this->setRedirect($link);
	}

	/**
	 * Articles
	 */
	public function element()
	{
		$model = $this->getModel('projectroundElement');
		$view = $this->getView('projectroundElement', 'html');
		$view->setModel($model, true);
		$view->display();
	}

	/**
	 * display the copy form
	 * @return void
	 */
	public function copy()
	{
		$cid = $this->input->get('cid', '', 'array');
		JArrayHelper::toInteger($cid);

		$model = $this->getThisModel();
		$model->setState('cid', $cid);

		$this->layout = 'copy_form';

		return $this->browse();
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
