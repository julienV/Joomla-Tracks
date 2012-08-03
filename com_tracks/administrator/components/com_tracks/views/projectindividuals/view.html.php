<?php
/**
* @version    $Id: view.html.php 140 2008-06-10 16:47:22Z julienv $ 
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
class TracksViewProjectindividuals extends TracksView
{
	function display($tpl = null)
	{
		$mainframe = JFactory::getApplication();
		$option = JRequest::getCmd('option');		
		
    if($this->getLayout() == 'assign') 
    {
			$this->_displayAssign($tpl);
			return;
		}
    $project_id = $mainframe->getUserState( $option.'project' );
		
		$document = JFactory::getDocument();
		$document->addScript(JURI::base() . 'components/com_tracks/assets/js/autocompleter/1_2/Autocompleter.js');
		$document->addScript(JURI::base() . 'components/com_tracks/assets/js/autocompleter/1_2/Autocompleter.Local.js');
		$document->addScript(JURI::base() . 'components/com_tracks/assets/js/autocompleter/1_2/Autocompleter.Request.js');
		$document->addScript(JURI::base() . 'components/com_tracks/assets/js/autocompleter/1_2/Observer.js');
		$document->addScript(JURI::base() . 'components/com_tracks/assets/js/quickadd1_2.js');
		$document->addStyleSheet(JURI::base() . 'components/com_tracks/assets/css/Autocompleter1_2.css');
		
		// Set toolbar items for the page
		JToolBarHelper::title(   JText::_('COM_TRACKS_Project_Participants' ), 'generic.png' );
		JToolBarHelper::back();
		JToolBarHelper::deleteList(JText::_('COM_TRACKS_DELETEPROJECTINDIVIDUALCONFIRM'));
		JToolBarHelper::editListX();
		JToolBarHelper::addNewX();
    JToolBarHelper::help( 'screen.tracks', true );
        
		$db		= JFactory::getDBO();
		$uri	= JFactory::getURI();

		//$filter_state		= $mainframe->getUserStateFromRequest( $option.'.viewprojectindividuals.filter_state',		'filter_state',		'',				'word' );
		$filter_order		= $mainframe->getUserStateFromRequest( $option.'.viewprojectindividuals.filter_order',		'filter_order',		't.name',	'cmd' );
		$filter_order_Dir	= $mainframe->getUserStateFromRequest( $option.'.viewprojectindividuals.filter_order_Dir',	'filter_order_Dir',	'',				'word' );
		$search				= $mainframe->getUserStateFromRequest( $option.'.viewprojectindividuals.search',			'search',			'',				'string' );
		$search				= JString::strtolower( $search );

		// Get data from the model
		//$model	= $this->getModel( );
		//print_r($model);
		$items		= $this->get( 'Data' );
		$total		= $this->get( 'Total' );
		$pagination = $this->get( 'Pagination' );

		// build list of categories
		//$javascript 	= 'onchange="document.adminForm.submit();"';
		
		// state filter
		//$lists['state']	= JHTML::_('grid.state',  $filter_state );

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
		$this->assignRef('project_id',  $project_id);
		
		parent::display($tpl);
	}
	
	function _displayAssign($tpl = null)
	{
    $mainframe = JFactory::getApplication();
$option = JRequest::getCmd('option');
		
		// Set toolbar items for the page
		JToolBarHelper::title(   JText::_('COM_TRACKS_Assign_Participants' ), 'generic.png' );
		JToolBarHelper::back();
		JToolBarHelper::save('saveassign', 'Save');
		JToolBarHelper::cancel('cancelassign');
    JToolBarHelper::help( 'screen.tracks', true );
    
		$db		= JFactory::getDBO();
		$uri	= JFactory::getURI();
		
    // Get data from the model
    // $model = $this->getModel();
    // print_r($model);
    
		//build the html select list for teams
    $teamoptions[] = JHTML::_('select.option',  '0', '- '. JText::_('COM_TRACKS_Select_a_team' ) .' -','id', 'name' );
    if ( $res = $this->get('Teams') ) {
      $teamoptions = array_merge( $teamoptions, $res );
    }
    
    //build the html select list for projects
    $projects[] = JHTML::_('select.option',  '0', '- '. JText::_('COM_TRACKS_Select_a_project' ) .' -','value', 'text' );
    if ( $res = $this->get('projectsListOptions') ) {
      $projects = array_merge( $projects, $res );
    }
    $lists['projects'] = JHTML::_('select.genericlist',  $projects, 'project_id', 'class="inputbox" size="1"', 'value', 'text');
    unset($projects);
    
    // get player names
		$players		= $this->get( 'assignList' );
		
		$this->assignRef('user',		JFactory::getUser());
		$this->assignRef('players',		$players);
    $this->assignRef('teamoptions',   $teamoptions);
    $this->assignRef('lists',   $lists);
		$this->assignRef('request_url',	$uri->toString());
		
		parent::display($tpl);
  }
}
?>
