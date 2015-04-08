<?php
/**
 * @version        $Id: view.html.php 77 2008-04-30 03:32:25Z julienv $
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

/**
 * HTML View class for the Tracks component
 *
 * @static
 * @package        Tracks
 * @since          0.1
 */
class TracksViewIndividual extends RViewSite
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
		RHelperAsset::load('tracks.css');
		$mainframe = JFactory::getApplication();

		if ($this->getLayout() == 'edit')
		{
			$this->displayForm($tpl);

			return;
		}

		$user = JFactory::getUser();
		$params = $mainframe->getParams('com_tracks');
		$document = JFactory::getDocument();

		$dispatcher = JDispatcher::getInstance();
		JPluginHelper::importPlugin('tracks');
		JPluginHelper::importPlugin('content');

		$data = $this->get('Item');
		$raceResults = $this->sortResultsByProject($this->get('RaceResults'));

		$show_edit_link = ($user->id && $user->id == $data->user_id) || $user->authorise('core.manage', 'com_tracks');

		$breadcrumbs = $mainframe->getPathWay();
		$breadcrumbs->addItem($data->first_name . ' ' . $data->last_name,
			'index.php?option=com_tracks&view=individual&i=' . $data->id);

		$document->setTitle($data->first_name . ' ' . $data->last_name);

		// Allow content plugins
		$data->description = JHTML::_('content.prepare', $data->description);

		$this->assignRef('data', $data);
		$this->assignRef('show_edit_link', $show_edit_link);
		$this->assignRef('results', $raceResults);
		$this->assignRef('params', $params);
		$this->assignRef('dispatcher', $dispatcher);

		parent::display($tpl);
	}

	function sortResultsByProject($results)
	{
		$projects = array();
		if (!count($results)) return $projects;

		foreach ($results AS $r)
		{
			@$projects[$r->project_id][] = $r;
		}
		return $projects;
	}


	protected function displayForm($tpl)
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
}
