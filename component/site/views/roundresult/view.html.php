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
class TracksViewRoundResult extends RViewSite
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
		$params = $mainframe->getParams();
		$ordering = $params->def('subround_order', 0);
		$ordering = ($ordering) ? 'DESC' : 'ASC';
		$projectround_id = JRequest::getVar('id', 0, '', 'int');

		$model = $this->getModel();
		$subroundresults = $model->getSubrounds($projectround_id, 0, $ordering);
		$round = $model->getRound($projectround_id);
		$project = $model->getRoundProject($projectround_id);
		$projectparams = $model->getParams($project->id);

		$breadcrumbs = $mainframe->getPathWay();
		$breadcrumbs->addItem($round->name, TrackslibHelperRoute::getRoundResultRoute($projectround_id));

		$document = JFactory::getDocument();
		$document->setTitle($round->name . ' - ' . $project->name);

		$this->results = $subroundresults;
		$this->round = $round;
		$this->project = $project;
		$this->projectround = TrackslibEntityProjectround::load($projectround_id);
		$this->projectparams = $projectparams;
		$this->params = $params;

		parent::display($tpl);
	}
}
