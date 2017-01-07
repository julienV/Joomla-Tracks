<?php
/**
 * @package     Tracks
 * @subpackage  Library
 * @copyright   Tracks (C) 2008-2015 Julien Vonthron. All rights reserved.
 * @license     GNU General Public License version 2 or later
 */

defined('_JEXEC') or die;

/**
 * HTML View class for the Tracks component
 *
 * @package  Tracks
 * @since    0.1
 */
class TracksViewProfile extends RViewSite
{
	/**
	 * Execute and display a template script.
	 *
	 * @param   string  $tpl  The name of the template file to parse; automatically searches through the template paths.
	 *
	 * @return  mixed  A string if successful, otherwise a Error object.
	 */
	public function display($tpl = null)
	{
		$mainframe = JFactory::getApplication();
		$user = JFactory::getUser();

		if (!$user->id)
		{
			print JText::_('COM_TRACKS_You_must_register_to_access_this_page');

			return;
		}

		$model = $this->getModel();
		$data = $model->getData($user->id);

		if (!$data->id)
		{
			// No tracks individual associated to profile
			$params = $mainframe->getParams('com_tracks');

			if ($params->get('user_registration'))
			{
				$mainframe->redirect(TrackslibHelperRoute::getEditIndividualRoute());
			}
			else
			{
				$msg = JText::_('COM_TRACKS_No_tracks_individual_associated_to_your_account');
				$mainframe->redirect('index.php?option=com_tracks', $msg);
			}
		}

		$mainframe->redirect(TrackslibHelperRoute::getIndividualRoute($data->id));
	}
}
