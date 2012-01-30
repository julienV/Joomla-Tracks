<?php
/**
* @version    $Id: view.html.php 128 2008-06-06 08:08:04Z julienv $ 
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
class TracksViewIndividual extends TracksView
{
	function display($tpl = null)
	{
		$mainframe = &JFactory::getApplication();
		$option = JRequest::getCmd('option');

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
		$mainframe = &JFactory::getApplication();
		$option = JRequest::getCmd('option');

		$db		=& JFactory::getDBO();
		$uri 	=& JFactory::getURI();
		$user 	=& JFactory::getUser();
		$model	=& $this->getModel();

		$lists = array();
		//get the project
		$object	=& $this->get('data');
		$isNew		= ($object->id < 1);

		// fail if checked out not by 'me'
		if ($model->isCheckedOut( $user->get('id') )) {
			$msg = JText::sprintf( 'DESCBEINGEDITTED', JText::_('COM_TRACKS_The_Individual' ), $object->name );
			$mainframe->redirect( 'index.php?option='. $option, $msg );
		}

		// Edit or Create?
		if (!$isNew)
		{
			$model->checkout( $user->get('id') );
		}
		
		// users list
    $lists['users'] = TracksHelper::usersSelect('user_id', $object->user_id, 1, NULL, 'name', 0);
    
    // countries
    $countries = array();
    $countries[] = JHTML::_('select.option', '', JText::_('COM_TRACKS_Select_country'));
    $countries = array_merge($countries, TracksCountries::getCountryOptions());
    $lists['countries'] = JHTML::_('select.genericlist', $countries, 'country_code', '', 'value', 'text', $object->country_code);
		
		//editor
		$editor =& JFactory::getEditor();
		
    $this->assignRef( 'editor', $editor );   
		$this->assignRef( 'lists', $lists);
		$this->assignRef( 'object', $object);
    $this->assignRef( 'miniimageselect', $miniimageselect);
		$this->assignRef('form'      	, $this->get('form'));

		parent::display($tpl);
	}
}
?>
