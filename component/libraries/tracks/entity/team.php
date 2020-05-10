<?php
/**
 * @package     Tracks.library
 * @subpackage  Entity
 * @copyright   Tracks (C) 2008-2015 Julien Vonthron. All rights reserved.
 * @license     GNU General Public License version 2 or later
 */

defined('_JEXEC') or die;

/**
 * Team Entity.
 *
 * @since  __deploy_version__
 */
class TrackslibEntityTeam extends TrackslibEntityBase
{
	/**
	 * Get associated projects
	 *
	 * @return TrackslibEntityProject[]
	 */
	public function getProjects()
	{
		$db = \JFactory::getDbo();
		$query = $db->getQuery(true)
			->select('project.*')
			->from('#__tracks_participants AS p')
			->innerJoin('#__tracks_projects as project ON p.project_id = project.id')
			->where('project.published = 1')
			->where('p.team_id = ' . (int) $this->id)
			->group('project.id')
			->order('project.ordering DESC');

		$db->setQuery($query);
		$rows = $db->loadObjectList();

		return empty($rows) ? $rows
			: array_map(
				function ($row)
				{
					$entity = TrackslibEntityProject::getInstance($row->id);
					$entity->bind($row);

					return $entity;
				},
				$rows
			);
	}

	/**
	 * Get stats
	 *
	 * @return array
	 */
	public function getStats()
	{
		$projects = $this->getProjects();

		$stats = [
			'projects' => count($projects),
			'results'  => [],
			'starts'   => 0,
			'points'   => 0,
			'wins'     => 0,
			'podiums'  => 0,
		];

		foreach ($projects as $project)
		{
			$rankingtool = $project->getRankingtool();
			$ranking     = $rankingtool->getTeamRanking($this->id);

			$stats['results'][] = [
				'project'     => $project,
				'rankingtool' => $rankingtool,
				'ranking'     => $ranking
			];

			$stats['starts']  += count($ranking->finishes);
			$stats['points']  += $ranking->points;
			$stats['wins']    += TrackslibHelperTools::getCustomTop($ranking, 1);
			$stats['podiums'] += TrackslibHelperTools::getCustomTop($ranking, 3);
		}

		return $stats;
	}
}
