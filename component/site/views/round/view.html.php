<?php
/**
* @version    $Id: view.html.php 77 2008-04-30 03:32:25Z julienv $
* @package    JoomlaTracks
* @copyright	Copyright (C) 2008 Julien Vonthron. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* Joomla Tracks is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();

jimport( 'joomla.application.component.view');

/**
 * HTML View class for the Tracks component
 *
 * @package		Tracks
 * @since 0.1
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
		$params = $mainframe->getParams();

		$round_id = $mainframe->input->getInt('id', 0);

		$model = $this->getModel();
		$round = $model->getRound( $round_id );

		$breadcrumbs = $mainframe->getPathWay();
		$breadcrumbs->addItem( $round->name,
				'index.php?view=round&r=' . $round_id );

		$document = JFactory::getDocument();
		$document->setTitle( $round->name );

		// parse description with content plugins
		$round->description = JHTML::_('content.prepare', $round->description);

		$picture = TrackslibHelperImage::modalimage($round->picture, $round->name, 150);

		$this->assignRef( 'round',    $round );
		$this->assignRef( 'params',   $params );
		$this->assignRef( 'picture',  $picture );

		parent::display($tpl);
	}
}
