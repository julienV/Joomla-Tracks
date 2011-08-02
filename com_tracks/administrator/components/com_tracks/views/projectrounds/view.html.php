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
class TracksViewProjectrounds extends TracksView
{
	function display($tpl = null)
	{
		$mainframe = &JFactory::getApplication();
		$option = JRequest::getCmd('option');

	  if($this->getLayout() == 'copy_form') 
    {
      $this->_displayCopy($tpl);
      return;
    }
    
		// Set toolbar items for the page
		JToolBarHelper::title(   JText::_('COM_TRACKS_Project_Rounds' ), 'generic.png' );
		JToolBarHelper::publish();
		JToolBarHelper::unpublish();
		JToolBarHelper::editListX();
		JToolBarHelper::addNewX();
    JToolBarHelper::deleteList(JText::_('COM_TRACKS_DELETEROUNDSCONFIRM'));
    JToolBarHelper::customX( 'copy', 'copy.png', 'copy_f2.png', JText::_('COM_TRACKS_COPY'), true );
    JToolBarHelper::help( 'screen.tracks', true );
        
		$db		=& JFactory::getDBO();
		$uri	=& JFactory::getURI();

		$filter_state		= $mainframe->getUserStateFromRequest( $option.'.viewprojectrounds.filter_state',		'filter_state',		'',				'word' );
		$filter_order		= $mainframe->getUserStateFromRequest( $option.'.viewprojectrounds.filter_order',		'filter_order',		'obj.ordering',	'cmd' );
		$filter_order_Dir	= $mainframe->getUserStateFromRequest( $option.'.viewprojectrounds.filter_order_Dir',	'filter_order_Dir',	'',				'word' );
		$search				= $mainframe->getUserStateFromRequest( $option.'.viewprojectrounds.search',			'search',			'',				'string' );
		$search				= JString::strtolower( $search );

		// Get data from the model
		//$model	=& $this->getModel( );
		//print_r($model);
		$items		= & $this->get( 'Data' );
		$total		= & $this->get( 'Total' );
		$pagination = & $this->get( 'Pagination' );

		// build list of categories
		//$javascript 	= 'onchange="document.adminForm.submit();"';
		
		// state filter
		$lists['state']	= JHTML::_('grid.state',  $filter_state );

		// table ordering
		$lists['order_Dir'] = $filter_order_Dir;
		$lists['order'] = $filter_order;

		// search filter
		$lists['search']= $search;

		$this->assignRef('user',		JFactory::getUser());
		$this->assignRef('lists',		$lists);
		$this->assignRef('items',		$items);
		$this->assignRef('pagination',	$pagination);
		$this->assignRef('request_url',	$uri->toString());

		parent::display($tpl);
	}
	

  function _displayCopy($tpl = null)
  {
    $mainframe = &JFactory::getApplication();
$option = JRequest::getCmd('option');
    
    // Set toolbar items for the page
    JToolBarHelper::title(   JText::_('COM_TRACKS_COPYROUNDS' ), 'generic.png' );
    JToolBarHelper::back();
    JToolBarHelper::save('savecopy', 'Copy');
    JToolBarHelper::cancel('cancelcopy');
    JToolBarHelper::help( 'screen.tracks', true );
    
    $db   =& JFactory::getDBO();
    $uri  =& JFactory::getURI();
    
    // Get data from the model
    // $model =& $this->getModel();
    // print_r($model);
    
    //build the html select list for projects
    $projects[] = JHTML::_('select.option',  '0', '- '. JText::_('COM_TRACKS_Select_a_project' ) .' -','value', 'text' );
    if ( $res = & $this->get('projectsListOptions') ) {
      $projects = array_merge( $projects, $res );
    }
    $lists['projects'] = JHTML::_('select.genericlist',  $projects, 'project_id', 'class="inputbox" size="1"', 'value', 'text');
    unset($projects);
    
    // get player names
    $rounds    = & $this->get( 'RoundsList' );
    
    $this->assignRef('user',    JFactory::getUser());
    $this->assignRef('rounds',   $rounds);
    $this->assignRef('lists',   $lists);
    $this->assignRef('request_url', $uri->toString());
    
    parent::display($tpl);
  }
}
?>
