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
 * @since       3.0
 */
class TracksTableCompetition extends RTable
{
	/**
	 * The name of the table with category
	 *
	 * @var string
	 * @since 0.9.1
	 */
	protected $_tableName = 'tracks_competitions';

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

	/**
	 * Called before delete().
	 *
	 * @param   mixed  $pk  An optional primary key value to delete.  If not set the instance property value is used.
	 *
	 * @return  boolean  True on success.
	 */
	protected function beforeDelete($pk = null)
	{
		// Initialise variables.
		$k = $this->_tbl_key;

		// Received an array of ids?
		if (is_array($pk))
		{
			// Sanitize input.
			JArrayHelper::toInteger($pk);
			$pk = RHelperArray::quote($pk);
			$pk = implode(',', $pk);
		}

		$pk = (is_null($pk)) ? $this->$k : $pk;

		// If no primary key is given, return false.
		if ($pk === null)
		{
			return false;
		}

		// Make sure it's not being used in projects
		$db = $this->_db;
		$query = $db->getQuery(true);

		$query->select('p.id');
		$query->from('#__tracks_projects AS p');
		$query->where('p.competition_id_id in (' . $pk . ')');
		$db->setQuery($query);
		$res = $db->loadObject();

		if ($res)
		{
			$this->setError(Jtext::_('COM_TRACKS_COMPETITION_DELETE_ERROR_ASSIGNED'));

			return false;
		}

		return true;
	}
}
