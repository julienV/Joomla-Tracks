<?php
/**
* @version    $Id: view.html.php 91 2008-05-02 09:41:23Z julienv $ 
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

require_once (JPATH_COMPONENT.DS.'abstract'.DS.'tracksview.php');

/**
 * HTML View class for the Tracks component
 *
 * @static
 * @package		Tracks
 * @since 0.1
 */
class TracksViewProjectsettings extends TracksView
{
	function display($tpl = null)
	{
		$mainframe = JFactory::getApplication();
		$option = JRequest::getCmd('option');
    
		// Set toolbar items for the page
		JToolBarHelper::title(   JText::_('COM_TRACKS_PROJECTSETTINGS' ), 'generic.png' );
		JToolBarHelper::editListX();
    JToolBarHelper::deleteList(JText::_('COM_TRACKS_DELETESETTINGSCONFIRM'));
    JToolBarHelper::help( 'screen.tracks.projectsettings', true );
        
		$db		= JFactory::getDBO();
		$uri	= JFactory::getURI();
		
    $filter_order   = $mainframe->getUserStateFromRequest( $option.'.settings.filter_order',   'filter_order',   'obj.xml', 'cmd' );
    $filter_order_Dir = $mainframe->getUserStateFromRequest( $option.'.settings.filter_order_Dir', 'filter_order_Dir', '',       'word' );
		
		// Get data from the model
		//$model	= $this->getModel( );
		//print_r($model);
		$items		= $this->get( 'Data' );
		$total		= $this->get( 'Total' );
		$pagination = $this->get( 'Pagination' );

		// build list of categories
		//$javascript 	= 'onchange="document.adminForm.submit();"';
		
		// table ordering
		$lists['order_Dir'] = $filter_order_Dir;
		$lists['order'] = $filter_order;

		$this->assignRef('user',		JFactory::getUser());
		$this->assignRef('lists',		$lists);
		$this->assignRef('items',		$items);
		$this->assignRef('pagination',	$pagination);
		$this->assignRef('request_url',	$uri->toString());

		parent::display($tpl);
	}
}
?>
