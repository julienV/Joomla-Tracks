<?php
/**
* @version    2.0
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
 * @since    2.0
 */
class TracksControllerIndividualedits extends FOFController
{
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

		$url = TracksHelperRoute::getIndividualRoute($model->getId());
		$this->setRedirect($url);

		return true;
	}

	public function save()
	{
		$result = parent::save();
		$model = $this->getThisModel();

		if ($result)
		{
			$id  = $model->getId();
			$url = TracksHelperRoute::getIndividualRoute($id);

			$this->setRedirect($url, JText::_('COM_TRACKS_INDIVIDUAL_SAVED'));
			return true;
		}

		return $result;
	}
}
