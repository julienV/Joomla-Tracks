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
class TracksViewTeam extends RViewSite
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
		if ($this->getLayout() == 'edit')
		{
			$this->displayForm($tpl);

			return;
		}

		RHelperAsset::load('tracks.css');
		$mainframe = JFactory::getApplication();

		$id = JRequest::getVar('id', 0, '', 'int');

		$model = $this->getModel();
		$data = $model->getItem($id);

		$individuals = $this->get('Individuals');

		// Parse description with content plugins
		$data->description = JHTML::_('content.prepare', $data->description);

		$breadcrumbs = $mainframe->getPathWay();
		$breadcrumbs->addItem($data->name, TrackslibHelperRoute::getTeamRoute($id));

		$document = JFactory::getDocument();
		$document->setTitle($data->name);

		$user = JFactory::getUser();
		$this->canEdit = $user->get('id') && ($user->authorise('core.manage', 'com_tracks') || $user->get('id') == $data->admin_id);

		if ($data->picture)
		{
			$attribs['class'] = "pic";
			$data->picture = JHTML::image(JURI::root() . $data->picture, $data->name, $attribs);
		}

		$this->data = $data;
		$this->individuals = $individuals;

		parent::display($tpl);
	}

	protected function displayForm($tpl)
	{
		RHelperAsset::load('tracks.css');

		$user = JFactory::getUser();
		$model = $this->getModel();

		$this->form = $this->get('Form');
		$this->item = $this->get('Item');
		$this->user = $user;
		$this->canEdit = $user->get('id') && ($user->authorise('core.manage', 'com_tracks') || $user->get('id') == $this->item->admin_id);

		if (!$user->id || !$this->canEdit)
		{
			JFactory::getApplication()->redirect('index.php', 'not allowed !');
		}

		$attribs['class'] = "pic";

		if ($this->item->picture)
		{
			$this->item->picture = JHTML::image(JURI::root() . $this->item->picture, $this->item->name, $attribs);
		}
		else
		{
			$this->item->picture = JHTML::image(JURI::root() . 'media/com_tracks/images/misc/tnnophoto.jpg', $this->item->name, $attribs);
		}

		if ($this->item->picture_small)
		{
			$this->item->picture_small = JHTML::image(JURI::root() . $this->item->picture_small, $this->item->name, $attribs);
		}
		else
		{
			$this->item->picture_small = JHTML::image(JURI::root() . 'media/com_tracks/images/misc/tnnophoto.jpg', $this->item->name, $attribs);
		}

		if ($this->item->vehicle_picture)
		{
			$this->item->vehicle_picture = JHTML::image(JURI::root() . $this->item->vehicle_picture, $this->item->vehicle_picture, $attribs);
		}
		else
		{
			$this->item->vehicle_picture = JHTML::image(JURI::root() . 'media/com_tracks/images/misc/tnnophoto.jpg', '', $attribs);
		}

		$this->title = $this->item->id ?
			JText::_('COM_TRACKS_PAGETITLE_EDIT_TEAM') :
			JText::_('COM_TRACKS_PAGETITLE_CREATE_TEAM');

		// Display the template
		parent::display($tpl);
	}
}
