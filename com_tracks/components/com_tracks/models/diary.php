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
require_once (JPATH_COMPONENT_ADMINISTRATOR.DS.'helpers'.DS.'imageselect.php');

/**
 * Joomla Tracks Component Front page Model
 *
 * @package     Tracks
 * @since 0.1
 */
class TracksModelDiary extends baseModel
{ 
	/**
	 * individual id
	 *
	 * @var int
	 */  
	var $_id = 0;
	
	var $_data = null;
	
	function __construct($config = array())
	{
		parent::__construct($config = array());
	  $id = JRequest::getInt('i');
    $this->setId((int)$id);
  }


	
  /**
   * Method to store the individual data
   *
   * @access  public
   * @return  boolean True on success
   * @since 1.5
   */
  function store($data)
  {
  
//  return;
  
		// Require the base controller
		require_once (JPATH_COMPONENT_ADMINISTRATOR.DS.'tables'.DS.'diary.php');
		
    $table = $this->getTable('diary');
    $params = &JComponentHelper::getParams('com_tracks');

    if ($data['id']) 
    {
  //  	$table->load($data['id']);
    }

//		mail('notifications@xjetski.com',  'id='.$table.' diary='.$data['diary_text'], $data['id'] );

    // Store the individual to the database
    if (!$table->save($data)) {
  //    $this->setError( $user->getError() );
    //  return false;
    }
		$this->_id = $table->id;


    return $this->_id;
  }
  
  /**
   * returns individual id if stored.
   *
   * @return int
   */
}
