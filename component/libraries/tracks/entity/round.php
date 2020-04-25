<?php
/**
 * @package     Tracks.library
 * @subpackage  Entity
 * @copyright   Tracks (C) 2008-2015 Julien Vonthron. All rights reserved.
 * @license     GNU General Public License version 2 or later
 */

defined('_JEXEC') or die;

/**
 * Round Entity.
 *
 * @since  3.0.6
 */
class TrackslibEntityRound extends TrackslibEntityBase
{
	/**
	 * Get project rounds
	 *
	 * @return TrackslibEntityProjectround[]
	 */
	public function getProjectrounds($state = [])
	{
		if (!$this->hasId())
		{
			return;
		}

		$state = array_merge(
			[
				'filter.round_id' => $this->id,
				'list.limit'      => 0
			],
			$state
		);

		$model = JModelAdmin::getInstance('Projectrounds', 'TracksModel', ['ignore_request' => true]);

		return $model->searchEntities($state);
	}
}
