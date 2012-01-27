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

// no direct access
defined('_JEXEC') or die('Restricted access');

// Include library dependencies
jimport('joomla.filter.input');

/**
 * Sponsor Table class
 *
 * @package		Joomla.Administrator
 * @subpackage	com_tracks
 * @since		1.7
 */
class TracksTableSponsor extends JTable
{
	/**
	 * Constructor
	 *
	 * @param JDatabase A database connector object
	 */
	public function __construct(&$db)
	{
		parent::__construct('#__tracks_sponsors', 'id', $db);
	}
	
	/**
	* Method to set the publishing state for a row or list of rows in the database
	* table.  The method respects checked out rows by other users and will attempt
	* to checkin rows that it can after adjustments are made.
	*
	* @param	mixed	An optional array of primary key values to update.  If not
	*					set the instance property value is used.
	* @param	integer The publishing state. eg. [0 = unpublished, 1 = published, 2=archived, -2=trashed]
	* @param	integer The user id of the user performing the operation.
	* @return	boolean	True on success.
	* @since	1.6
	*/
	public function publish($pks = null, $state = 1, $userId = 0)
	{
		// Initialise variables.
		$k = $this->_tbl_key;

		// Sanitize input.
		JArrayHelper::toInteger($pks);
		$userId = (int) $userId;
		$state  = (int) $state;

		// If there are no primary keys set check to see if the instance key is set.
		if (empty($pks))
		{
			if ($this->$k) {
				$pks = array($this->$k);
			}
			// Nothing to set publishing state on, return false.
			else {
				$this->setError(JText::_('JLIB_DATABASE_ERROR_NO_ROWS_SELECTED'));
				return false;
			}
		}

		// Get an instance of the table
		$table = JTable::getInstance('Sponsor', 'TracksTable');

		// For all keys
		foreach ($pks as $pk)
		{
			// Load the banner
			if(!$table->load($pk))
			{
				$this->setError($table->getError());
			}

			// Verify checkout
			if($table->checked_out==0 || $table->checked_out==$userId)
			{
				// Change the state
				$table->state = $state;
				$table->checked_out=0;
				$table->checked_out_time=$this->_db->getNullDate();

				// Check the row
				$table->check();

				// Store the row
				if (!$table->store())
				{
					$this->setError($table->getError());
				}
			}
		}
		return count($this->getErrors())==0;
	}
	
	public function check()
	{
		$alias = JFilterOutput::stringURLSafe($this->name);
		
		if(empty($this->alias) || $this->alias === $alias ) {
			$this->alias = $alias;
		}
		
		return true;
	}
}