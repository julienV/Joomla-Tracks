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
		$mainframe = &JFactory::getApplication();
		$option = JRequest::getCmd('option');

		if ($this->getLayout() == 'form') {
			$this->_displayForm($tpl);
			return;
		}

		$user   =& JFactory::getUser();
		
		$params = $mainframe->getParams( 'com_tracks' );
		$document =& JFactory::getDocument();
		
		$dispatcher = & JDispatcher::getInstance();
    JPluginHelper::importPlugin('tracks');
    
    JPluginHelper::importPlugin('content');

		$data = & $this->get('Data');

//if ( $user->name=="John Corns" )
 //  	$raceResults = $this->sortResultsByYear($this->get('RaceResults'));
  // 	else
		$raceResults = $this->sortResultsByProject($this->get('RaceResults'));

		$rankings = $this->get('Rankings');

		$show_edit_link = ( $user->id && $user->id == $data->user_id ) || $user->authorise('core.manage', 'com_tracks');

	  if (! $show_edit_link && $user->name )
    {
		$model = &$this->getModel();
		$model->addHit();
		}

		$breadcrumbs =& $mainframe->getPathWay();
		$breadcrumbs->addItem( $data->first_name . ' ' . $data->last_name,
                        'index.php?option=com_tracks&view=individual&i=' . $data->id );

		$document->setTitle( $data->first_name . ' ' . $data->last_name );
				
		// allow content plugins
		$data->description = JHTML::_('content.prepare', $data->description);
		
		$options = array(JHTML::_('select.option', 'racing', JText::_('COM_TRACKS_TIPS_SELECT_category_RACING')));
		if (TracksHelperTools::isFreerider($data->id)) {
			$options[] = JHTML::_('select.option', 'freeride', JText::_('COM_TRACKS_TIPS_SELECT_category_FREERIDE'));			
		}
		if (TracksHelperTools::isFreestyler($data->id)) {
			$options[] = JHTML::_('select.option', 'freestyle', JText::_('COM_TRACKS_TIPS_SELECT_category_FREESTYLE'));			
		}
		$tipscategory = JHTML::_('select.genericlist', $options, 'category');

		$this->assignRef( 'data',           $data );
		$this->assignRef( 'show_edit_link', $show_edit_link );
		$this->assignRef( 'results',        $raceResults );
		$this->assignRef( 'rankings',       $rankings );
		$this->assignRef( 'params',         $params );
    $this->assignRef( 'dispatcher',     $dispatcher );
    $this->assignRef( 'competedagainst', $this->get('competedagainst') );
    $this->assignRef( 'useragainst',     $this->get('userParticipatedAgainst') );
    $this->assignRef( 'user',     $user );
    $this->assignRef( 'tipscategory',     $tipscategory );

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

	function sortResultsByYear($results)
	{
		$projects = array();
		if (!count($results)) return $projects;

		foreach ($results AS $r)
		{
		if ($r->seasonname != 'event')
 			@$projects[$r->s][] = $r;
		}

		foreach ($results AS $r)
		{
		if ($r->seasonname == 'event')
 			@$projects[$r->r][] = $r;
		}

		return $projects;
	}

	function addOrdinalNumberSuffix($num) 
	{
		if (!in_array(($num % 100),array(11,12,13))){
			switch ($num % 10) {
				// Handle 1st, 2nd, 3rd
				case 1:  return $num.'st';
				case 2:  return $num.'nd';
				case 3:  return $num.'rd';
			}
		}
		return $num.'th';
	}
	
    

  function _displayForm($tpl)
  {
    $mainframe = &JFactory::getApplication();
$option = JRequest::getCmd('option');
    
    $db   =& JFactory::getDBO();
    $uri  =& JFactory::getURI();
    $user   =& JFactory::getUser();
    
    if (!$user->get('id')) {
      $mainframe->redirect(JURI::base(), JText::_('COM_TRACKS_VIEW_INDIVIDUAL_PLEASE_LOGIN_TO_EDIT_PROFILE'), 'error' );
    }
      
    $profile = &JModel::getInstance('profile', 'TracksFrontModel');
    $this->setModel($profile, true);
                
    // Get the page/component configuration
    $params = &$mainframe->getParams();
    
    $menus  = &JSite::getMenu();
    $menu = $menus->getActive();

    // because the application sets a default page title, we need to get it
    // right from the menu item itself
    //AP REMOVED BECAUSE OF THE ERRORS USERS WERE REPORTING
//     if (is_object( $menu )) {
//       $menu_params = new JParameter( $menu->params );
//       if (!$menu_params->get( 'page_title')) {
//         $params->set('page_title',  JText::_( 'COM_TRACKS_VIEW_INDIVIDUAL_TITLE' ));
//       }
//     } else {
//       $params->set('page_title',  JText::_( 'COM_TRACKS_VIEW_INDIVIDUAL_TITLE' ));
//    }
    $document = &JFactory::getDocument();
    $document->setTitle( $params->get( 'page_title' ) );
    
    //get the individual
    $object = & $this->get('Data');
    $isNew = ($object->id > 0) ? 0 : 1;
    
    $lists = array();

    // countries
    $countries = array();
    $countries[] = JHTML::_('select.option', '', JTEXT::_('COM_TRACKS_SELECT_COUNTRY'));
    $countries = array_merge($countries, TracksCountries::getCountryOptions());
    $lists['countries'] = JHTML::_('select.genericlist', $countries, 'country_code', '', 'value', 'text', $object->country_code);
    
    $options = $this->get('SponsorOptions');
    $lists['sponsor'] = $options;
    
    if ( $user->authorise('core.manage', 'com_tracks') 
      || ( $object->user_id && ($user->id == $object->user_id) )
      || ( $isNew && $user->id )
       ) {
    	$can_edit = true;
    }
    else {
      $can_edit = false;
    }
    
    if (!$can_edit) {
      JError::raiseError( 403, JText::_( 'COM_TRACKS_ACCESS_FORBIDDEN' ));
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
    $this->assignref('sponsors', $this->get('sponsors'));

    parent::display($tpl);
  }
  
  public function rideButton($result)
  {
  	$user = &JFactory::getUser();
  	if ($result->ride) {
  		return JLVImageTool::modalimage($result->ride, 'ride', 16, null, 'components/com_tracks/assets/images/viewride.png');
  	}
  	else if ($user->get('id') && ($user->authorise('core.manage', 'com_tracks') || $this->data->user_id == $user->get('id')))
  	{
  		$document = JFactory::getDocument();
			$document->addScript('components/com_tracks/assets/js/ridemodal.js');
  		JHTML::_('behavior.modal', 'a.ride');
  		$img = JHTML::image('components/com_tracks/assets/images/addride.png', 'ride');
  		$attribs = array('class' => 'ride',
			                 'rel' => "{handler: 'iframe', size: {x: 650, y: 375}}");
  		$link = JHTML::link('index.php?option=com_tracks&controller=addride&task=add&tmpl=component&r='.$result->id, $img, $attribs);
  		return $link;
  	}
  }
}
?>
