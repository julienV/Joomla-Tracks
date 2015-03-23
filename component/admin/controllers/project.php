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
		$option = $app->input->getCmd('option', 'com_tracks');

		// Get cid array from address
		$id	= $app->input->getInt('id', 0);

		// Update session value for project
		if ($app->setUserState($option . 'project', $id))
		{
			$this->setRedirect('index.php?option=com_tracks', JText::_('COM_TRACKS_Project_selected'));
		}
		else
		{
			$this->setRedirect('index.php?option=com_tracks', JText::_('COM_TRACKS_Error_while_selecting_project'), 'error');
		}
	}
}
