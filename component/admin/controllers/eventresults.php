<?php
/**
 * @package     Tracks
 * @subpackage  Admin
 * @copyright   Tracks (C) 2008-2015 Julien Vonthron. All rights reserved.
 * @license     GNU General Public License version 2 or later
 */

defined('_JEXEC') or die();

/**
 * Tracks Component Event results Controller
 *
 * @package     Tracks
 * @subpackage  Admin
 * @since       3.0
 */
class TracksControllerEventresults extends RControllerAdmin
{
	/**
	 * Add all project participants to event results
	 *
	 * @return void
	 */
	public function addall()
	{
		$event_id = $this->input->getInt('event_id');
		$model = $this->getModel('Eventresults');
		$model->setState('event_id', $event_id);

		if ($model->addAll())
		{
			$this->setMessage(JText::_('COM_TRACKS_Imported_participants'));
		}
		else
		{
			$this->setMessage(JText::_('COM_TRACKS_Error_importing_participants') . $model->getError(), 'error');
		}

		$this->setRedirect($this->getRedirectToListRoute());
	}

	public function back()
	{
		$model = $this->getModel('eventresults');
		$infos = $model->getEventInfo();
		$this->setRedirect('index.php?option=com_tracks&view=events&projectround_id=' . $infos->projectround_id);
	}

	public function saveranks()
	{
		$cid = $this->input->getVar('cid', array(), 'post', 'array');
		$rank = $this->input->getVar('rank', array(), 'post', 'array');
		$bonus_points = $this->input->getVar('bonus_points', array(), 'post', 'array');
		$performance = $this->input->getVar('performance', array(), 'post', 'array');

		JArrayHelper::toInteger($cid);
		JArrayHelper::toInteger($rank);

		$model = $this->getModel('eventresults');

		try
		{
			$model->saveranks($cid, $rank, $bonus_points, $performance);
			$this->setMessage('COM_TRACKS_RESULTS_SAVED');
		}
		catch (RuntimeException $e)
		{
			$this->setMessage('COM_TRACKS_RESULTS_SAVE_ERROR' . ': ' . $e->getMessage(), 'error');
		}

		$this->setRedirect($this->getRedirectToListRoute());
	}

	/**
	 * Forces event_id in all redirections
	 *
	 * Registers a redirection with an optional message. The redirection is
	 * carried out when you use the redirect method.
	 *
	 * @param   string  $url   The URL to redirect to
	 * @param   string  $msg   The message to be pushed to the application
	 * @param   string  $type  The message type to be pushed to the application, e.g. 'error'
	 *
	 * @return  JController  This object to support chaining
	 */
	public function setRedirect($url, $msg = null, $type = null)
	{
		$uri = JURI::getInstance($url);

		$id = $this->input->getInt('event_id', 0);

		if (!$id)
		{
			$formData = $this->input->get('jform', array(), 'array');

			if (!isset($formData['event_id']) || !$formData['event_id'])
			{
				throw new RuntimeException('event_id is required', 500);
			}
		}

		$uri->setVar('event_id', $id);
		$url = $uri->toString();

		return parent::setRedirect($url, $msg, $type);
	}
}
