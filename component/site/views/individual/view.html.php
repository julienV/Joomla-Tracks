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

		$attribs['class'] = "pic";

		if ($data->picture != '')
		{
			$data->picture = JHTML::image(JURI::root() . $data->picture, $data->first_name . ' ' . $data->last_name, $attribs);
		}
		else
		{
			$data->picture = JHTML::image(JURI::root() . 'media/com_tracks/images/misc/tnnophoto.jpg', $data->first_name . ' ' . $data->last_name, $attribs);
		}

		if ($data->picture_small != '')
		{
			$data->picture_small = JHTML::image(JURI::root() . $data->picture_small, $data->first_name . ' ' . $data->last_name, $attribs);
		}
		else
		{
			$data->picture_small = JHTML::image(JURI::root() . 'media/com_tracks/images/misc/tnnophoto.jpg', $data->first_name . ' ' . $data->last_name, $attribs);
		}

		$raceResults = $this->sortResultsByProject($this->get('RaceResults'));

		$show_edit_link = ($user->id && $user->id == $data->user_id) || $user->authorise('core.manage', 'com_tracks');

		$breadcrumbs = $mainframe->getPathWay();
		$breadcrumbs->addItem($data->first_name . ' ' . $data->last_name, TrackslibHelperRoute::getEditIndividualRoute($data->id));

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

		$this->title = $this->item->id ?
			JText::_('COM_TRACKS_PAGETITLE_EDIT_INDIVIDUAL') :
			JText::_('COM_TRACKS_PAGETITLE_CREATE_INDIVIDUAL');

		// Display the template
		parent::display($tpl);
	}
}
