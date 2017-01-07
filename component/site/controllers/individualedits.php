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
	/**
	 * Public constructor of the Controller class
	 *
	 * @param   array  $config  Optional configuration parameters
	 */
	public function __construct($config = array())
	{
		parent::__construct($config);
	}

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

		$url = TrackslibHelperRoute::getIndividualRoute($model->getId());
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
			$url = TrackslibHelperRoute::getIndividualRoute($id);

			$this->setRedirect($url, JText::_('COM_TRACKS_INDIVIDUAL_SAVED'));
			return true;
		}

		return $result;
	}

	protected function onBeforeAdd()
	{
		$user = JFactory::getUser();

		if (!($user))
		{
			throw new Exception('access not allowed', 403);
		}

		$allow_register = JComponentHelper::getParams('com_tracks')->get('user_registration', 0);

		if (!$allow_register)
		{
			throw new Exception('Create individuals not allowed', 403);
		}

		return true;
	}

	/**
	 * ACL check before editing a record
	 *
	 * @return  boolean  True to allow the method to run
	 */
	protected function onBeforeEdit()
	{
		$user = JFactory::getUser();

		if (!($user))
		{
			throw new Exception('access not allowed', 403);
		}

		return true;
	}

	protected function onBeforeSave()
	{
		$user = JFactory::getUser();

		return $user->get('id');
	}

	/**
	 * Execute something before applySave is called. Return false to prevent
	 * applySave from executing.
	 *
	 * @param   array  &$data  The data upon which applySave will act
	 *
	 * @return  boolean  True to allow applySave to run
	 */
	protected function onBeforeApplySave(&$data)
	{
		if (!$data['id'])
		{
			if (!JComponentHelper::getParams('com_tracks')->get('user_registration', 0))
			{
				throw new Exception('Create individuals not allowed', 403);
			}

			// Set user id to current user
			$data['user_id'] = JFactory::getUser()->get('id');

			return true;
		}

		// Else, make sure the user can actually edit this record
		$model = $this->getThisModel();
		$model->setId((int) $data['id']);
		$ind = $model->getItem();

		$user = JFactory::getUser();

		if (!$ind->user_id && !$user->authorise('core.manage', 'com_tracks'))
		{
			throw new Exception('Edit individuals not allowed', 403);
		}
		elseif (!$ind->user_id)
		{
			$data['user_id'] = JFactory::getUser()->get('id');
		}
		elseif (!($ind->user_id == JFactory::getUser()->get('id') || $user->authorise('core.manage', 'com_tracks')))
		{
			throw new Exception('Edit individuals not allowed', 403);
		}

		return true;
	}
}
