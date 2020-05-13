<?php
/**
 * @package     Tracks
 * @subpackage  Library
 * @copyright   Tracks (C) 2008-2015 Julien Vonthron. All rights reserved.
 * @license     GNU General Public License version 2 or later
 */

defined('_JEXEC') or die;

/**
 * HTML View class for the Tracks component
 *
 * @package  Tracks
 * @since    0.1
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

	/**
	 * Sort results
	 *
	 * @param   array  $results  results
	 *
	 * @return array
	 */
	public function sortResultsByProject($results)
	{
		$projects = array();

		if (empty($results))
		{
			return $projects;
		}

		foreach ($results AS $r)
		{
			$projects[$r->project_id][] = $r;
		}

		return $projects;
	}

	/**
	 * Display form
	 *
	 * @param   string  $tpl  tpl
	 *
	 * @return void
	 */
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

		$attribs['class'] = "pic";

		if ($this->item->picture != '')
		{
			$this->item->picture = JHTML::image(JURI::root() . $this->item->picture, $this->item->first_name . ' ' . $this->item->last_name, $attribs);
		}
		else
		{
			$this->item->picture = JHTML::image(JURI::root() . 'media/com_tracks/images/misc/tnnophoto.jpg', $this->item->first_name . ' ' . $this->item->last_name, $attribs);
		}

		if ($this->item->picture_small != '')
		{
			$this->item->picture_small = JHTML::image(JURI::root() . $this->item->picture_small, $this->item->first_name . ' ' . $this->item->last_name, $attribs);
		}
		else
		{
			$this->item->picture_small = JHTML::image(JURI::root() . 'media/com_tracks/images/misc/tnnophoto.jpg', $this->item->first_name . ' ' . $this->item->last_name, $attribs);
		}

		$this->title = $this->item->id ?
			JText::_('COM_TRACKS_PAGETITLE_EDIT_INDIVIDUAL') :
			JText::_('COM_TRACKS_PAGETITLE_CREATE_INDIVIDUAL');

		// Display the template
		parent::display($tpl);
	}
}
