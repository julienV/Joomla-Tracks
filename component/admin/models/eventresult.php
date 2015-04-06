<?php
/**
 * @package     Tracks
 * @subpackage  Admin
 * @copyright   Tracks (C) 2008-2015 Julien Vonthron. All rights reserved.
 * @license     GNU General Public License version 2 or later
 */

defined('_JEXEC') or die();

/**
 * Tracks Component eventresults Model
 *
 * @package     Tracks
 * @subpackage  Admin
 * @since       3.0
 */
class TracksModelEventresult extends RModelAdmin
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

		if (!$item->event_id)
		{
			// Get the prid of the record from the request.
			$id = JFactory::getApplication()->input->getInt('event_id');

			if (!$id)
			{
				throw new RuntimeException('event_id id is required');
			}

			$item->event_id = $id;
		}

		return $item;
	}

	/**
	 * Update performance
	 *
	 * @param   int     $id        result id
	 * @param   string  $property  property to update
	 * @param   string  $val       value
	 *
	 * @return bool
	 */
	public function update($id, $property, $val)
	{
		switch ($property)
		{
			case 'rank':
				$val = (int) $val;
				break;

			case 'performance':
				break;

			case 'bonuspoints':
				$property = 'bonus_points';
				break;

			default:
				throw new RuntimeException('Unknown property');
		}

		$table = $this->getTable();

		if (!$table->load($id))
		{
			throw new RuntimeException('Result not found');
		}

		$table->{$property} = $val;

		if (!$table->store())
		{
			throw new RuntimeException('Update Result table failed');
		}

		return true;
	}
}
