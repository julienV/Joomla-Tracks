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
class TracksViewIndividuals extends RViewSite
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
		$params = $mainframe->getParams('com_tracks');

		$model = $this->getModel();
		$rows = $model->getData();

		$document = JFactory::getDocument();
		$document->setTitle(JText::_('COM_TRACKS_All_Individuals'));

		$breadcrumbs = $mainframe->getPathWay();
		$breadcrumbs->addItem(
			JText::_('COM_TRACKS_All_Individuals'),
			TrackslibHelperRoute::getIndividualsRoute()
		);

		$this->assignRef('rows', $rows);
		$this->assignRef('params', $params);

		parent::display($tpl);
	}

	/**
	 * returns first letter of word
	 *
	 * @param   string  $word       a word
	 * @param   bool    $uppercase  return uppercase ?
	 *
	 * @return string
	 */
	public function firstLetter($word, $uppercase = true)
	{
		$first = mb_substr($word, 0, 1);

		return $uppercase ? mb_convert_case($first, 1) : $first;
	}
}
