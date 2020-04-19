<?php
/**
 * @package     Tracks
 * @subpackage  Admin
 * @copyright   Tracks (C) 2008-2015 Julien Vonthron. All rights reserved.
 * @license     GNU General Public License version 2 or later
 */

defined('_JEXEC') or die();

/**
 * Tracks Component Projects Controller
 *
 * @package     Tracks
 * @subpackage  Admin
 * @since       3.0
 */
class TracksControllerProjects extends RControllerAdmin
{
	/**
	 * Deep Copy projects
	 *
	 * @return void
	 */
	public function copy()
	{
		// Check for request forgeries
		JSession::checkToken() or die(JText::_('JINVALID_TOKEN'));

		// Get items to publish from the request.
		$cid = JFactory::getApplication()->input->get('cid', array(), 'array');

		if (empty($cid))
		{
			JLog::add(JText::_($this->text_prefix . '_NO_ITEM_SELECTED'), JLog::WARNING, 'jerror');
		}
		else
		{
			// Get the model.
			$model = $this->getModel();

			// Make sure the item ids are integers
			JArrayHelper::toInteger($cid);

			// Copy the items.
			try
			{
				if ($model->copy($cid))
				{
					$this->setMessage(JText::plural('COM_TRACKS_PROJECTS_N_ITEMS_COPIED', count($cid)));
				}
				else
				{
					$this->setMessage($model->getError(), 'error');
				}
			}
			catch (Exception $e)
			{
				$this->setMessage($e->getMessage(), 'error');
			}
		}

		$extension = $this->input->get('extension');
		$extensionURL = ($extension) ? '&extension=' . $extension : '';

		// Set redirect
		$this->setRedirect($this->getRedirectToListRoute($extensionURL));
	}
}
