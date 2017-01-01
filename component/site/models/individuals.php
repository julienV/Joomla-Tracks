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
class TracksModelIndividuals extends TrackslibModelFrontbase
{
	/**
	 * Get data
	 *
	 * @return mixed
	 */
	public function getData()
	{
		$ordering = JFactory::getApplication()->getParams('com_tracks')->get('ordering', 0);
		$order    = $ordering ? ' ORDER BY i.first_name, i.last_name ASC ' : ' ORDER BY i.last_name ASC, i.first_name ASC ';
		$query    = ' SELECT i.id, i.first_name, i.last_name, i.country_code, '
			. ' CASE WHEN CHAR_LENGTH( i.alias ) THEN CONCAT_WS( \':\', i.id, i.alias ) ELSE i.id END AS slug '
			. ' FROM #__tracks_individuals as i '
			. $order;

		$this->_db->setQuery($query);

		return $this->_db->loadObjectList();
	}
}
