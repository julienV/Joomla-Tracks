<?php
/**
* @version    $Id$ 
* @package    JoomlaTracks
* @copyright	Copyright (C) 2008 Julien Vonthron. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* Joomla Tracks is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/

defined('JPATH_PLATFORM') or die;

jimport('joomla.application.component.view');

/**
 * View class for a list of sponsors.
 *
 * @package		Joomla.Administrator
 * @subpackage	com_tracks
 * @since		1.7
 */
class TracksViewSponsors extends JView
{
	protected $items;
	protected $pagination;
	protected $state;

	/**
	 * Display the view
	 */
	public function display($tpl = null)
	{
		$this->state		= $this->get('State');
		$this->items		= $this->get('Items');
		$this->pagination	= $this->get('Pagination');

		// Check for errors.
		if (count($errors = $this->get('Errors'))) {
			JError::raiseError(500, implode("\n", $errors));
			return false;
		}

		$this->addToolbar();
		parent::display($tpl);
	}

	/**
	 * Add the page title and toolbar.
	 *
	 * @since	1.6
	 */
	protected function addToolbar()
	{
		require_once JPATH_COMPONENT.'/helper.php';

		$state	= $this->get('State');
		$canDo	=TracksHelper::getActions();
		$user	= JFactory::getUser();

		JToolBarHelper::title(JText::_('COM_TRACKS_SPONSORS'), 'sponsors.png');
		if ($canDo->get('core.manage')) {
			JToolBarHelper::addNew('sponsor.add');
			JToolBarHelper::editList('sponsor.edit');
		}
		if ($canDo->get('core.manage')) {

			JToolBarHelper::divider();
			JToolBarHelper::publish('sponsors.publish', 'JTOOLBAR_PUBLISH', true);
			JToolBarHelper::unpublish('sponsors.unpublish', 'JTOOLBAR_UNPUBLISH', true);


			JToolBarHelper::divider();
			JToolBarHelper::archiveList('sponsors.archive');
			JToolBarHelper::checkin('sponsors.checkin');
		}
		if ($state->get('filter.state') == -2 && $canDo->get('core.manage')) {
			JToolBarHelper::deleteList('', 'sponsor.delete', 'JTOOLBAR_EMPTY_TRASH');
			JToolBarHelper::divider();
		} elseif ($canDo->get('core.manage')) {
			JToolBarHelper::trash('sponsors.trash');
			JToolBarHelper::divider();
		}
		if ($canDo->get('core.manage')) {
			JToolBarHelper::preferences('com_tracks');
			JToolBarHelper::divider();
		}
	}
}
