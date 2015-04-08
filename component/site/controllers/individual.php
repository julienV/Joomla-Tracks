<?php
/**
 * @package     Tracks
 * @subpackage  Admin
 * @copyright   Tracks (C) 2008-2015 Julien Vonthron. All rights reserved.
 * @license     GNU General Public License version 2 or later
 */

defined('_JEXEC') or die();

/**
 * Tracks Component Individual Controller
 *
 * @package     Tracks
 * @subpackage  Admin
 * @since       3.0
 */
class TracksControllerIndividual extends RControllerForm
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

	function save()
	{
		// Check for request forgeries
		JRequest::checkToken() or jexit('Invalid Token');

		$user = JFactory::getUser();
		$id = JRequest::getVar('i', 0, 'post', 'int');

		// perform security checks
		if (!$user->get('id'))
		{
			JError::raiseError(403, JText::_('COM_TRACKS_Access_Forbidden'));
			return;
		}

		// only autorize users can specify the user_id.
		if (!$user->authorise('core.manage', 'com_tracks'))
		{
			$user_id = $user->get('id');
		}
		else
		{
			$user_id = JRequest::getVar('user_id', 0, 'post', 'int');
		}

		//clean request
		// first, save description...
		$post = JRequest::get('post');
		$post['id'] = $id;
		$post['user_id'] = $user_id;
		$post['full_name'] = JRequest::getVar('full_name', '', 'post', 'string');
		$post['last_name'] = JRequest::getVar('last_name', '', 'post', 'string');
		$post['country_code'] = JRequest::getVar('country_code', '', 'post', 'string');
		$post['dob'] = JRequest::getVar('dob', '', 'post', 'string');
		$post['address'] = JRequest::getVar('address', '', 'post', 'string');
		$post['postcode'] = JRequest::getVar('postcode', '', 'post', 'string');
		$post['city'] = JRequest::getVar('city', '', 'post', 'string');
		$post['state'] = JRequest::getVar('state', '', 'post', 'string');
		$post['country'] = JRequest::getVar('country', '', 'post', 'string');
		$post['description'] = JRequest::getVar('description', '', 'post', 'string', JREQUEST_ALLOWRAW);

		$picture = JRequest::getVar('picture', '', 'files', 'array');
		$picture_small = JRequest::getVar('picture_small', '', 'files', 'array');

		// store data
		$model = $this->getModel('individual');

		if ($model->store($post, $picture, $picture_small))
		{
			$msg = JText::_('COM_TRACKS_Profile_has_been_saved');
		}
		else
		{
			$msg = $model->getError();
		}

		$this->setRedirect(JRoute::_(TrackslibHelperRoute::getIndividualRoute($model->getId())), $msg);
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
}

