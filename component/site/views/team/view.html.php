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
			return $this->displayForm($tpl);
		}

		$mainframe = JFactory::getApplication();

		$model = $this->getModel();
		$data  = $model->getItem();

		// Parse description with content plugins
		$data->description = JHTML::_('content.prepare', $data->description);

		$breadcrumbs = $mainframe->getPathWay();
		$breadcrumbs->addItem($data->name, TrackslibHelperRoute::getTeamRoute($data->id));

		$document = JFactory::getDocument();
		$document->setTitle($data->name);

		$user = JFactory::getUser();
		$this->canEdit = $user->get('id') && ($user->authorise('core.manage', 'com_tracks') || $user->get('id') == $data->admin_id);

		$this->data = $data;

		parent::display($tpl);
	}

	/**
	 * Display form
	 *
	 * @param   string  $tpl  The name of the template file to parse; automatically searches through the template paths.
	 *
	 * @return  void
	 */
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
