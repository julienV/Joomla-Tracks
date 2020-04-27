<?php
/**
 * @package     Tracks
 * @subpackage  Library
 * @copyright   Tracks (C) 2008-2015 Julien Vonthron. All rights reserved.
 * @license     GNU General Public License version 2 or later
 */

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
		$user = JFactory::getUser();
		$model = $this->getModel();

		$this->form = $this->get('Form');
		$this->item = $this->get('Item');
		$this->user = $user;
		$this->userIndividual = $this->get('UserIndividual');
		$this->canEdit = $model->canEdit($this->item);

		if (!$user->id || !$this->canEdit)
		{
			JFactory::getApplication()->redirect('index.php', 'not allowed !');
		}

		$attribs['class'] = "pic";

		if ($this->item->picture != '')
		{
			$this->item->picture = JHTML::image(JURI::root() . $this->item->picture, $this->item->first_name . ' ' . $this->item->last_name, $attribs);
		}

		if ($this->item->picture_small != '')
		{
			$this->item->picture_small = JHTML::image(JURI::root() . $this->item->picture_small, $this->item->first_name . ' ' . $this->item->last_name, $attribs);
		}

		$this->title = $this->item->id ?
			JText::_('COM_TRACKS_PAGETITLE_EDIT_INDIVIDUAL') :
			JText::_('COM_TRACKS_PAGETITLE_CREATE_INDIVIDUAL');

		// Display the template
		parent::display($tpl);
	}
}
