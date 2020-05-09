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
	/**
	 * Get the item link
	 *
	 * @param   mixed    $itemId  Specify a custom itemId if needed. Default: joomla way to use active itemid
	 * @param   boolean  $routed  Process URL with JRoute?
	 * @param   boolean  $xhtml   Replace & by &amp; for XML compliance.
	 *
	 * @return  string
	 */
	public function getLink($itemId = 'inherit', $routed = true, $xhtml = true)
	{
		if (!$this->hasId())
		{
			return null;
		}

		$url = $this->getBaseUrl() . '&id=' . $this->getSlug() . '&p=' . $this->project_id
			. $this->getLinkItemIdString($itemId);

		return $this->formatUrl($url, $routed, $xhtml);
	}

	/**
	 * Get round winners
	 *
	 * @return array
	 */
	public function getWinner()
	{
		return array_filter(
			$rankings = $this->getProject()->getRankingTool()->getIndividualsRankings($this->id),
			function ($result)
			{
				return $result->rank == 1;
			}
		);
	}
}
