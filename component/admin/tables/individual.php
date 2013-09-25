<?php
/**
 * @version    $Id: individual.php 13 2008-02-05 16:25:22Z julienv $
 * @package    JoomlaTracks
 * @copyright  Copyright (C) 2008 Julien Vonthron. All rights reserved.
 * @license    GNU/GPL, see LICENSE.php
 *                 Joomla Tracks is free software. This version may have been modified pursuant
 *                 to the GNU General Public License, and as distributed it includes or
 *                 is derivative of works licensed under the GNU General Public License or
 *                 other free or open source software licenses.
 *                 See COPYRIGHT.php for copyright notices and details.
 */

// No direct access
defined('_JEXEC') or die('Restricted access');

// Include library dependencies
jimport('joomla.filter.input');

/**
 * Individuals Table class
 *
 * @package  Tracks
 * @since    0.1
 */
class TracksTableIndividual extends FOFTable
{
	/**
	 * constructor
	 *
	 * @param   string    $table  name of the table
	 * @param   string    $key    table primary key
	 * @param   database  &$db    A database connector object
	 */
	public function __construct($table, $key, &$db)
	{
		parent::__construct('#__tracks_individuals', 'id', $db);
	}

	/**
	 * Overloaded check method to ensure data integrity
	 *
	 * @access public
	 *
	 * @return boolean True on success
	 *
	 * @since  1.0
	 */
	function check()
	{
		$alias = JFilterOutput::stringURLSafe($this->first_name . '-' . $this->last_name);

		if (empty($this->alias) || $this->alias === $alias)
		{
			$this->alias = $alias;
		}

		// Should check name unicity
		return true;
	}
	/**
	 * Check out (lock) a record
	 *
	 * @param   integer  $userId  The locking user's ID
	 * @param   integer  $oid     The primary key value of the record to lock
	 *
	 * @return  boolean  True on success
	 */
	public function checkout($userId, $oid = null)
	{
		$fldLockedBy = $this->getColumnAlias('locked_by');
		$fldLockedOn = $this->getColumnAlias('locked_on');

		if (!(in_array($fldLockedBy, $this->getKnownFields())
			|| in_array($fldLockedOn, $this->getKnownFields())))
		{
			return true;
		}


		$k = $this->_tbl_key;

		if ($oid !== null)
		{
			$this->$k = $oid;
		}

		// No primary key defined, stop here
		if (!$this->$k)
		{
			return false;
		}

		$date = JFactory::getDate();

		if (FOFPlatform::getInstance()->checkVersion(JVERSION, '3.0', 'ge'))
		{
			$time = $date->toSql();
		}
		else
		{
			$time = $date->toMysql();
		}

		$query = $this->_db->getQuery(true)
			->update($this->_db->qn($this->_tbl))
			->set(
				array(
					$this->_db->qn($fldLockedBy) . ' = ' . $this->_db->q((int) $userId),
					$this->_db->qn($fldLockedOn) . ' = ' . $this->_db->q($time)
				)
			)
			->where($this->_db->qn($this->_tbl_key) . ' = ' . $this->_db->q($this->$k));
		$this->_db->setQuery((string) $query);

		$this->$fldLockedBy = $userId;
		$this->$fldLockedOn = $time;

		return $this->_db->execute();
	}
}
