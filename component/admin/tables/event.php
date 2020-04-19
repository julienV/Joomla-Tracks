<?php
/**
 * @package     Tracks
 * @subpackage  Admin
 * @copyright   Tracks (C) 2008-2015 Julien Vonthron. All rights reserved.
 * @license     GNU General Public License version 2 or later
 */

defined('_JEXEC') or die();

/**
 * Tracks Component Event Table
 *
 * @package     Tracks
 * @subpackage  Admin
 * @since       3.0
 */
class TracksTableEvent extends TrackslibTable
{
	/**
	 * The name of the table with category
	 *
	 * @var string
	 * @since 0.9.1
	 */
	protected $_tableName = 'tracks_events';

	/**
	 * Field name to publish/unpublish/trash table registers. Ex: state
	 *
	 * @var  string
	 */
	protected $_tableFieldState = 'published';

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
		if (!$this->projectround_id)
		{
			$this->setError('project round id is required');

			return false;
		}

		if (!$this->type)
		{
			$this->setError('event type id is required');

			return false;
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
		if (!parent::beforeDelete($pk))
		{
			return false;
		}

		return $this->deleteResults($pk);
	}

	/**
	 * Delete project round events
	 *
	 * @param   mixed  $pk  An optional primary key value to delete.  If not set the instance property value is used.
	 *
	 * @return  boolean  True on success.
	 */
	protected function deleteResults($pk)
	{
		$pks = $this->getPkArray($pk);
		$pks = implode(', ', RHelperArray::quote($pks));

		$query = $this->_db->getQuery(true)
			->delete('#__tracks_events_results')
			->where('event_id IN (' . $pks . ')');

		$this->_db->setQuery($query);
		$this->_db->execute();

		return true;
	}
}
