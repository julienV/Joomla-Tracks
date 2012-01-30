<?php
/**
* @version    $Id: individual.php 13 2008-02-05 16:25:22Z julienv $ 
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

require_once (JPATH_COMPONENT.DS.'models'.DS.'item.php');

/**
 * Joomla Tracks Component competition Model
 *
 * @author Julien Vonthron <julien.vonthron@gmail.com>
 * @package   Tracks
 * @since 0.1
 */
class TracksModelIndividual extends TracksModelItem
{

	/**
	 * Method to load content individual data
	 *
	 * @access	private
	 * @return	boolean	True on success
	 * @since	0.1
	 */
	function _loadData()
	{
		// Lets load the content if it doesn't already exist
		if (empty($this->_data))
		{
			$query = 'SELECT *'.
					' FROM #__tracks_individuals' .
          ' WHERE id = '.(int) $this->_id;
			$this->_db->setQuery($query);
			$this->_data = $this->_db->loadObject();
			return (boolean) $this->_data;
		}
		return true;
	}

	/**
	 * Method to store the item
	 *
	 * @access	public
	 * @return	boolean	True on success
	 * @since	1.5
	 */
	function store($data)
	{
		$row =& $this->getTable();

		// Bind the form fields to the items table
		if (!$row->bind($data)) {
			$this->setError($this->_db->getErrorMsg());
			return false;
		}

		// Create the timestamp for the date
		$row->checked_out_time = gmdate('Y-m-d H:i:s');

		// Make sure the item is valid
		if (!$row->check()) {
			$this->setError($this->_db->getErrorMsg());
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
	 * Method to initialise the data
	 *
	 * @access	private
	 * @return	boolean	True on success
	 * @since	1.5
	 */
	function _initData()
	{
		// Lets load the content if it doesn't already exist
		if (empty($this->_data))
		{
			$object = new stdClass();
			$object->id					      = 0;
			$object->last_name        = "";
			$object->first_name       = "";
      $object->alias            = "";
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
			$object->checked_out			= 0;
			$object->checked_out_time	= 0;
			$this->_data					= $object;
			return (boolean) $this->_data;
		}
		return true;
	}
	
  /**
   * Method to delete record(s)
   *
   * @access  public
   * @return  boolean True on success
   */
  function delete()
  {
    $cids = JRequest::getVar( 'cid', array(0), 'post', 'array' );

    $row =& $this->getTable();

    $rec = 0;
    if (count( $cids ))
    {
      foreach($cids as $cid) {
      	//first check that individual isn't used in a project
      	$query = ' SELECT COUNT(*) FROM #__tracks_projects_individuals WHERE individual_id = ' . $cid;
      	$this->_db->setQuery($query);
      	if ($this->_db->loadResult())
      	{
      		$this->setError( JText::_('COM_TRACKS_INDIVIDUALPROJECTINUSE') );
          return false;
      	}
      	
        if (!$row->delete( $cid )) {
          $this->setError( $row->getErrorMsg() );
          return false;
        }
      } 
    }
    return $rec;
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
	public function getTable($type = 'individual', $prefix = 'table', $config = array())
	{
		return JTable::getInstance($type, $prefix, $config);
	}

	/**
		* Method to get the record form.
		*
		* @param	array	$data		Data for the form.
		* @param	boolean	$loadData	True if the form is to load its own data (default case), false if not.
		* @return	mixed	A JForm object on success, false on failure
		* @since	1.7
		*/
	public function getForm($data = array(), $loadData = true)
	{
		// Get the form.
		$form = $this->loadForm('com_tracks.individual', 'individual',
		array('load_data' => $loadData) );
		if (empty($form))
		{
			return false;
		}
		return $form;
	}

	/**
		* Method to get the data that should be injected in the form.
		*
		* @return	mixed	The data for the form.
		* @since	1.7
		*/
	protected function loadFormData()
	{
		// Check the session for previously entered form data.
		$data = JFactory::getApplication()->getUserState('com_tracks.edit.individual.data', array());
		if (empty($data))
		{
			$data = $this->getData();
		}
		return $data;
	}
}
?>
