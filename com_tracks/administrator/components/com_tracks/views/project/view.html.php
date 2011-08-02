<?php
/**
* @version    $Id: view.html.php 94 2008-05-02 10:28:05Z julienv $ 
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
class TracksViewProject extends TracksView
{
	function display($tpl = null)
	{
		$mainframe = &JFactory::getApplication();
		$option = JRequest::getCmd('option');

		if($this->getLayout() == 'form') {
			$this->_displayForm($tpl);
			return;
		}

		//get the project
		$project =& $this->get('data');
		
		parent::display($tpl);
	}

	function _displayForm($tpl)
	{
		$mainframe = &JFactory::getApplication();
		$option = JRequest::getCmd('option');

		$db		=& JFactory::getDBO();
		$uri 	=& JFactory::getURI();
		$user 	=& JFactory::getUser();
		$model	=& $this->getModel();

    $document = & JFactory::getDocument();
    $document->addStyleSheet('components/com_tracks/assets/css/tracksbackend.css');
    
		$lists = array();
		//get the project
		$project	=& $this->get('data');
		$isNew		= ($project->id < 1);

		// fail if checked out not by 'me'
		if ($model->isCheckedOut( $user->get('id') )) {
			$msg = JText::sprintf( 'DESCBEINGEDITTED', JText::_('COM_TRACKS_The_project' ), $project->name );
			$mainframe->redirect( 'index.php?option='. $option, $msg );
		}

		// Edit or Create?
		if (!$isNew)
		{
			$model->checkout( $user->get('id') );
		}
		else
		{
			// initialise new record
			$project->published = 1;
			$project->order 	= 0;
		}
		
		//build the html select list for competition
	    $competitions[] = JHTML::_('select.option',  '', '- '. JText::_('COM_TRACKS_Select_a_Competition' ) .' -','id', 'name' );
	    if ( $res = & $this->get('Competitions') ) {
	      $competitions = array_merge( $competitions, $res );
	    }
	    $lists['competitions'] = JHTML::_('select.genericlist',  $competitions, 'competition_id', 'class="inputbox required" size="1"', 'id', 'name', $project->competition_id);
	    unset($competitions);
	    
	    //build the html select list for season
	    $seasons[] = JHTML::_('select.option',  '', '- '. JText::_('COM_TRACKS_Select_a_Season' ) .' -','id', 'name' );
	    if ( $res = & $this->get('Seasons') ) {
	      $seasons = array_merge( $seasons, $res );
	    }
	    $lists['seasons'] = JHTML::_('select.genericlist',  $seasons, 'season_id', 'class="inputbox required" size="1"', 'id', 'name', $project->season_id);
	    unset($seasons);
			
	    //build the html select list for admin
	    $lists['admin'] = JHTML::_('list.users',  'admin_id', $project->admin_id);
    
		// build the html select list for ordering
		$query = 'SELECT ordering AS value, name AS text'
			. ' FROM #__tracks_projects'
			. ' ORDER BY ordering';

		$lists['ordering'] 			= JHTML::_('list.specificordering',  $project, $project->id, $query, 1 );

		// build the html select list
		$lists['published'] 		= JHTML::_('select.booleanlist',  'published', 'class="inputbox"', $project->published );
		
		$file 	= JPATH_COMPONENT.DS.'models'.DS.'project.xml';
		$params = new JParameter( $project->params, $file );

		$this->assignRef('lists',		$lists);
		$this->assignRef('project',		$project);
		$this->assignRef('params',		$params);

		parent::display($tpl);
	}
}
?>
