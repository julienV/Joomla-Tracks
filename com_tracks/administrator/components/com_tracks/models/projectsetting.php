<?php
/**
* @version    $Id: project.php 15 2008-02-06 00:37:43Z julienv $ 
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

jimport('joomla.application.component.model');
require_once (JPATH_COMPONENT.DS.'models'.DS.'item.php');

/**
 * Joomla Tracks Component Project settings Model
 *
 * @author Julien Vonthron <julien.vonthron@gmail.com>
 * @package   Tracks
 * @since 0.1
 */
class TracksModelProjectsetting extends TracksModelItem
{
  /**
   * project id
   *
   * @var int
   */
	var $_project_id = 0;
	/**
	 * item id
	 *
	 * @var int
	 */
	var $_id = 0;
	/**
	 * data records
	 *
	 * @var array
	 */
	var $_data = null;
	
	function __construct()
	{
		$mainframe = JFactory::getApplication();
		$option = JRequest::getCmd('option');
		
		parent::__construct();
		
		$project_id = $mainframe->getUserState( $option.'project', 0 );
    $this->set('_project_id', $project_id);    
    
    $array = JRequest::getVar('cid',  0, '', 'array');
    $this->setId((int)$array[0]);		
	}
	
  /**
   * Method to set the identifier
   *
   * @access  public
   * @param int category identifier
   */
  function setId($id)
  {
    // Set category id and wipe data
    $this->_id      = $id;
    $this->_data  = null;
  }
	  

  /**
   * Method to get an item
   *
   * @since 0.1
   */
  function &getData()
  {
    // Load the item data
    if (!$this->_loadData()) $this->_initData();

    return $this->_data;
  }
  
  /**
   * Method to load content round data
   *
   * @access  private
   * @return  boolean True on success
   * @since 0.1
   */
  function _loadData()
  {
    // Lets load the content if it doesn't already exist
    if (empty($this->_data))
    {
      $query = ' SELECT * FROM #__tracks_project_settings WHERE id = ' . $this->_db->Quote($this->_id);
      $this->_db->setQuery($query);
      $this->_data = $this->_db->loadObject();
      return (boolean) $this->_data;
    }
    return true;
  }
  
  /**
   * Method to initialise the data
   *
   * @access  private
   * @return  boolean True on success
   * @since 1.5
   */
  function _initData()
  {
  	// Lets load the content if it doesn't already exist
  	if (empty($this->_data))
  	{
  		$object = new stdClass();
  		$object->id              = 0;
  		$object->project_id          = $this->_project_id;
  		$object->xml                 = '';
  		$object->parameters          = '';
  		$object->checked_out         = 0;
  		$object->checked_out_time    = 0;
  		$this->_data         = $object;
  		return (boolean) $this->_data;
  	}
  	return true;
  }
  

  /**
   * Method to store the item
   *
   * @access  public
   * @return  false|int id on success
   * @since 1.5
   */
  function store($data)
  {
    $row = $this->getTable();
    
    // Bind the form fields to the items table
    if (!$row->bind($data)) {
      $this->setError($this->_db->getErrorMsg());
      return false;
    }

    // Create the timestamp for the date
    $row->checked_out_time = gmdate('Y-m-d H:i:s');

    // Make sure the item is valid
    if (!$row->check()) {
      $this->setError($row->getError());
      return false;
    }

    // Store the item to the database
    if (!$row->store()) {
      $this->setError($this->_db->getErrorMsg());
      return false;
    }
    return $row->id;
  }
  
  /**
  * Returns a Table object, always creating it
  *
  * @param	type	The table type to instantiate
  * @param	string	A prefix for the table class name. Optional.
  * @param	array	Configuration array for model. Optional.
  * @return	JTable	A database object
  * @since	1.6
  */
  public function getTable($type = 'projectsetting', $prefix = 'table', $config = array())
  {
  	return JTable::getInstance($type, $prefix, $config);
  }
}
?>
