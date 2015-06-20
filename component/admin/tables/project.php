<?php
/**
 * @package     Tracks
 * @subpackage  Admin
 * @copyright   Tracks (C) 2008-2015 Julien Vonthron. All rights reserved.
 * @license     GNU General Public License version 2 or later
 */

defined('_JEXEC') or die();

/**
 * Tracks Component Project Table
 *
 * @package     Tracks
 * @subpackage  Admin
 * @since       3.0
 */
class TracksTableProject extends TrackslibTable
{
	/**
	 * The name of the table with category
	 *
	 * @var string
	 * @since 0.9.1
	 */
	protected $_tableName = 'tracks_projects';

	/**
	 * Field name to publish/unpublish/trash table registers. Ex: state
	 *
	 * @var  string
	 */
	protected $_tableFieldState = 'published';

	/**
	 * Overloaded check method to ensure data integrity
	 *
	 * @return boolean True on success
	 */
	public function check()
	{
		if ($this->name == '')
		{
			$this->setError(JText::_('COM_TRACKS__Name_is_mandatory '));

			return false;
		}

		$alias = JFilterOutput::stringURLSafe($this->name);

		if (empty($this->alias) || $this->alias === $alias)
		{
			$this->alias = $alias;
		}

		if (!$this->season_id)
		{
			$this->setError(JText::_('COM_TRACKS__Season_is_mandatory '));

			return false;
		}

		if (!$this->competition_id)
		{
			$this->setError(JText::_('COM_TRACKS__Competition_is_mandatory '));

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

		return $this->deleteProjectrounds($pk);
	}

	/**
	 * Delete project rounds
	 *
	 * @param   mixed  $pk  An optional primary key value to delete.  If not set the instance property value is used.
	 *
	 * @return  boolean  True on success.
	 */
	protected function deleteProjectrounds($pk)
	{
		$pks = $this->getPkArray($pk);
		$pks = implode(', ', RHelperArray::quote($pks));

		$query = $this->_db->getQuery(true)
			->select('id')
			->from('#__tracks_projects_rounds')
			->where('project_id IN (' . $pks. ')');

		$this->_db->setQuery($query);
		$projectRoundIds = $this->_db->loadColumn();

		$table = RTable::getAdminInstance('Projectround');

		foreach ($projectRoundIds as $prid)
		{
			$table->delete((int) $prid);
		}

		return true;
	}
}
