<?php
/**
 * @package     Tracks
 * @subpackage  Admin
 * @copyright   Tracks (C) 2008-2015 Julien Vonthron. All rights reserved.
 * @license     GNU General Public License version 2 or later
 */

defined('_JEXEC') or die();

/**
 * Rounds Table class
 * Rounds can be GP for formula one, stages for multi-day bicyle racing, etc...
 *
 * @package     Tracks
 * @subpackage  Admin
 * @since       0.1
 */
class TracksTableRound extends RTable
{
	protected $_tableName = 'tracks_rounds';

	/**
	 * Overloaded check method to ensure data integrity
	 *
	 * @access public
	 *
	 * @return boolean True on success
	 *
	 * @since  1.0
	 */
	public function check()
	{
		$alias = JFilterOutput::stringURLSafe($this->name);

		if (empty($this->alias) || $this->alias === $alias)
		{
			$this->alias = $alias;
		}

		return true;
	}
}
