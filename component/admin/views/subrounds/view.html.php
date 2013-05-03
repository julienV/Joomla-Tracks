<?php
/**
* @version    0.2 $Id$ 
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
class TracksViewSubrounds extends TracksView
{
	function display($tpl = null)
	{
		$mainframe = JFactory::getApplication();
		$option = JRequest::getCmd('option');

		$round = $this->get('Round');
		
		// Set toolbar items for the page
		JToolBarHelper::title( JText::sprintf('COM_TRACKS_Subrounds_for', $round->roundname) , 'generic.png' );
    JToolBarHelper::custom('back', 'back.png', 'back.png', JText::_('COM_TRACKS_back'), false); 
		JToolBarHelper::save( 'saveranks', 'Save' );
		JToolBarHelper::editListX();
		JToolBarHelper::addNewX();
    JToolBarHelper::DeleteList(JText::_('COM_TRACKS_DELETESUBROUNDSCONFIRM'));
    JToolBarHelper::publish();
    JToolBarHelper::unpublish();
		JToolBarHelper::help( 'screen.tracks', true );
        
		$db		= JFactory::getDBO();
		$uri	= JFactory::getURI();

		$filter_order		= $mainframe->getUserStateFromRequest( $option.'.viewsubrounds.filter_order',		'filter_order',		'obj.ordering',	'cmd' );
		$filter_order_Dir	= $mainframe->getUserStateFromRequest( $option.'.viewsubrounds.filter_order_Dir',	'filter_order_Dir',	'',				'word' );

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
		$this->assignRef('projectround_id',	JRequest::getVar('prid',0));
		$this->assignRef('pagination',	$pagination);
		$this->assignRef('request_url',	$uri->toString());

		parent::display($tpl);
	}
}
?>
