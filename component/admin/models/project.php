<?php
/**
 * @package     Tracks
 * @subpackage  Admin
 * @copyright   Tracks (C) 2008-2015 Julien Vonthron. All rights reserved.
 * @license     GNU General Public License version 2 or later
 */

defined('_JEXEC') or die();

/**
 * Tracks Component projects Model
 *
 * @package     Tracks
 * @subpackage  Admin
 * @since       3.0
 */
class TracksModelProject extends RModelAdmin
{
	/**
	 * Deep copy with rounds and subrounds
	 *
	 * @param   array  $cid  project ids
	 *
	 * @return void
	 */
	public function copy($cid)
	{
		foreach ($cid as $projectId)
		{
			$project = RTable::getAdminInstance('Project');
			$project->load($projectId);

			$newProject = clone $project;
			$newProject->id = null;
			$newProject->name = JText::sprintf('COM_TRACKS_PROJECT_COPY_OF_S', $project->name);

			$newProject->store();

			$this->copyProjectRounds($projectId, $newProject->id);
		}
	}

	/**
	 * Copy project rounds from one project to the other
	 *
	 * @param   int  $source       source project id
	 * @param   int  $destination  destination project id
	 *
	 * @return void
	 */
	protected function copyProjectRounds($source, $destination)
	{
		$query = $this->_db->getQuery(true)
			->select('id')
			->from('#__tracks_projects_rounds')
			->where('project_id = ' . $source);

		$this->_db->setQuery($query);
		$projectRoundIds = $this->_db->loadColumn();

		if ($projectRoundIds)
		{
			$model = RModel::getAdminInstance('Projectround');
			$model->copy($projectRoundIds, $destination);
		}
	}
}
