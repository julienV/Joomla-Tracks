<?php
/**
 * @version    1.0
 * @package    JoomlaTracks
 * @copyright  Copyright (C) 2008 Julien Vonthron. All rights reserved.
 * @license    GNU/GPL, see LICENSE.php
 *             Joomla Tracks is free software. This version may have been modified pursuant
 *             to the GNU General Public License, and as distributed it includes or
 *             is derivative of works licensed under the GNU General Public License or
 *             other free or open source software licenses.
 *             See COPYRIGHT.php for copyright notices and details.
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();

/**
 * HTML View class for the Tracks component
 *
 * @static
 * @package  Tracks
 * @since    0.1
 */
class TracksViewProjectindividuals extends TracksMenuViewFOF
{
	function _displayAssign($tpl = null)
	{
		$mainframe = JFactory::getApplication();
		$option = JRequest::getCmd('option');

		// Set toolbar items for the page
		JToolBarHelper::title(JText::_('COM_TRACKS_Assign_Participants'), 'generic.png');
		JToolBarHelper::back();
		JToolBarHelper::save('saveassign', 'Save');
		JToolBarHelper::cancel('cancelassign');
		JToolBarHelper::help('screen.tracks', true);

		$db = JFactory::getDBO();
		$uri = JFactory::getURI();

		// Get data from the model
		// $model = $this->getModel();
		// print_r($model);

		//build the html select list for teams
		$teamoptions[] = JHTML::_('select.option', '0', '- ' . JText::_('COM_TRACKS_Select_a_team') . ' -', 'id', 'name');
		if ($res = $this->get('Teams'))
		{
			$teamoptions = array_merge($teamoptions, $res);
		}

		//build the html select list for projects
		$projects[] = JHTML::_('select.option', '0', '- ' . JText::_('COM_TRACKS_Select_a_project') . ' -', 'value', 'text');
		if ($res = $this->get('projectsListOptions'))
		{
			$projects = array_merge($projects, $res);
		}
		$lists['projects'] = JHTML::_('select.genericlist', $projects, 'project_id', 'class="inputbox" size="1"', 'value', 'text');
		unset($projects);

		// get player names
		$players = $this->get('assignList');

		$this->assignRef('user', JFactory::getUser());
		$this->assignRef('players', $players);
		$this->assignRef('teamoptions', $teamoptions);
		$this->assignRef('lists', $lists);
		$this->assignRef('request_url', $uri->toString());

		parent::display($tpl);
	}
}
