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
class TracksViewProjectsetting extends TracksView
{
	function display($tpl = null)
	{
    $this->setLayout('form');
		$this->_displayForm($tpl);
    return;
    
		if($this->setLayout() == 'form') {
			$this->_displayForm($tpl);
			return;
		}

		//get the project
		$projectsettings =& $this->get('data');
		
		parent::display($tpl);
	}

	function _displayForm($tpl)
	{
		$mainframe = &JFactory::getApplication();
		$option = JRequest::getCmd('option');

		$db		=& JFactory::getDBO();
		$uri 	=& JFactory::getURI();
		$user 	=& JFactory::getUser();
    $document = & JFactory::getDocument();
    
    JHTML::_('behavior.tooltip');
				
		$object =& $this->get('data');
    
		JForm::addFormPath(JPATH_COMPONENT.DS.'projectparameters'.DS.'default');
		if (strstr($object->xml, '.xml')) {
			$xml = substr($object->xml, 0, -4);
		}
		else {
			$xml = $object->xml;
		}
		$projectparams = JForm::getInstance('form', $xml);
		$projectparams->bind(array('params' => $object->settings));
    
    $this->assignRef('object', $object);
    $this->assignRef('projectparams', $projectparams);
		
		parent::display($tpl);
	}
}
?>
