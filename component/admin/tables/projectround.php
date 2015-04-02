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
class TracksTableProjectround extends RTable
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
}
