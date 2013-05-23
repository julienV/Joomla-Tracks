<?php
/**
* @version    $Id: roundresult.php 61 2008-04-24 15:20:36Z julienv $ 
* @package    JoomlaTracks
* @copyright    Copyright (C) 2008 Julien Vonthron. All rights reserved.
* @license      GNU/GPL, see LICENSE.php
* Joomla Tracks is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();

jimport('joomla.application.component.model');
require_once( 'base.php' );

/**
 * Joomla Tracks Component Front page Model
 *
 * @package     Tracks
 * @since 0.1
 */
class TracksModelProfile extends baseModel
{ 
	/** individual id **/  
	var $_id = 0;
	
	var $_userid;
	
  var $_data = null;
	
	function __construct()
	{
		parent::__construct();
		
	  $this->_id = JRequest::getInt('i');
	}
	
	function getData()
	{
		$user = JFactory::getUser();
		if ( $this->_id )
		{
			$query =  ' SELECT i.* '
			. ' FROM #__tracks_individuals as i '
			. ' WHERE i.id = ' . $this->_id;
		}
		else if ($user->get('id'))
		{
			$query =  ' SELECT i.* '
			. ' FROM #__tracks_individuals as i '
			. ' WHERE i.user_id = ' . $user->get('id');
		}
		else {
			JError::raiseError(403, JText::_('COM_TRACKS_ERROR_MUST_BE_LOGGED'));
			return false;
		}

		$this->_db->setQuery( $query );

		if ( $result = $this->_db->loadObject() )
		{
			$this->_data = $result;
			$attribs['class']="pic";

			if ($this->_data->picture != '') {
				$this->_data->picture = JHTML::image(JURI::root().$this->_data->picture, $this->_data->first_name. ' ' . $this->_data->last_name, $attribs);
			} else {
				$this->_data->picture = JHTML::image(JURI::root().'media/com_tracks/images/misc/tnnophoto.jpg', $this->_data->first_name. ' ' . $this->_data->last_name, $attribs);
			}
			 
			if ($this->_data->picture_small != '') {
				$this->_data->picture_small = JHTML::image(JURI::root().$this->_data->picture_small, $this->_data->first_name. ' ' . $this->_data->last_name, $attribs);
			} else {
				$this->_data->picture_small = JHTML::image(JURI::root().'media/com_tracks/images/misc/tnnophoto.jpg', $this->_data->first_name. ' ' . $this->_data->last_name, $attribs);
			}

		}
    
    return $this->_data;
	}

  
  function _initData()
  {
      $object = new stdClass();
      $object->id               = 0;
      $object->last_name        = "";
      $object->first_name       = "";
      $object->nickname         = "";
      $object->dob              = null;
      $object->height           = null;
      $object->weight           = null;
      $object->hometown         = null;
      $object->country          = null;
      $object->user_id          = 0;
      $object->picture          = null;
      $object->picture_small    = null;
      $object->address          = null;
      $object->postcode         = null;
      $object->city             = null;
      $object->state            = null;
      $object->country_code     = null;
      $object->description      = null;
      $object->checked_out      = 0;
      $object->checked_out_time = 0;
      $this->_data          = $object;
      return (boolean) $this->_data;
  }
    
}
