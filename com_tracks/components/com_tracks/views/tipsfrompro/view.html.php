<?php
/**
* @version    $Id: view.html.php 135 2008-06-08 21:50:12Z julienv $ 
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
 * @since 0.1
 */
class TracksFrontViewTipsfrompro extends JView
{
  function display($tpl = null)
  {
    $mainframe = &JFactory::getApplication();
    $option = JRequest::getCmd('option');
    
    $data = $this->get('items');
    $pagination = $this->get('pagination');
            
    $viewparams = $mainframe->getParams( 'com_tracks' );
    
    $breadcrumbs =& $mainframe->getPathWay();
    $breadcrumbs->addItem( JText::_('COM_TRACKS_TIPSFROMPRO_view_title' ) );
    
    $title = JText::_('COM_TRACKS_TIPSFROMPRO_view_title');

    JHTML::_('behavior.framework');
    
    $document =& JFactory::getDocument();
    $document->setTitle($title);
    $document->addScript('components/com_tracks/assets/js/tipsfrompro.js');
    JText::script('COM_TRACKS_TIPSFROMPRO_DELETE_CONFIRM');

    $this->assignRef( 'params',     $viewparams );
    $this->assignRef( 'data',	      $data );
    $this->assignRef( 'pagination',	$pagination );
    $this->assignRef( 'user',	JFactory::getUser() );
    $this->assign( 'title',	$title );

    parent::display($tpl);
  }
}