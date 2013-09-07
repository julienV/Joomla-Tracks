<?php
/**
 * @version    2.0
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
 * Subrounds type Table class
 * A round is composed of subround, of differents types.
 * E.g, a formula 1 grand prix is composed of pratice sessions,
 * a qualifying session, and the race. All of those are subrounds types.
 *
 * @package  Tracks
 * @since    0.1
 */
class TracksTableSubroundtype extends FOFTable
{
	/**
	 * constructor
	 *
	 * @param   string           $table  name of the table
	 * @param   string           $key    table primary key
	 * @param   JDatabaseDriver  &$db    A database connector object
	 */
	public function __construct($table, $key, &$db)
	{
		parent::__construct('#__tracks_subroundtypes', 'id', $db);
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
		$alias = JFilterOutput::stringURLSafe($this->name);

		if (empty($this->alias) || $this->alias === $alias)
		{
			$this->alias = $alias;
		}

		return true;
	}
}
