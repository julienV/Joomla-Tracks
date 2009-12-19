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
 * @since 0.2
 */
class TracksViewSubroundResults extends TracksView
{
	function display($tpl = null)
	{
		global $mainframe, $option;
    $document = &JFactory::getDocument();
    
    $document->addScript(JURI::base() . 'components/com_tracks/assets/js/Autocompleter.js');
    $document->addScript(JURI::base() . 'components/com_tracks/assets/js/Observer.js');
    $document->addStyleSheet(JURI::base() . 'components/com_tracks/assets/css/Autocompleter.css');

		$subround =& $this->get('SubroundInfo');
		
		// Set toolbar items for the page
		JToolBarHelper::title(   JText::_( 'Sub-round results: ').$subround->roundname.' - '.$subround->subroundname , 'generic.png' );
		//JToolBarHelper::custom('massadd', 'default', 'default', JText::_('Mass Add'), false);
    JToolBarHelper::custom('back', 'back.png', 'back.png', JTEXT::_('back'), false); 
		JToolBarHelper::save( 'saveranks', 'Save' );
		JToolBarHelper::editListX();
		JToolBarHelper::addNewX();
    JToolBarHelper::custom('addall', 'default', 'default', JText::_('Add all'), false);
    JToolBarHelper::deleteList();
		JToolBarHelper::help( 'screen.tracks', true );
        
		$db		=& JFactory::getDBO();
		$uri	=& JFactory::getURI();

		$filter_order		= $mainframe->getUserStateFromRequest( $option.'.viewsubroundresults.filter_order',		'filter_order',		'rr.rank',	'cmd' );
		$filter_order_Dir	= $mainframe->getUserStateFromRequest( $option.'.viewsubroundresults.filter_order_Dir',	'filter_order_Dir',	'',				'word' );

		// Get data from the model
		//$model	=& $this->getModel( );
		//print_r($model);
		$items		= & $this->get( 'Data' );
		$total		= & $this->get( 'Total' );
		$pagination = & $this->get( 'Pagination' );

		// build list of categories
		//$javascript 	= 'onchange="document.adminForm.submit();"';
		
		// state filter
		//$lists['state']	= JHTML::_('grid.state',  $filter_state );

		// table ordering
		$lists['order_Dir'] = $filter_order_Dir;
		$lists['order'] = $filter_order;
				
		$this->assignRef('user',		JFactory::getUser());
		$this->assignRef('lists',		$lists);
		$this->assignRef('items',		$items);
		$this->assignRef('subround_id',	JRequest::getVar('srid',0));
		$this->assignRef('pagination',	$pagination);
		$this->assignRef('request_url',	$uri->toString());
		$this->assignRef('site_url',	$mainframe->getSiteURL());

		parent::display($tpl);
	}
}
?>
