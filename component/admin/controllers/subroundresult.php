<?php
/**
 * @version    0.2 $Id$
 * @package    JoomlaTracks
 * @copyright  Copyright (C) 2008 Julien Vonthron. All rights reserved.
 * @license    GNU/GPL, see LICENSE.php
 *             Joomla Tracks is free software. This version may have been modified pursuant
 *             to the GNU General Public License, and as distributed it includes or
 *             is derivative of works licensed under the GNU General Public License or
 *             other free or open source software licenses.
 *             See COPYRIGHT.php for copyright notices and details.
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();

/**
 * Joomla Tracks Component Controller
 *
 * @package  Tracks
 * @since    0.1
 */
class TracksControllerSubroundresult extends FOFController
{
	/**
	 * Public constructor of the Controller class
	 *
	 * @param   array  $config  Optional configuration parameters
	 */
	public function __construct($config = array())
	{
		parent::__construct($config);

		// Do I have a form?
		$model = $this->getThisModel();

		$prid = $this->input->getInt('subround_id', 0);

		if (!$prid)
		{
			throw new Exception('subround_id is required', 500);
		}

		$model->setState('subround_id', $prid);
	}

	/**
	 * Add all project participants to subround results
	 */
	public function addall()
	{
		$model = $this->getModel('Subroundresults');

		if ($model->addAll())
		{
			$msg = JText::_('COM_TRACKS_Imported_participants');
			$msgtype = 'message';
		}
		else
		{
			$msg = JText::_('COM_TRACKS_Error_importing_participants') . $model->getError();
			$msgtype = 'error';
		}

		$this->setRedirect('index.php?option=com_tracks&view=subroundresults', $msg, $msgtype);
	}


	public function back()
	{
		$model = $this->getModel('subroundresults');
		$infos = $model->getSubroundInfo();
		$this->setRedirect('index.php?option=com_tracks&view=subrounds&projectround_id=' . $infos->projectround_id);
	}

	public function saveranks()
	{
		$option = $this->input->getCmd('option', 'com_tracks');

		$cid = $this->input->getVar('cid', array(), 'post', 'array');
		$rank = $this->input->getVar('rank', array(), 'post', 'array');
		$bonus_points = $this->input->getVar('bonus_points', array(), 'post', 'array');
		$performance = $this->input->getVar('performance', array(), 'post', 'array');
		$individual = $this->input->getVar('individual', array(), 'post', 'array');
		$team = $this->input->getVar('team', array(), 'post', 'array');
		$subround_id = $this->input->getVar('subround_id', 0, 'post', 'int');

		JArrayHelper::toInteger($cid);
		JArrayHelper::toInteger($rank);
		JArrayHelper::toInteger($individual);
		JArrayHelper::toInteger($team);

		$model = $this->getThisModel();

		if ($model->saveranks($cid, $rank, $bonus_points, $performance, $individual, $team, $subround_id))
		{
			$msg = 'Results saved';
			$msgtype = 'message';
		}
		else
		{
			$msg = 'Error saving results';
			$msgtype = 'error';
		}

		$this->setRedirect('index.php?option=com_tracks&view=subroundresults', $msg, $msgtype);
	}

	/**
	 * Forces subround_id in all redirections
	 *
	 * Registers a redirection with an optional message. The redirection is
	 * carried out when you use the redirect method.
	 *
	 * @param   string  $url   The URL to redirect to
	 * @param   string  $msg   The message to be pushed to the application
	 * @param   string  $type  The message type to be pushed to the application, e.g. 'error'
	 *
	 * @return  FOFController  This object to support chaining
	 */
	public function setRedirect($url, $msg = null, $type = null)
	{
		$uri = JURI::getInstance($url);

		$id = $this->input->getInt('subround_id', 0);

		if (!$id)
		{
			throw new Exception('subround_id is required', 500);
		}

		$uri->setVar('subround_id', $id);
		$url = $uri->toString();

		return parent::setRedirect($url, $msg, $type);
	}
}
