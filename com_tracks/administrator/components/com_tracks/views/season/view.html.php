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

/**
 * HTML View class for the Tracks component
 *
 * @static
 * @package		Tracks
 * @since 0.1
 */
class TracksViewSeason extends JView
{
	function display($tpl = null)
	{
		$mainframe = JFactory::getApplication();
		$option = JRequest::getCmd('option');

		if($this->getLayout() == 'form') {
			$this->_displayForm($tpl);
			return;
		}

		//get the project
		$season = $this->get('data');
		
		parent::display($tpl);
	}

	function _displayForm($tpl)
	{
		$mainframe = JFactory::getApplication();
		$option = JRequest::getCmd('option');

		$db		= JFactory::getDBO();
		$uri 	= JFactory::getURI();
		$user 	= JFactory::getUser();
		$model	= $this->getModel();

    $document = JFactory::getDocument();
    $document->addStyleSheet('components/com_tracks/assets/css/tracksbackend.css');
    
		$lists = array();
		//get the project
		$season	= $this->get('data');
		$isNew		= ($season->id < 1);

		// fail if checked out not by 'me'
		if ($model->isCheckedOut( $user->get('id') )) {
			$msg = JText::sprintf( 'DESCBEINGEDITTED', JText::_('COM_TRACKS_The_season' ), $season->name );
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
			//$season->published = 1;
			$season->order 	= 0;
		}
		
		   
		// build the html select list for ordering
		$query = 'SELECT ordering AS value, name AS text'
			. ' FROM #__tracks_seasons'
			. ' ORDER BY ordering';

		$lists['ordering'] 			= JHTML::_('list.specificordering',  $season, $season->id, $query, 1 );

		// build the html select list
		$arr = array(
			JHtml::_('select.option', '0', JText::_('JNO')),
			JHtml::_('select.option', '1', JText::_('JYES'))
		);
		$lists['published'] 		= JHTML::_('select.genericlist',  $arr,'published', 'class="inputbox"', 'value', 'text', $season->published );

		$this->assignRef('lists',		$lists);
		$this->assignRef('season',		$season);

		parent::display($tpl);
	}
}
?>
