<?php
/**
 * @package     Tracks.library
 * @subpackage  Entity
 * @copyright   Tracks (C) 2008-2015 Julien Vonthron. All rights reserved.
 * @license     GNU General Public License version 2 or later
 */

defined('_JEXEC') or die;

/**
 * Event Entity.
 *
 * @since  __deploy_version__
 */
class TrackslibEntityEvent extends TrackslibEntityBase
{
	/**
	 * Return project type
	 *
	 * @return TrackslibEntityProjectround
	 */
	public function getProjectRound()
	{
		$item = $this->loadItem();

		if (!$item)
		{
			return false;
		}

		return TrackslibEntityProjectround::load($item->projectround_id);
	}

	/**
	 * Return project type
	 *
	 * @return TrackslibEntityProject
	 */
	public function getEventtype()
	{
		$item = $this->loadItem();

		if (!$item)
		{
			return false;
		}

		return TrackslibEntityEventtype::load($item->type);
	}
}
