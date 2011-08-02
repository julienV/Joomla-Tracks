<?php
/**
* @version    $Id: view.html.php 110 2008-05-26 16:47:13Z julienv $ 
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
jimport( 'joomla.html.pane' );

/**
 * HTML View class for the Tracks component
 *
 * @static
 * @package		Tracks
 * @since 0.1
 */
class TracksViewMenu extends JView
{
  function display($tpl = null)
	{
		$mainframe = &JFactory::getApplication();
		$option = JRequest::getCmd('option');
		
		if ($this->getLayout() == 'end') {
			return $this->_displayEnd();
		}
		
		$project	= & $this->get( 'Data' );
    $pane     = & JPane::getInstance('sliders');
    $view     = JRequest::getCmd( 'view' );

	  //Create Submenu
    JSubMenuHelper::addEntry( JText::_('COM_TRACKS_PROJECTS' ), 'index.php?option=com_tracks&view=projects', $view == 'projects');
    JSubMenuHelper::addEntry( JText::_('COM_TRACKS_COMPETITIONS' ), 'index.php?option=com_tracks&view=competitions', $view == 'competitions');
    JSubMenuHelper::addEntry( JText::_('COM_TRACKS_SEASONS' ), 'index.php?option=com_tracks&view=seasons', $view == 'seasons');
    JSubMenuHelper::addEntry( JText::_('COM_TRACKS_TEAMS' ), 'index.php?option=com_tracks&view=teams', $view == 'teams');
    JSubMenuHelper::addEntry( JText::_('COM_TRACKS_INDIVIDUALS' ), 'index.php?option=com_tracks&view=individuals', $view == 'individuals');
    JSubMenuHelper::addEntry( JText::_('COM_TRACKS_ROUNDS' ), 'index.php?option=com_tracks&view=rounds', $view == 'rounds');
    JSubMenuHelper::addEntry( JText::_('COM_TRACKS_SUBROUNDTYPES' ), 'index.php?option=com_tracks&view=subroundtypes', $view == 'subroundtypes');
    JSubMenuHelper::addEntry( JText::_('COM_TRACKS_ABOUT' ), 'index.php?option=com_tracks&view=about', $view == 'about');

		$this->assignRef('project',		$project);
    $this->assignRef('pane',   $pane);
		
		parent::display('tablepane');
	}
	
	function _displayEnd($tpl = null)
	{
		parent::display('tablepane');
	}
}
?>