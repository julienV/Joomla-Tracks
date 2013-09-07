<?php
/**
 * @version    2.0
 * @package    JoomlaTracks
 * @copyright  Copyright (C) 2008 Julien Vonthron. All rights reserved.
 * @license    GNU/GPL, see LICENSE.php
 * Joomla Tracks is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * See COPYRIGHT.php for copyright notices and details.
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();

class TracksToolbar extends FOFToolbar
{
	protected function getMyViews()
	{
		$views = array(
			'projects',
			'competitions',
			'seasons',
			'teams',
			'individuals',
			'rounds',
		);
		return $views;
	}

	public function renderToolbar($view = null, $task = null, $input = null)
	{
		if ($view == 'menu')
		{
			return;
		}

		return parent::renderToolbar($view, $task, $input);
	}

	public function renderSubmenu()
	{
		return;
	}

	public function onProjectsBrowse()
	{
		// Set toolbar items for the page
		JToolBarHelper::title(JText::_('COM_TRACKS'), 'tracks');

		JToolBarHelper::addNewX();
		JToolBarHelper::editListX();
		JToolBarHelper::deleteList(JText::_('COM_TRACKS_DELETEPROJECTSCONFIRM'));
		JToolBarHelper::publishList();
		JToolBarHelper::unpublishList();
		JToolBarHelper::preferences('com_tracks', '600', '500');
		JToolBarHelper::help( 'screen.tracks', true );
	}

	public function onProjectindividualsBrowse()
	{
		JToolBarHelper::title(JText::_('COM_TRACKS') . ' &ndash; ' . JText::_('COM_TRACKS_Project_Participants' ), 'tracks');

		JToolBarHelper::back();
		JToolBarHelper::deleteList(JText::_('COM_TRACKS_DELETEPROJECTINDIVIDUALCONFIRM'));
		JToolBarHelper::editListX();
		JToolBarHelper::addNewX();
	}

	public function onIndividualsBrowse()
	{
		JToolBarHelper::title(JText::_('COM_TRACKS_Individuals'), 'tracks' );

		JToolBarHelper::deleteList();
		JToolBarHelper::editListX();
		JToolBarHelper::addNewX();
		JToolBarHelper::assign();
	}

	public function onIndividualsAssign()
	{
		JToolBarHelper::title(JText::_('COM_TRACKS_Individuals'), 'tracks' );

		JToolBarHelper::save('saveassign', 'Save');
		JToolBarHelper::back();
	}

	public function onTeamsBrowse()
	{
		JToolBarHelper::title(JText::_('COM_TRACKS') . ' &ndash; ' . JText::_('COM_TRACKS_TEAMS' ), 'tracks');

		JToolBarHelper::addNewX();
		JToolBarHelper::editListX();
		JToolBarHelper::deleteList();
	}

	public function onSubroundtypesBrowse()
	{
		JToolBarHelper::title(JText::_('COM_TRACKS') . ' &ndash; ' . JText::_('COM_TRACKS_SUBROUND_TYPES' ), 'tracks');

		JToolBarHelper::addNewX();
		JToolBarHelper::editListX();
		JToolBarHelper::deleteList();
	}

	public function onSubroundresultsBrowse()
	{
		JToolBarHelper::title(JText::_('COM_TRACKS') . ' &ndash; ' . JText::_('COM_TRACKS_RESULTS' ), 'tracks');

		JToolBarHelper::addNewX();
		JToolBarHelper::editListX();
		JToolBarHelper::save( 'saveranks', 'Save' );
		JToolBarHelper::custom('addall', 'default', 'default', JText::_('COM_TRACKS_Add_all'), false);
		JToolBarHelper::deleteList();
		JToolBarHelper::custom('back', 'back.png', 'back.png', JText::_('COM_TRACKS_back'), false);
	}
}
