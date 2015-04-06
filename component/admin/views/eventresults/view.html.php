<?php
/**
 * @package     Tracks
 * @subpackage  Admin
 * @copyright   Tracks (C) 2008-2015 Julien Vonthron. All rights reserved.
 * @license     GNU General Public License version 2 or later
 */

defined('_JEXEC') or die('Restricted access');

/**
 * HTML View class for Tracks event results
 *
 * @package     Tracks
 * @subpackage  Admin
 * @since       3.0
 */
class TracksViewEventresults extends TrackslibViewAdmin
{
	/**
	 * Layout used to render the component
	 *
	 * @var  string
	 */
	protected $componentLayout = 'component.adminproject';

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

		$this->items = $this->get('Items');
		$this->pagination = $this->get('Pagination');
		$this->filterForm = $this->get('Form');
		$this->activeFilters = $this->get('ActiveFilters');
		$this->state = $this->get('State');
		$this->breadcrumbs = $this->get('Breadcrumbs');

		// Ordering
		$this->ordering = array();

		if ($this->items)
		{
			foreach ($this->items as &$item)
			{
				$this->ordering[0][] = $item->id;
			}
		}

		// Edit permission
		$this->canEdit = false;

		if ($user->authorise('core.edit', 'com_tracks'))
		{
			$this->canEdit = true;
		}

		// Edit state permission
		$this->canEditState = false;

		if ($user->authorise('core.edit.state', 'com_tracks'))
		{
			$this->canEditState = true;
		}

		parent::display($tpl);
	}

	/**
	 * Get the page title
	 *
	 * @return  string  The title to display
	 *
	 * @since   0.9.1
	 */
	public function getTitle()
	{
		return JText::_('COM_TRACKS_TITLE_EVENTRESULTS');
	}

	/**
	 * Get the tool-bar to render.
	 *
	 * @return  RToolbar
	 */
	public function getToolbar()
	{
		$user = JFactory::getUser();

		$firstGroup = new RToolbarButtonGroup;
		$secondGroup = new RToolbarButtonGroup;
		$thirdGroup = new RToolbarButtonGroup;
		$fourthGroup = new RToolbarButtonGroup;

		if ($user->authorise('core.create', 'com_tracks'))
		{
			$new = RToolbarBuilder::createNewButton('eventresult.add');
			$firstGroup->addButton($new);

			$addall = RToolbarBuilder::createStandardButton('eventresults.addall', 'COM_TRACKS_ADD_ALL', '', 'icon-plus', false);
			$firstGroup->addButton($addall);
		}

		if ($user->authorise('core.edit', 'com_tracks'))
		{
			$edit = RToolbarBuilder::createEditButton('eventresult.edit');
			$secondGroup->addButton($edit);
		}

		if ($user->authorise('core.delete', 'com_tracks'))
		{
			$delete = RToolbarBuilder::createDeleteButton('eventresults.delete');
			$fourthGroup->addButton($delete);
		}

		$toolbar = new RToolbar;
		$toolbar->addGroup($firstGroup)->addGroup($secondGroup)->addGroup($thirdGroup)->addGroup($fourthGroup);

		return $toolbar;
	}
}
