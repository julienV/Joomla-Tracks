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
class TracksViewRound extends TracksView
{
	function display($tpl = null)
	{
		global $mainframe;

		if($this->getLayout() == 'form') {
			$this->_displayForm($tpl);
			return;
		}

		//get the object
		$object =& $this->get('data');
		
		parent::display($tpl);
	}

	function _displayForm($tpl)
	{
		global $mainframe, $option;

		$db		=& JFactory::getDBO();
		$uri 	=& JFactory::getURI();
		$user 	=& JFactory::getUser();
		$model	=& $this->getModel();

    $document = & JFactory::getDocument();
    $document->addStyleSheet('components/com_tracks/assets/css/tracksbackend.css');
    
		$lists = array();
		//get the project
		$object	=& $this->get('data');
		$isNew		= ($object->id < 1);

		// fail if checked out not by 'me'
		if ($model->isCheckedOut( $user->get('id') )) {
			$msg = JText::sprintf( 'DESCBEINGEDITTED', JText::_( 'The Round' ), $object->name );
			$mainframe->redirect( 'index.php?option='. $option, $msg );
		}

		// Edit or Create?
		if (!$isNew)
		{
			$model->checkout( $user->get('id') );
		}
		
		// build the html select list for ordering
        $query = 'SELECT r.ordering AS value, r.name AS text'
            . ' FROM #__tracks_rounds AS r '
            . ' ORDER BY r.ordering';

        $lists['ordering']          = JHTML::_('list.specificordering',  $object, $object->id, $query, 1 );
		
        // build the html radio for publish
        $lists['published']         = JHTML::_('select.booleanlist',  'published', 'class="inputbox"', $object->published );
        
		//editor
		$editor =& JFactory::getEditor();
		
    $this->assignRef( 'editor', $editor );  
		$this->assignRef( 'lists', $lists );
		$this->assignRef( 'object', $object );

		parent::display($tpl);
	}
}
?>
