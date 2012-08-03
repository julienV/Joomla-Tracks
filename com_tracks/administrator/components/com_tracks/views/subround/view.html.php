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
		$mainframe = JFactory::getApplication();
		$option = JRequest::getCmd('option');

		if($this->getLayout() == 'form') {
			$this->_displayForm($tpl);
			return;
		}

		//get the project
		$subround = $this->get('data');
		
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

		JHTML::_('behavior.formvalidation');
		
		//get the project
		$object	= $this->get('data');
		$isNew		= ($object->id < 1);

		// fail if checked out not by 'me'
		if ($model->isCheckedOut( $user->get('id') )) 
    {
			$msg = JText::sprintf( 'DESCBEINGEDITTED', JText::_('COM_TRACKS_The_projectround' ), $object->name );
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
		    
		//editor
    $editor = JFactory::getEditor();
		
    $this->assignRef('editor', $editor);  
		$this->assignRef('object',		$object);
		$this->assignRef('form',		$this->get('form'));

		parent::display($tpl);
	}
}
?>
