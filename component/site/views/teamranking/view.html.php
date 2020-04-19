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
class TracksViewTeamRanking extends RViewSite
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

		$model = $this->getModel();
		$rankings = $model->getTeamRankings($project_id);
		$project = $model->getProject($project_id);
		$projectparams = $model->getParams($project->id);
		$params = $mainframe->getParams('com_tracks');

		$breadcrumbs = $mainframe->getPathWay();
		$breadcrumbs->addItem($project->name . ' ' . JText::_('COM_TRACKS_Team_Rankings'), TrackslibHelperRoute::getTeamRankingRoute($project_id));

		$document = JFactory::getDocument();
		$document->setTitle($project->name . ' ' . JText::_('COM_TRACKS_Team_Rankings'));

		$this->assignRef('project', $project);
		$this->assignRef('rankings', $rankings);
		$this->assignRef('projectparams', $projectparams);
		$this->assignRef('params', $params);

		parent::display($tpl);
	}
}
