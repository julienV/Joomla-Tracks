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
	 * @return bool|TrackslibEntityProject
	 */
	public function getIndividual()
	{
		if (!$this->hasId())
		{
			return false;
		}

		return TrackslibEntityIndividual::load($this->individual_id);
	}
}
