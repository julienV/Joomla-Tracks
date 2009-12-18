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

/**
 * HTML View class for the Tracks component
 *
 * @static
 * @package		Tracks
 * @since 0.2
 */
class TracksViewSubround extends JView
{
	function display($tpl = null)
	{
		global $mainframe;

		if($this->getLayout() == 'form') {
			$this->_displayForm($tpl);
			return;
		}

		//get the project
		$subround =& $this->get('data');
		
		parent::display($tpl);
	}

	function _displayForm($tpl)
	{
		global $mainframe, $option;

		$db		=& JFactory::getDBO();
		$uri 	=& JFactory::getURI();
		$user 	=& JFactory::getUser();
		$model	=& $this->getModel();

		$lists = array();
		//get the project
		$object	=& $this->get('data');
		$isNew		= ($object->id < 1);

		// fail if checked out not by 'me'
		if ($model->isCheckedOut( $user->get('id') )) 
    {
			$msg = JText::sprintf( 'DESCBEINGEDITTED', JText::_( 'The projectround' ), $object->name );
			$mainframe->redirect( 'index.php?option='. $option, $msg );
		}

		// Edit or Create?
		if (!$isNew) {
			$model->checkout( $user->get('id') );
		}
		else
		{
			// initialise new record
			//$projectround->published = 1;
			$object->order 	= 0;
			$object->projectround_id 	= JRequest::getVar('prid',0);
		}
		
		//build the html select list for sub-round types
	  $types[] = JHTML::_('select.option',  '0', '- '. JText::_( 'Select a sub-round type' ) .' -','id', 'name' );
	  if ( $res = & $this->get('SubroundTypes') ) {
	    $types = array_merge( $types, $res );
	  }
	  $lists['types'] = JHTML::_('select.genericlist',  $types, 'type', 'class="inputbox" size="1"', 'id', 'name', $object->type);
	  unset($types);
    
		// build the html select list for ordering
		$query = 'SELECT sr.ordering AS value, CONCAT(srt.name, "(", srt.note, ")") AS text'
			. ' FROM #__tracks_projects_subrounds AS sr '
			. ' INNER JOIN #__tracks_subroundtypes AS srt ON sr.type = srt.id'
      . ' WHERE projectround_id = ' . JRequest::getVar('prid',0)
			. ' ORDER BY sr.ordering';
		$lists['ordering'] 			= JHTML::_('list.specificordering',  $object, $object->id, $query, 1 );

		// build the html select list
		$lists['published'] 		= JHTML::_('select.booleanlist',  'published', 'class="inputbox"', $object->published );
    
		//editor
    $editor =& JFactory::getEditor();
		
    $this->assignRef('editor', $editor);  
    $this->assignRef('lists',		$lists);
		$this->assignRef('object',		$object);

		parent::display($tpl);
	}
}
?>
