<?php
/**
 * @package     Tracks
 * @subpackage  Admin
 * @copyright   Tracks (C) 2008-2015 Julien Vonthron. All rights reserved.
 * @license     GNU General Public License version 2 or later
 */

defined('_JEXEC') or die();

/**
 * Tracks Component events Model
 *
 * @package     Tracks
 * @subpackage  Admin
 * @since       3.0
 */
class TracksModelEvent extends RModelAdmin
{
	/**
	 * Method to get a single record.
	 *
	 * @param   integer  $pk  The id of the primary key.
	 *
	 * @return  mixed    Object on success, false on failure.
	 */
	public function getItem($pk = null)
	{
		$item = parent::getItem($pk);

		if (!$item->projectround_id)
		{
			// Get the prid of the record from the request.
			$prid = JFactory::getApplication()->input->getInt('projectround_id');

			if (!$prid)
			{
				throw new RuntimeException('projectround id is required');
			}

			$item->projectround_id = $prid;
		}

		return $item;
	}
}
