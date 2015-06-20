<?php
/**
 * @package     Tracks
 * @subpackage  Admin
 * @copyright   Tracks (C) 2008-2015 Julien Vonthron. All rights reserved.
 * @license     GNU General Public License version 2 or later
 */

defined('_JEXEC') or die();

/**
 * Tracks Component Base Table
 *
 * @package     Tracks
 * @subpackage  Admin
 * @since       3.0
 */
class TrackslibTable extends RTable
{
	/**
	 * Sanitize pk input array for delete function, use instance key if empty
	 *
	 * @param   mixed  $pks  optional id or array of ids
	 *
	 * @return array|bool|null
	 *
	 * @throws Exception
	 */
	protected function getPkArray($pks = null)
	{
		// Initialise variables.
		$k = $this->_tbl_key;

		if ($pks && !is_array($pks))
		{
			$pks = array($pks);
		}

		// Sanitize input.
		JArrayHelper::toInteger($pks);

		// If there are no primary keys set check to see if the instance key is set.
		if (empty($pks))
		{
			if ($this->$k)
			{
				$pks = array($this->$k);
			}
			else
			{
				throw new Exception(JText::_('JLIB_DATABASE_ERROR_NO_ROWS_SELECTED'));
			}
		}

		return $pks;
	}
}
