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
class TracksViewProject extends RViewSite
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

		$projectId = JFactory::getApplication()->input->getInt('p', 0);

		$model = $this->getModel();
		$results = $model->getResults($projectId);
		$project = $model->getProject($projectId);
		$params = $model->getParams();

		$document = JFactory::getDocument();
		$document->setTitle($project->name);

		$breadcrumbs = $mainframe->getPathWay();
		$breadcrumbs->addItem($project->name, TrackslibHelperRoute::getProjectRoute($project->id));

		$this->assignRef('results', $results);
		$this->assignRef('project', $project);
		$this->assignRef('params', $params);

		parent::display($tpl);
	}
}
