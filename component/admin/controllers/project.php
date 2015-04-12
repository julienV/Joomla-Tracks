<?php
/**
 * @package     Tracks
 * @subpackage  Admin
 * @copyright   Tracks (C) 2008-2015 Julien Vonthron. All rights reserved.
 * @license     GNU General Public License version 2 or later
 */

defined('_JEXEC') or die();

/**
 * Tracks Component Competition Controller
 *
 * @package     Tracks
 * @subpackage  Admin
 * @since       3.0
 */
class TracksControllerProject extends RControllerForm
{
	/**
	 * Make select project active for edition
	 *
	 * @return void
	 */
	public function select()
	{
		$app = JFactory::getApplication();

		$id	= $app->input->getInt('currentproject', 0);
		$return = $app->input->get('return');

		if ($return)
		{
			$this->setRedirect(base64_decode($return));
		}
		else
		{
			$this->setRedirect('index.php?option=com_tracks&view=projects');
		}

		// Update session value for project
		if ($app->setUserState('currentproject', $id))
		{
			$this->setMessage(JText::_('COM_TRACKS_Project_selected'));
		}
		else
		{
			$this->setMessage(JText::_('COM_TRACKS_Error_while_selecting_project'), 'error');
		}
	}
}
