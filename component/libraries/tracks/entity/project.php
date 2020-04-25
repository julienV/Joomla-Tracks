<?php
/**
 * @package     Tracks.library
 * @subpackage  Entity
 * @copyright   Tracks (C) 2008-2015 Julien Vonthron. All rights reserved.
 * @license     GNU General Public License version 2 or later
 */

defined('_JEXEC') or die;

/**
 * Project Entity.
 *
 * @property integer $id
 * @property integer $competition_id
 * @property integer $season_ids
 *
 * @since  3.0
 */
class TrackslibEntityProject extends TrackslibEntityBase
{
	protected $rankingTool;

	/**
	 * Return project type
	 *
	 * @return boolean|mixed
	 */
	public function getProjectType()
	{
		return $this->type;
	}

	/**
	 * Get ranking tool for project
	 *
	 * @return TrackslibRankingtoolDefault
	 */
	public function getRankingTool()
	{
		if (empty($this->rankingTool))
		{
			JPluginHelper::importPlugin('tracks_projecttype');
			RFactory::getDispatcher()->trigger('onTracksGetRankingTool', array($this->id, &$this->rankingTool));
		}

		return clone $this->rankingTool;
	}

	/**
	 * Get competition
	 *
	 * @return TrackslibEntityCompetition
	 */
	public function getCompetition ()
	{
		return TrackslibEntityCompetition::load($this->competition_id);
	}

	/**
	 * Get season
	 *
	 * @return TrackslibEntitySeason
	 */
	public function getSeason ()
	{
		return TrackslibEntitySeason::load($this->season_id);
	}
}
