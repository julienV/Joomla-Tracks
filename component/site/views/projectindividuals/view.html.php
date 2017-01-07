<?php
/**
 * @version        $Id: view.html.php 135 2008-06-08 21:50:12Z julienv $
 * @package        JoomlaTracks
 * @copyright      Copyright (C) 2008 Julien Vonthron. All rights reserved.
 * @license        GNU/GPL, see LICENSE.php
 * Joomla Tracks is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * See COPYRIGHT.php for copyright notices and details.
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
class TracksViewProjectindividuals extends RViewSite
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
		$params = $mainframe->getParams();

		$model = $this->getModel();
		$individuals = $model->getIndividuals($project_id);
		$project = $model->getProject($project_id);
		$projectparams = $model->getParams($project_id);

		$breadcrumbs = $mainframe->getPathWay();
		$breadcrumbs->addItem($project->name . ' ' . JText::_('COM_TRACKS_Participants'), TrackslibHelperRoute::getParticipantsRoute($project_id));

		$document = JFactory::getDocument();
		$document->setTitle($project->name . ' ' . JText::_('COM_TRACKS_Participants'));

		$this->assignRef('params', $params);
		$this->assignRef('project', $project);
		$this->assignRef('projectparams', $projectparams);
		$this->assignRef('individuals', $individuals);

		parent::display($tpl);
	}
}

?>
