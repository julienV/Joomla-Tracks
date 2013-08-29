<?php
/**
 * @version        $Id: view.html.php 110 2008-05-26 16:47:13Z julienv $
 * @package        JoomlaTracks
 * @copyright      Copyright (C) 2008 Julien Vonthron. All rights reserved.
 * @license        GNU/GPL, see LICENSE.php
 *                 Joomla Tracks is free software. This version may have been modified pursuant
 *                 to the GNU General Public License, and as distributed it includes or
 *                 is derivative of works licensed under the GNU General Public License or
 *                 other free or open source software licenses.
 *                 See COPYRIGHT.php for copyright notices and details.
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();

jimport('joomla.application.component.view');
jimport('joomla.html.pane');

/**
 * HTML View class for the Tracks component
 *
 * @static
 * @package        Tracks
 * @since          0.1
 */
class TracksViewMenu extends FOFViewHtml
{
	function display($tpl = null)
	{
		$app = JFactory::getApplication();
		$option = JRequest::getCmd('option');
		$document = JFactory::getDocument();
		$uri = JURI::getInstance();

		if ($this->getLayout() == 'end')
		{
			return $this->_displayEnd();
		}

		$project = $this->get('Data');
		$view = JRequest::getCmd('view');
		$state = $this->get('state');
		$document->addScript(JUri::root() . 'media/com_tracks/js/menu.js');

		$lists = array();
		$options = array(JHTML::_('select.option', 0, JText::_('COM_TRACKS_SELECT_A_PROJECT')));
		$moreopts = $this->get('ProjectsOptions');
		if ($moreopts)
		{
			$options = array_merge($options, $moreopts);
		}
		$this->lists->set('project', JHTML::_('select.genericlist', $options, 'glproject', 'class="inputbox"', 'value', 'text', $state->get('project')));

		$this->assignRef('project', $project);
		$this->assign('referer', $uri->toString());

		parent::display('tablepane');
	}

	function _displayEnd($tpl = null)
	{
		parent::display('tablepane');
	}

	/**
	 * Executes before rendering the page for the Add task.
	 *
	 * @param   string  $tpl  Subtemplate to use
	 *
	 * @return  boolean  Return true to allow rendering of the page
	 */
	protected function onAdd($tpl = null)
	{
		return true;
	}
}
