<?php
/**
 * @package     Tracks
 * @subpackage  Admin
 * @copyright   Tracks (C) 2008-2015 Julien Vonthron. All rights reserved.
 * @license     GNU General Public License version 2 or later
 */

defined('_JEXEC') or die();

/**
 * Tracks Component project round Table
 *
 * @package     Tracks
 * @subpackage  Admin
 * @since       3.0
 */
class TracksTableProjectround extends TrackslibTable
{
	/**
	 * The name of the table with category
	 *
	 * @var string
	 * @since 0.9.1
	 */
	protected $_tableName = 'tracks_projects_rounds';

	/**
	 * Overloaded check method to ensure data integrity
	 *
	 * @return boolean
	 */
	public function check()
	{
		if (!$this->project_id)
		{
			$this->setError(JText::_('COM_TRACKS_Error_check_missing_project_id'));

			return false;
		}

		if (!$this->round_id)
		{
			$this->setError(JText::_('COM_TRACKS_Error_check_missing_round_id'));

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

		return $this->deleteProjectroundEvents($pk);
	}

	/**
	 * Delete project round events
	 *
	 * @param   mixed  $pk  An optional primary key value to delete.  If not set the instance property value is used.
	 *
	 * @return  boolean  True on success.
	 */
	protected function deleteProjectroundEvents($pk)
	{
		$pks = $this->getPkArray($pk);
		$pks = implode(', ', RHelperArray::quote($pks));

		$query = $this->_db->getQuery(true)
			->select('id')
			->from('#__tracks_events')
			->where('projectround_id IN (' . $pks . ')');

		$this->_db->setQuery($query);
		$eventIds = $this->_db->loadColumn();

		$table = RTable::getAdminInstance('Event');

		foreach ($eventIds as $id)
		{
			$table->delete((int) $id);
		}

		return true;
	}
}
