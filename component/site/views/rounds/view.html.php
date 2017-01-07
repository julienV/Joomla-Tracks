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
 * @static
 * @package  Tracks
 * @since    0.1
 */
class TracksViewRounds extends RViewSite
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

		$model = $this->getModel();
		$rows = $model->getRounds();

		$document = JFactory::getDocument();
		$document->setTitle(JText::_('COM_TRACKS_All_rounds'));

		$breadcrumbs = $mainframe->getPathWay();
		$breadcrumbs->addItem(JText::_('COM_TRACKS_All_rounds'), TrackslibHelperRoute::getRoundsRoute());

		$this->assignRef('rows', $rows);

		parent::display($tpl);
	}
}
