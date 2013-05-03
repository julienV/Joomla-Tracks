<?php
/**
* @version    $Id: item.php 65 2008-04-24 16:33:58Z julienv $ 
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

jimport('joomla.application.component.modeladmin');

/**
 * Joomla Tracks Component Item Model
 *
 * @author Julien Vonthron <julien.vonthron@gmail.com>
 * @package   Tracks
 * @since 0.1
 */
abstract class TracksModelItem extends JModelAdmin
{
	/**
	 * item id
	 *
	 * @var int
	 */
	var $_id = null;

	/**
	 * Project data
	 *
	 * @var array
	 */
	var $_data = null;

	/**
	 * Constructor
	 *
	 * @since 0.1
	 */
	function __construct()
	{
		parent::__construct();

		$array = JRequest::getVar('cid', array(0), '', 'array');
		$edit	= JRequest::getVar('edit',true);
		if($edit)
			$this->setId((int)$array[0]);
	}

	/**
	 * Method to set the item identifier
	 *
	 * @access	public
	 * @param	int item identifier
	 */
	function setId($id)
	{
		// Set item id and wipe data
		$this->_id		= $id;
		$this->_data	= null;
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
	 * Tests if item is checked out
	 *
	 * @access	public
	 * @param	int	A user id
	 * @return	boolean	True if checked out
	 * @since	0.1
	 */
	function isCheckedOut( $uid=0 )
	{
		if ($this->_loadData())
		{
			if ($uid) {
				return ($this->_data->checked_out && $this->_data->checked_out != $uid);
			} else {
				return $this->_data->checked_out;
			}
		}
	}

	/**
	 * Method to checkin/unlock the item
	 *
	 * @access	public
	 * @return	boolean	True on success
	 * @since	1.5
	 */
	function checkin()
	{
		if ($this->_id)
		{
			$project = $this->getTable();
			if(! $project->checkin($this->_id)) {
				$this->setError($this->_db->getErrorMsg());
				return false;
			}
		}
		return false;
	}

	/**
	 * Method to checkout/lock the item
	 *
	 * @access	public
	 * @param	int	$uid	User ID of the user checking the item out
	 * @return	boolean	True on success
	 * @since	1.5
	 */
	function checkout($uid = null)
	{
		if ($this->_id)
		{
			// Make sure we have a user id to checkout the article with
			if (is_null($uid)) {
				$user	= JFactory::getUser();
				$uid	= $user->get('id');
			}
			// Lets get to it and checkout the thing...
			$project = $this->getTable();
			if(!$project->checkout($uid, $this->_id)) {
				$this->setError($this->_db->getErrorMsg());
				return false;
			}

			return true;
		}
		return false;
	}

	/**
	 * Method to store the item
	 *
	 * @access	public
	 * @return	false|int	id on success
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

		// if new item, order last
		if (!$row->id) {
			$row->ordering = $row->getNextOrder(  );
		}

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
	 * Method to delete record(s)
	 *
	 * @access	public
	 * @return	boolean	True on success
	 */
	function delete()
	{
		$cids = JRequest::getVar( 'cid', array(0), 'post', 'array' );

		$row = $this->getTable();

		if (count( $cids ))
		{
			foreach($cids as $cid) {
				if (!$row->delete( $cid )) {
					$this->setError( $row->getErrorMsg() );
					return false;
				}
			}						
		}
		return true;
	}
	
	/**
	 * Method to move an item
	 *
	 * @access	public
	 * @return	boolean	True on success
	 * @since	1.5
	 */
	function move($direction)
	{
		$row = $this->getTable();
		if (!$row->load($this->_id)) {
			$this->setError($this->_db->getErrorMsg());
			return false;
		}

		if (!$row->move( $direction )) {
			$this->setError($this->_db->getErrorMsg());
			return false;
		}

		return true;
	}

	/**
	 * Method to save item order
	 *
	 * @access	public
	 * @return	boolean	True on success
	 * @since	1.5
	 */
	function saveorder($cid = array(), $order)
	{
		$row = $this->getTable();

		// update ordering values
		for( $i=0; $i < count($cid); $i++ )
		{
			$row->load( (int) $cid[$i] );

			if ($row->ordering != $order[$i])
			{
				$row->ordering = $order[$i];
				if (!$row->store()) {
					$this->setError($this->_db->getErrorMsg());
					return false;
				}
			}
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
	public function getTable($type = 'tablename', $prefix = '', $config = array())
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
		$form = $this->loadForm('com_tracks.'.$this->name, $this->name,
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
		$data = JFactory::getApplication()->getUserState('com_tracks.edit.'.$this->name.'.data', array());
		if (empty($data))
		{
			$data = $this->getData();
		}
		return $data;
	}
}
?>
