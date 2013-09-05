<?php
/**
* @version    0.2 $Id$
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
 * @since    0.2
 */
class TracksControllerSubround extends FOFController
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

		$prid = $this->input->getInt('projectround_id', 0);

		if (!$prid)
		{
			throw new Exception('projectround_id is required', 500);
		}

		$model->setState('projectround_id', $prid);
	}

	public function back()
	{
		$this->setRedirect('index.php?option=com_tracks&view=projectrounds');
	}

	/**
	 * Cancel the edit, check in the record and return to the Browse task
	 *
	 * @return  void
	 */
	public function cancel()
	{
		$model = $this->getThisModel();

		if (!$model->getId())
		{
			$model->setIDsFromRequest();
		}

		$model->checkin();

		// Remove any saved data
		JFactory::getSession()->set($model->getHash() . 'savedata', null);

		// Redirect to the display task

		if ($customURL = $this->input->get('returnurl', '', 'string'))
		{
			$customURL = base64_decode($customURL);
		}

		$url = !empty($customURL) ? $customURL : 'index.php?option=com_tracks&view=subrounds&projectround_id=' . $model->getState('projectround_id');
		$this->setRedirect($url);

		return true;
	}

	/**
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

		$prid = $this->input->getInt('projectround_id', 0);

		if (!$prid)
		{
			throw new Exception('projectround_id is required', 500);
		}

		$uri->setVar('projectround_id', $prid);
		$url = $uri->toString();

		return parent::setRedirect($url, $msg, $type);
	}
}
