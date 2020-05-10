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
	 * @var TrackslibEntityParticipant[]
	 */
	private $participants;

	/**
	 * Get associated projects
	 *
	 * @param   array  $state  state
	 *
	 * @return TrackslibEntityProject[]
	 */
	public function getProjects($state = [])
	{
		$order = !empty($state['list.ordering']) ? $state['list.ordering'] : 'project.ordering DESC';
		$limit = !empty($state['list.limit']) ? $state['list.limit'] : 0;
		$start = !empty($state['list.start']) ? $state['list.start'] : 0;

		$db    = \JFactory::getDbo();
		$query = $db->getQuery(true)
			->select('project.*')
			->from('#__tracks_participants AS p')
			->innerJoin('#__tracks_projects as project ON p.project_id = project.id')
			->where('project.published = 1')
			->where('p.team_id = ' . (int) $this->id)
			->group('project.id')
			->order($order);

		$db->setQuery($query, $start, $limit);
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
	 * Get team participants to projects
	 *
	 * @param   array  $state  state
	 *
	 * @return TrackslibEntityParticipant[]
	 */
	public function getParticipants()
	{
		if (!is_null($this->participants))
		{
			return $this->participants;
		}

		$order = 'project.ordering DESC, i.last_name ASC, i.first_name ASC';

		$db    = \JFactory::getDbo();
		$query = $db->getQuery(true)
			->select('p.*')
			->from('#__tracks_participants AS p')
			->innerJoin('#__tracks_projects as project ON p.project_id = project.id')
			->innerJoin('#__tracks_individuals as i ON i.id = p.individual_id')
			->where('project.published = 1')
			->where('p.team_id = ' . (int) $this->id)
			->group('p.id')
			->order($order);

		$db->setQuery($query);
		$rows = $db->loadObjectList();

		$this->participants = empty($rows) ? $rows
			: array_map(
				function ($row)
				{
					$entity = TrackslibEntityParticipant::getInstance($row->id);
					$entity->bind($row);

					return $entity;
				},
				$rows
			);

		return $this->participants;
	}

	/**
	 * Get participants of one project
	 *
	 * @param   int  $projectId  project id
	 *
	 * @return TrackslibEntityParticipant[]
	 */
	public function getProjectParticipants($projectId)
	{
		$allParticipants = $this->getParticipants();

		return empty($allParticipants) ? []
			: array_filter(
				$allParticipants,
				function (TrackslibEntityParticipant $participant) use ($projectId)
				{
					return $participant->project_id == $projectId;
				}
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
