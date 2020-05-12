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
class TracksModelRound extends TrackslibModelFrontbase
{
	/**
	 * Get round
	 *
	 * @param   int  $round_id  round id
	 *
	 * @return mixed|null
	 */
	public function getRound($round_id)
	{
		return $this->getItem($round_id);
	}
}
