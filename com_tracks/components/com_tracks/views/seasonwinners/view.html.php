<?php
/**
* @version    $Id: view.html.php 43 2008-02-24 23:47:38Z julienv $ 
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
 * @static
 * @package		Tracks
 * @since 1.0
 */
class TracksFrontViewSeasonWinners extends JView
{
    function display($tpl = null)
    {
    	$mainframe = &JFactory::getApplication();    	
    	          
    	$model =& $this->getModel();
    	$model->setSeasonId(JRequest::getVar( 's', 0, '', 'int' ));
    	$params =& $mainframe->getParams('com_tracks');
    	
    	$season  = $this->get('Season');
    	$winners = $this->get('Winners');

    	$document =& JFactory::getDocument();
    	$document->setTitle( $season->name );

    	$breadcrumbs =& $mainframe->getPathWay();
    	$title = JText::sprintf('COM_TRACKS_SEASON_WINNERS_S', $season->name);
    	$breadcrumbs->addItem( $season->name, 'index.php?option=com_tracks&view=seasonwinnners&s=' . $season->slug );

    	$this->assignRef( 'season',    $season );
    	$this->assignRef( 'winners',   $winners );
    	$this->assignRef( 'params',    $params );
    	$this->assignRef( 'title',     $title );

    	parent::display($tpl);
    }
}
?>