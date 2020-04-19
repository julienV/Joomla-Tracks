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
class TracksViewProjectresults extends RViewSite
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

		$project_id = $mainframe->input->getInt('p', 0);

		$data = $this->get('data');
		$rounds = $this->get('rounds');
		$project = $this->get('project');

		$params = $this->get('Params');

		$params->merge(JComponentHelper::getParams('com_tracks'));

		$title = JText::sprintf('COM_TRACKS_view_project_results', $project->name);

		$breadcrumbs = $mainframe->getPathWay();
		$breadcrumbs->addItem($title, TrackslibHelperRoute::getProjectResultRoute($project_id));

		$document = JFactory::getDocument();
		$document->setTitle($title);

		$this->assignRef('params', $params);
		$this->assignRef('project', $project);
		$this->assignRef('rows', $data);
		$this->assignRef('rounds', $rounds);
		$this->assign('title', $title);

		parent::display($tpl);
	}
}
