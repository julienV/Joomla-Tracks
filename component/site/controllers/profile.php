<?php
/**
 * @package     Tracks
 * @subpackage  Site
 * @copyright   Tracks (C) 2008-2015 Julien Vonthron. All rights reserved.
 * @license     GNU General Public License version 2 or later
 */

// Check to ensure this file is included in Joomla!
use Joomla\CMS\Router\Route;

defined('_JEXEC') or die();

/**
 * Joomla Tracks Component Controller
 *
 * @package  Tracks
 * @since    2.0
 */
class TracksControllerProfile extends RControllerForm
{
	/**
	 * Add an individual
	 *
	 * @return  mixed  True if the record can be added, a error object if not.
	 */
	public function add()
	{
		if (!JFactory::getUser()->get('id'))
		{
			$this->setRedirect(JURI::base(), JText::_('COM_TRACKS_Please_login_to_be_able_to_edit_your_tracks_profile'), 'error');
			$this->redirect();
		}

		return parent::add();
	}

	/**
	 * Edit an individual
	 *
	 * @param   string  $key     key
	 * @param   string  $urlVar  urlvar
	 *
	 * @return bool
	 */
	public function edit($key = null, $urlVar = null)
	{
		if (!JFactory::getUser()->get('id'))
		{
			$this->setRedirect(JURI::base(), JText::_('COM_TRACKS_Please_login_to_be_able_to_edit_your_tracks_profile'), 'error');
			$this->redirect();
		}

		return parent::edit($key, $urlVar);
	}

	/**
	 * Method to check if you can add a new record.
	 *
	 * Extended classes can override this if necessary.
	 *
	 * @param   array  $data  An array of input data.
	 *
	 * @return  boolean
	 *
	 * @since   12.2
	 */
	protected function allowAdd($data = array())
	{
		return JComponentHelper::getParams('com_tracks')->get('user_registration');
	}

	/**
	 * Method to check if you can add a new record.
	 *
	 * Extended classes can override this if necessary.
	 *
	 * @param   array   $data  An array of input data.
	 * @param   string  $key   The name of the key for the primary key; default is id.
	 *
	 * @return  boolean
	 */
	protected function allowEdit($data = array(), $key = 'id')
	{
		$model = $this->getModel();
		$item = $model->getItem($data[$key]);
		$user = JFactory::getUser();

		return JComponentHelper::getParams('com_tracks')->get('user_registration') && $item->user_id == $user->id
			|| JFactory::getUser()->authorise('core.edit', 'com_tracks');
	}

	/**
	 * Function that allows child controller access to model data
	 * after the data has been saved.
	 *
	 * @param   \JModelLegacy  $model      The data model object.
	 * @param   array          $validData  The validated data.
	 *
	 * @return  void
	 *
	 * @since   1.6
	 */
	protected function postSaveHook(\JModelLegacy $model, $validData = array())
	{
		$returnUrl = $this->input->get('return', '', 'Base64');

		if ($returnUrl)
		{
			$returnUrl = base64_decode($returnUrl);

			$this->setRedirect($returnUrl, false);
		}
		else
		{
			$id = $model->getState($model->getName() . '.id');
			$this->setRedirect(Route::_(TrackslibHelperRoute::getIndividualRoute($id)));
		}
	}
}
