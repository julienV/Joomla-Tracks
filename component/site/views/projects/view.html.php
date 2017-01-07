<?php
/**
 * @package    Tracks.Site
 * @copyright  Tracks (C) 2008-2015 Julien Vonthron. All rights reserved.
 * @license    GNU General Public License version 2 or later
 */

// No direct access
defined('_JEXEC') or die('Restricted access');

jimport( 'joomla.application.component.view');

/**
 * HTML View class for the Tracks component
 *
 * @package  Tracks.Site
 * @since    0.1
 */
class TracksViewProjects extends RViewSite
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
		$model = $this->getModel();
		$projects = $model->getProjects();

		$this->assignRef('projects', $projects);

		parent::display($tpl);
	}
}
