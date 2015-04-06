<?php
/**
 * @version        $Id: view.html.php 77 2008-04-30 03:32:25Z julienv $
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

jimport('joomla.application.component.view');

/**
 * HTML View class for the Tracks component
 *
 * @static
 * @package        Tracks
 * @since          0.1
 */
class TracksViewProfile extends JViewLegacy
{
	function display($tpl = null)
	{
		$mainframe = JFactory::getApplication();
		$option = JRequest::getCmd('option');

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
			// no tracks individual associated to profile
			$params = $mainframe->getParams('com_tracks');
			if ($params->get('user_registration'))
			{
				$mainframe->redirect('index.php?option=com_tracks&view=individualedit&task=add');
			}
			else
			{
				$msg = JText::_('COM_TRACKS_No_tracks_individual_associated_to_your_account');
				$mainframe->redirect('index.php?option=com_tracks', $msg);
			}
		}

		$mainframe->redirect('index.php?option=com_tracks&view=individual&i=' . $data->id);
	}
}
