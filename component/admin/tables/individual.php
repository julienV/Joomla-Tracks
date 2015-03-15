<?php
/**
 * @package     Tracks
 * @subpackage  Admin
 * @copyright   Tracks (C) 2008-2015 Julien Vonthron. All rights reserved.
 * @license     GNU General Public License version 2 or later
 */

defined('_JEXEC') or die();

/**
 * Tracks Component Competition Table
 *
 * @package     Tracks
 * @subpackage  Admin
 * @since       0.1
 */
class TracksTableIndividual extends RTable
{
	protected $_tableName = 'tracks_individuals';

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
		$alias = JFilterOutput::stringURLSafe($this->first_name . '-' . $this->last_name);

		if (empty($this->alias) || $this->alias === $alias)
		{
			$this->alias = $alias;
		}

		// Should check name unicity
		return true;
	}
}
