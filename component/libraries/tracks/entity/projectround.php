<?php
/**
 * @package     Tracks.library
 * @subpackage  Entity
 * @copyright   Tracks (C) 2008-2015 Julien Vonthron. All rights reserved.
 * @license     GNU General Public License version 2 or later
 */

defined('_JEXEC') or die;

/**
 * Project round Entity.
 *
 * @property integer id
 *
 * @since  3.0.6
 */
class TrackslibEntityProjectround extends TrackslibEntityBase
{
	/**
	 * Return project type
	 *
	 * @return TrackslibEntityProject
	 */
	public function getProject()
	{
		$item = $this->loadItem();

		if (!$item)
		{
			return false;
		}

		return TrackslibEntityProject::load($item->project_id);
	}

	/**
	 * Return project type
	 *
	 * @return boolean|mixed
	 */
	public function getRound()
	{
		$item = $this->loadItem();

		if (!$item)
		{
			return false;
		}

		return TrackslibEntityRound::load($item->round_id);
	}

	protected function getBaseUrl()
	{
		return 'index.php?option=com_tracks&view=roundresult';
	}


}
