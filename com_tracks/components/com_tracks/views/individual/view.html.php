<?php
/**
* @version    $Id: view.html.php 77 2008-04-30 03:32:25Z julienv $ 
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
class TracksFrontViewIndividual extends JView
{
	function display($tpl = null)
	{
		global $mainframe;

		if ($this->getLayout() == 'form') {
			$this->_displayForm($tpl);
			return;
		}

		$user   =& JFactory::getUser();
		$params = &JComponentHelper::getParams( 'com_tracks' );
		$document =& JFactory::getDocument();
		
		$dispatcher = & JDispatcher::getInstance();
    JPluginHelper::importPlugin('tracks');
    
    JPluginHelper::importPlugin('content');

		$data = & $this->get('Data');
		$raceResults = $this->sortResultsByProject($this->get('RaceResults'));

		$show_edit_link = ( $user->id && $user->id == $data->user_id ) || $user->authorize('com_tracks', 'manage', 'users');

		$breadcrumbs =& $mainframe->getPathWay();
		$breadcrumbs->addItem( $data->first_name . ' ' . $data->last_name,
                        'index.php?option=com_tracks&view=individual&i=' . $data->id );

		$document->setTitle( $data->first_name . ' ' . $data->last_name );
				
		$data->text = $data->description;
		$results = $dispatcher->trigger('onPrepareContent', array (& $data, null, 0));
		$data->description = $data->text;

		$this->assignRef( 'data',    $data );
		$this->assignRef( 'show_edit_link',    $show_edit_link );
		$this->assignRef( 'results', $raceResults );
		$this->assignRef( 'params', $params );
    $this->assignRef( 'dispatcher', $dispatcher );

		parent::display($tpl);
	}
	
	function sortResultsByProject($results)
	{
		$projects = array();
		if (!count($results)) return $projects;

		foreach ($results AS $r)
		{
			@$projects[$r->projectname][] = $r;
		}
		return $projects;
	}
    

  function _displayForm($tpl)
  {
    global $mainframe, $option;
    
    $db   =& JFactory::getDBO();
    $uri  =& JFactory::getURI();
    $user   =& JFactory::getUser();
    
    if (!$user->get('id')) {
      $mainframe->redirect(JURI::base(), JText::_('Please login to be able to edit your tracks profile'), 'error' );
    }
      
    $profile = &JModel::getInstance('profile', 'TracksFrontModel');
    $this->setModel($profile, true);
                
    // Get the page/component configuration
    $params = &$mainframe->getParams();
    
    $menus  = &JSite::getMenu();
    $menu = $menus->getActive();

    // because the application sets a default page title, we need to get it
    // right from the menu item itself
    if (is_object( $menu )) {
      $menu_params = new JParameter( $menu->params );     
      if (!$menu_params->get( 'page_title')) {
        $params->set('page_title',  JText::_( 'Individual Details' ));
      }
    } else {
      $params->set('page_title',  JText::_( 'Individual Details' ));
    }
    $document = &JFactory::getDocument();
    $document->setTitle( $params->get( 'page_title' ) );
    
    //get the individual
    $object = & $this->get('Data');
    $isNew = ($object->id > 0) ? 0 : 1;
    
    $lists = array();

    // countries
    $countries = array();
    $countries[] = JHTML::_('select.option', '', JTEXT::_('Select country'));
    $countries = array_merge($countries, TracksCountries::getCountryOptions());
    $lists['countries'] = JHTML::_('select.genericlist', $countries, 'country_code', '', 'value', 'text', $object->country_code);
    
    if ( $user->authorize('com_tracks', 'manage' ) 
      || ( $object->user_id && ($user->id == $object->user_id) )
      || ( $isNew && $user->id )
       ) {
    	$can_edit = true;
    }
    else {
      $can_edit = false;
    }
    
    if (!$can_edit) {
      JError::raiseError( 403, JText::_( 'Access Forbidden' ));
      return;  
    }
    
    // users list
    $lists['users'] = HTMLTracks::usersSelect('user_id', $object->user_id, 1, NULL, 'name', 0);
    
    //editor
    $editor =& JFactory::getEditor();
    
    $this->assignRef( 'editor', $editor );   
    $this->assignRef( 'lists', $lists);
    $this->assignRef( 'object', $object);
    $this->assignRef( 'params', $params);
    $this->assignRef( 'user', $user);

    parent::display($tpl);
  }
}
?>
