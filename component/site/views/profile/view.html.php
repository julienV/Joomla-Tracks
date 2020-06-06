<?php
/**
 * @package     Tracks
 * @subpackage  Library
 * @copyright   Tracks (C) 2008-2015 Julien Vonthron. All rights reserved.
 * @license     GNU General Public License version 2 or later
 */

use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;

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
		$user = JFactory::getUser();

		if (!$user->id)
		{
			print Text::_('COM_TRACKS_You_must_register_to_access_this_page');

			return;
		}

		return $this->displayForm($tpl);
	}

	/**
	 * Display form
	 *
	 * @param   string  $tpl  tpl
	 *
	 * @return void
	 */
	protected function displayForm($tpl)
	{
		$this->userIndividual = $this->get('UserIndividual');

		Factory::getApplication()->redirect(TrackslibHelperRoute::getEditIndividualRoute($this->userIndividual));
	}
}
