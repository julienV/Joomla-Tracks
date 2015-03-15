<?php
/**
 * @package     Tracks
 * @subpackage  Admin
 * @copyright   Tracks (C) 2008-2015 Julien Vonthron. All rights reserved.
 * @license     GNU General Public License version 2 or later
 */

defined('_JEXEC') or die();

/**
 * Subrounds type Table class
 * A round is composed of subround, of differents types.
 * E.g, a formula 1 grand prix is composed of pratice sessions,
 * a qualifying session, and the race. All of those are subrounds types.
 *
 * @package     Tracks
 * @subpackage  Admin
 * @since       0.1
 */
class TracksTableEventtype extends RTable
{
	protected $_tableName = 'tracks_eventtypes';

	/**
	 * Overloaded check method to ensure data integrity
	 *
	 * @access public
	 *
	 * @return boolean True on success
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
