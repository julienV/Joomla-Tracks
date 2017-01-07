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
class TracksViewRound extends RViewSite
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
		$params    = $mainframe->getParams();

		$round_id = $mainframe->input->getInt('id', 0);

		$model = $this->getModel();
		$round = $model->getRound($round_id);

		$breadcrumbs = $mainframe->getPathWay();
		$breadcrumbs->addItem($round->name, 'index.php?view=round&r=' . $round_id);

		$document = JFactory::getDocument();
		$document->setTitle($round->name);

		// Parse description with content plugins
		$round->description = JHTML::_('content.prepare', $round->description);

		$picture = TrackslibHelperImage::modalimage($round->picture, $round->name, 150);

		$this->assignRef('round', $round);
		$this->assignRef('params', $params);
		$this->assignRef('picture', $picture);

		parent::display($tpl);
	}
}
