<?php
/**
 * @package     Tracks
 * @subpackage  Admin
 * @copyright   Tracks (C) 2008-2015 Julien Vonthron. All rights reserved.
 * @license     GNU General Public License version 2 or later
 */

defined('_JEXEC') or die();

/**
 * Tracks Component Participants Controller
 *
 * @package     Tracks
 * @subpackage  Admin
 * @since       3.0
 */
class TracksControllerParticipants extends RControllerAdmin
{
	/**
	 * Add selected participants to all project round events
	 *
	 * @return void
	 */
	public function addToAllEvents()
	{
		// Check for request forgeries.
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

		// Get the input
		$pks = $this->input->post->get('cid', array(), 'array');

		$model = $this->getModel('participants');

		foreach ($pks as $participantId)
		{
			$model->addToAllRounds($participantId);
		}

		// Set redirect
		$this->setRedirect($this->getRedirectToListRoute(), JText::_('COM_TRACKS_PARTICIPANTS_ADD_TO_ALL_EVENTS_SUCCESS'));
	}
}
