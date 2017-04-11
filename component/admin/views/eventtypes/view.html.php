<?php
/**
 * @package     Tracks
 * @subpackage  Admin
 * @copyright   Tracks (C) 2008-2015 Julien Vonthron. All rights reserved.
 * @license     GNU General Public License version 2 or later
 */

defined('_JEXEC') or die('Restricted access');

/**
 * HTML View class for Tracks eventtypes
 *
 * @package     Tracks
 * @subpackage  Admin
 * @since       3.0
 */
class TracksViewEventtypes extends TrackslibViewAdmin
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

		$this->items = $this->get('Items');
		$this->pagination = $this->get('Pagination');
		$this->filterForm = $this->get('Form');
		$this->activeFilters = $this->get('ActiveFilters');
		$this->state = $this->get('State');

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
		return JText::_('COM_TRACKS_EVENTTYPES');
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
			$new = RToolbarBuilder::createNewButton('eventtype.add');
			$firstGroup->addButton($new);
		}

		if ($user->authorise('core.edit', 'com_tracks'))
		{
			$edit = RToolbarBuilder::createEditButton('eventtype.edit');
			$secondGroup->addButton($edit);
		}

		if ($user->authorise('core.delete', 'com_tracks'))
		{
			$delete = RToolbarBuilder::createDeleteButton('eventtypes.delete');
			$fourthGroup->addButton($delete);
		}

		$toolbar = new RToolbar;
		$toolbar->addGroup($firstGroup)->addGroup($secondGroup)->addGroup($thirdGroup)->addGroup($fourthGroup);

		return $toolbar;
	}

	/**
	 * returns toggle html
	 *
	 * @param   object  $row      item data
	 * @param   int     $i        row number
	 * @param   bool    $allowed  is user allowed to perform action
	 *
	 * @return string html
	 */
	public static function enableStatsToggle($row, $i, $allowed = true)
	{
		$states = array(
			1 => array('disableStats', '', 'COM_TRACKS_ENABLED', '', false, 'ok', 'ok'),
			0 => array('enableStats', '', 'COM_TRACKS_DISABLED', '', false, 'remove', 'remove'),
		);

		return JHtml::_('rgrid.state', $states, $row->enable_stats, $i, 'eventtypes.', $allowed, true);
	}
}
