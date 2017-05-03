<?php
/**
 * @package     Tracks
 * @subpackage  Admin
 * @copyright   Tracks (C) 2008-2015 Julien Vonthron. All rights reserved.
 * @license     GNU General Public License version 2 or later
 */

defined('_JEXEC') or die();

/**
 * HTML View class for Tracks team edit
 *
 * @package     Tracks
 * @subpackage  Admin
 * @since       3.0
 */
class TracksViewTeam extends TrackslibViewAdmin
{
	/**
	 * @var  boolean
	 */
	protected $displaySidebar = false;

	/**
	 * Display the edit page
	 *
	 * @param   string  $tpl  The template file to use
	 *
	 * @return   string
	 */
	public function display($tpl = null)
	{
		$user = JFactory::getUser();

		$this->form = $this->get('Form');
		$this->item = $this->get('Item');

		$this->canConfig = false;

		if ($user->authorise('core.admin', 'com_tracks'))
		{
			$this->canConfig = true;
		}

		// Display the template
		parent::display($tpl);
	}

	/**
	 * Get the view title.
	 *
	 * @return  string  The view title.
	 */
	public function getTitle()
	{
		$subTitle = ' <small>' . JText::_('COM_TRACKS_NEW') . '</small>';

		if ($this->item->id)
		{
			$subTitle = ' <small>' . JText::_('COM_TRACKS_EDIT') . '</small>';
		}

		return JText::_('COM_TRACKS_PAGETITLE_EDIT_TEAM') . $subTitle;
	}

	/**
	 * Get the toolbar to render.
	 *
	 * @return  RToolbar
	 */
	public function getToolbar()
	{
		$group = new RToolbarButtonGroup;

		$save = RToolbarBuilder::createSaveButton('team.apply');
		$saveAndClose = RToolbarBuilder::createSaveAndCloseButton('team.save');
		$saveAndNew = RToolbarBuilder::createSaveAndNewButton('team.save2new');
		$save2Copy = RToolbarBuilder::createSaveAsCopyButton('team.save2copy');

		$group->addButton($save)
			->addButton($saveAndClose)
			->addButton($saveAndNew)
			->addButton($save2Copy);

		if (empty($this->item->id))
		{
			$cancel = RToolbarBuilder::createCancelButton('team.cancel');
		}
		else
		{
			$cancel = RToolbarBuilder::createCloseButton('team.cancel');
		}

		$group->addButton($cancel);

		$this->toolbar = new RToolbar;
		$this->toolbar->addGroup($group);

		return parent::getToolbar();
	}
}
