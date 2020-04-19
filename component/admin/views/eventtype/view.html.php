<?php
/**
 * @package     Tracks
 * @subpackage  Admin
 * @copyright   Tracks (C) 2008-2015 Julien Vonthron. All rights reserved.
 * @license     GNU General Public License version 2 or later
 */

defined('_JEXEC') or die();

/**
 * HTML View class for Tracks eventtype edit
 *
 * @package     Tracks
 * @subpackage  Admin
 * @since       3.0
 */
class TracksViewEventtype extends TrackslibViewAdmin
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

		return JText::_('COM_TRACKS_PAGETITLE_EDIT_EVENTTYPE') . $subTitle;
	}

	/**
	 * Get the toolbar to render.
	 *
	 * @return  RToolbar
	 */
	public function getToolbar()
	{
		$group = new RToolbarButtonGroup;

		$save = RToolbarBuilder::createSaveButton('eventtype.apply');
		$saveAndClose = RToolbarBuilder::createSaveAndCloseButton('eventtype.save');
		$saveAndNew = RToolbarBuilder::createSaveAndNewButton('eventtype.save2new');
		$save2Copy = RToolbarBuilder::createSaveAsCopyButton('eventtype.save2copy');

		$group->addButton($save)
			->addButton($saveAndClose)
			->addButton($saveAndNew)
			->addButton($save2Copy);

		if (empty($this->item->id))
		{
			$cancel = RToolbarBuilder::createCancelButton('eventtype.cancel');
		}
		else
		{
			$cancel = RToolbarBuilder::createCloseButton('eventtype.cancel');
		}

		$group->addButton($cancel);

		$this->toolbar = new RToolbar;
		$this->toolbar->addGroup($group);

		return parent::getToolbar();
	}
}
