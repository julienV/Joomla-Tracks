<?php
/**
 * @package     Tracks
 * @subpackage  Admin
 * @copyright   Tracks (C) 2008-2015 Julien Vonthron. All rights reserved.
 * @license     GNU General Public License version 2 or later
 */

defined('_JEXEC') or die();

/**
 * Tracks Component Season Table
 *
 * @package     Tracks
 * @subpackage  Admin
 * @since       3.0
 */
class TracksTableSeason extends RTable
{
	/**
	 * The name of the table
	 *
	 * @var string
	 * @since 0.9.1
	 */
	protected $_tableName = 'tracks_seasons';

	/**
	 * The primary key of the table
	 *
	 * @var string
	 * @since 0.9.1
	 */
	protected $_tableKey = 'id';

	/**
	 * Overloaded check method to ensure data integrity
	 *
	 * @access  public
	 *
	 * @return  boolean True on success
	 *
	 * @since   1.0
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
