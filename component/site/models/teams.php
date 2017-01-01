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
class TracksModelTeams extends TrackslibModelFrontbase
{
	/**
	 * Get data
	 *
	 * @return mixed
	 */
	public function getData()
	{
		$query = ' SELECT t.id, t.name, t.country_code, t.picture_small AS team_logo, '
			. ' CASE WHEN CHAR_LENGTH( t.alias ) THEN CONCAT_WS( \':\', t.id, t.alias ) ELSE t.id END AS slug '
			. ' FROM #__tracks_teams as t '
			. ' ORDER BY t.name ASC ';

		$this->_db->setQuery($query);

		return $this->_db->loadObjectList();
	}
}
