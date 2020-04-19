<?php
/**
 * @package     Tracks
 * @subpackage  Site
 * @copyright   Tracks (C) 2008-2015 Julien Vonthron. All rights reserved.
 * @license     GNU General Public License version 2 or later
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();

/**
 * Joomla Tracks Component Controller
 *
 * @package  Tracks
 * @since    2.0
 */
class TracksControllerProfile extends FOFController
{
	/**
	 * redirects to correct view...
	 *
	 * @return bool
	 *
	 * @throws Exception
	 */
	public function onBeforeAdd()
	{
		$user = JFactory::getUser();

		if (!($user))
		{
			throw new Exception('access not allowed', 403);
		}

		// If the user is logged, check if he already has a profile
		$model = FOFModel::getTmpInstance('IndividualEdits', 'TracksModel');
		$ind = $model->getUserIndividual($user->get('id'));

		if (!$ind)
		{
			$allow_register = JComponentHelper::getParams('com_tracks')->get('user_registration', 0);

			if (!$allow_register)
			{
				throw new Exception('Create individuals not allowed', 403);
			}

			// Redirect to individual edit
			$link = JRoute::_(TrackslibHelperRoute::getEditIndividualRoute());
		}
		else
		{
			// Redirect to individual edit
			$link = JRoute::_(TrackslibHelperRoute::getEditIndividualRoute($ind));
		}

		$this->setRedirect($link);
		$this->redirect();

		// We shouldn't arrive at that point ;)
		return false;
	}
}
