<?php
/**
* @version    $Id$ 
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
class TracksViewSubroundResult extends TracksView
{
  function display($tpl = null)
  {
    global $mainframe;

    if($this->getLayout() == 'form') {
      $this->_displayForm($tpl);
      return;
    }

    //get the project
    $subroundResult =& $this->get('data');
    
    parent::display($tpl);
  }

  function _displayForm($tpl)
  {
    global $mainframe, $option;

    $db   =& JFactory::getDBO();
    $uri  =& JFactory::getURI();
    $user   =& JFactory::getUser();
    $model  =& $this->getModel();

    $lists = array();
    //get the project
    $result =& $this->get('data');
    $isNew    = ($result->id < 1);

    // fail if checked out not by 'me'
    if ($model->isCheckedOut( $user->get('id') )) 
    {
      $msg = JText::sprintf( 'DESCBEINGEDITTED', JText::_( 'The result' ), $result->id );
      $mainframe->redirect( 'index.php?option='. $option, $msg );
    }

    // Edit or Create?
    if (!$isNew) {
      $model->checkout( $user->get('id') );
    }
    else
    {
      // initialise new record
      $participants = array();
      $participants[] = JHTML::_('select.option', 0, JText::_('SELECT PARTICIPANT'));
      $participants = array_merge($participants, $this->get('ParticipantsOptions'));
      $lists['participants'] = JHTML::_('select.genericlist', $participants, 'individual_id', 'class="inputbox required"');
    }
    
    //editor
    $editor =& JFactory::getEditor();
    
    $this->assignRef( 'editor', $editor );   
    $this->assignRef('lists',   $lists);
    $this->assignRef('result',    $result);

    parent::display($tpl);
  }
}
?>
