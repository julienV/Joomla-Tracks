<?php
/**
* @version    $Id: round.php 80 2008-04-30 20:17:37Z julienv $ 
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
 * Joomla Tracks Component competition Model
 *
 * @author Julien Vonthron <julien.vonthron@gmail.com>
 * @package   Tracks
 * @since 0.1
 */
class TracksModelRound extends TracksModelItem
{

  
	/**
	 * Method to (un)publish a round
	 *
	 * @access	public
	 * @return	boolean	True on success
	 * @since	0.1
	 */
	function publish($cid = array(), $publish = 1)
	{
		$user 	= JFactory::getUser();

		if (count( $cid ))
		{
			JArrayHelper::toInteger($cid);
			$cids = implode( ',', $cid );

			$query = 'UPDATE #__tracks_rounds'
				. ' SET published = '.(int) $publish
				. ' WHERE id IN ( '.$cids.' )'
				. ' AND ( checked_out = 0 OR ( checked_out = '.(int) $user->get('id').' ) )'
			;
			$this->_db->setQuery( $query );
			if (!$this->_db->query()) {
				$this->setError($this->_db->getErrorMsg());
				return false;
			}
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
	 * Method to load content round data
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
					' FROM #__tracks_rounds' .
          ' WHERE id = '.(int) $this->_id;
			$this->_db->setQuery($query);
			$this->_data = $this->_db->loadObject();
			return (boolean) $this->_data;
		}
		return true;
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
			$object->id			         = 0;
			$object->name                = null;
      $object->alias               = null;
			$object->description         = null;
			$object->ordering            = 0;
			$object->checked_out         = 0;
			$object->checked_out_time    = 0;
			$object->published   		 = 0;
			$this->_data				 = $object;
			return (boolean) $this->_data;
		}
		return true;
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
	public function getTable($type = 'round', $prefix = 'table', $config = array())
	{
		return JTable::getInstance($type, $prefix, $config);
	}
}
