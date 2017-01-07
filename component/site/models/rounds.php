<?php
/**
 * @package    Tracks.Site
 * @copyright  Tracks (C) 2008-2015 Julien Vonthron. All rights reserved.
 * @license    GNU General Public License version 2 or later
 */

// No direct access
defined('_JEXEC') or die('Restricted access');

/**
 * Joomla Tracks Component Front page Model
 *
 * @package  Tracks
 * @since    0.1
 */
class TracksModelRounds extends TracksModelFrontbase
{
	/**
	 * Get rounds
	 *
	 * @return mixed
	 */
	public function getRounds()
	{
		$query = ' SELECT r.id, r.name, '
			. ' CASE WHEN CHAR_LENGTH( r.alias ) THEN CONCAT_WS( \':\', r.id, r.alias ) ELSE r.id END AS slug '
			. ' FROM #__tracks_rounds AS r '
			. ' WHERE r.published = 1 '
			. ' ORDER BY r.name ASC ';

		$this->_db->setQuery($query);

		return $this->_db->loadObjectList();
	}
}
