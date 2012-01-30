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
 * @since 0.1
 */
class TracksFrontViewIndividuals extends JView
{
    function display($tpl = null)
    {
    	$mainframe = &JFactory::getApplication();
			$option = JRequest::getCmd('option');
			$params = $mainframe->getParams('com_tracks');
    	          
			$model =& $this->getModel();
			$rows = $model->getData();
						
			switch (JRequest::getCmd('filtering'))
			{
				case 'female':
					$title = Jtext::_('COM_TRACKS_INDIVIDUAL_SEARCH_FEMALES_TITLE');
					break;
				case 'haspicture':
					$title = Jtext::_('COM_TRACKS_INDIVIDUAL_SEARCH_PICTURES_TITLE');
					break;
				case 'standup':
					$title = Jtext::_('COM_TRACKS_INDIVIDUAL_SEARCH_STANDUP_TITLE');
					break;
				case 'lastupdated':
					$title = Jtext::_('COM_TRACKS_INDIVIDUAL_SEARCH_LAST_UPDATED_TITLE');
					break;
				case 'lastviewed':
					$title = Jtext::_('COM_TRACKS_INDIVIDUAL_SEARCH_LAST_VIEWED_TITLE');
					break;
				default:
					$title = Jtext::_('COM_TRACKS_All_Individuals');
				break;
			}
			if (JRequest::getCmd('country')) {
				$title .= Jtext::sprintf('COM_TRACKS_INDIVIDUAL_SEARCH_COUNTRY_TITLE', TracksCountries::getCountryName(JRequest::getCmd('country')));
			}
			
			$document =& JFactory::getDocument();
			$document->setTitle( $title );
			
			$breadcrumbs =& $mainframe->getPathWay();
			$breadcrumbs->addItem( $title );
			
			$this->assignRef( 'rows',    $rows );
			$this->assignRef( 'params',  $params );
			$this->assignRef( 'title',   $title );

			parent::display($tpl);
    }
}
?>