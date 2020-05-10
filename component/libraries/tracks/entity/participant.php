<?php
/**
 * @package     Tracks.library
 * @subpackage  Entity
 * @copyright   Tracks (C) 2008-2015 Julien Vonthron. All rights reserved.
 * @license     GNU General Public License version 2 or later
 */

defined('_JEXEC') or die;

/**
 * Participant Entity.
 *
 * @property int  $id
 * @property int  $project_id
 * @property int  $individual_id
 *
 * @since  __deploy_version__
 */
class TrackslibEntityParticipant extends TrackslibEntityBase
{
	/**
	 * Override name
	 *
	 * @param   string  $name  table name
	 *
	 * @return JTable|RTable
	 */
	protected function getTable($name = null)
	{
		if (null === $name)
		{
			$name = "Participants";
		}

		$name = str_replace('Entity', '', $name);

		return RTable::getAdminInstance($name, array(), $this->getComponent());
	}

	/**
	 * Get project
	 *
	 * @return bool|TrackslibEntityProject
	 */
	public function getProject()
	{
		if (!$this->hasId())
		{
			return false;
		}

		return TrackslibEntityProject::load($this->project_id);
	}

	/**
	 * Get individual
	 *
	 * @return boolean|TrackslibEntityIndividual
	 */
	public function getIndividual()
	{
		if (!$this->hasId())
		{
			return false;
		}

		return TrackslibEntityIndividual::load($this->individual_id);
	}

	/**
	 * Get name from first and last name
	 *
	 * @return string
	 */
	public function getFullName()
	{
		return $this->getIndividual()->getFullName();
	}

	/**
	 * Get full ranking data object
	 *
	 * @return array
	 */
	public function getRankingData()
	{
		return $this->getProject()->getRankingTool()->getIndividualRanking($this->individual_id);
	}

	/**
	 * Get rank
	 *
	 * @return mixed
	 */
	public function getRank()
	{
		return $this->getRankingData()->rank;
	}

	/**
	 * Get points
	 *
	 * @return mixed
	 */
	public function getPoints()
	{
		return $this->getRankingData()->points;
	}

	/**
	 * Get wins
	 *
	 * @return int
	 */
	public function getWins()
	{
		return TrackslibHelperTools::getCustomTop($this->getRankingData(), 1);
	}

	/**
	 * Get finishes below rank
	 *
	 * @param   int  $rank  rank limit
	 *
	 * @return int
	 */
	public function getTop($rank)
	{
		return TrackslibHelperTools::getCustomTop($this->getRankingData(), $rank);
	}
}
