<?php
/**
 * @package     Tracks.library
 * @subpackage  Entity
 * @copyright   Tracks (C) 2008-2015 Julien Vonthron. All rights reserved.
 * @license     GNU General Public License version 2 or later
 */

use Tracks\Helper\Config;

defined('_JEXEC') or die;

require_once JPATH_SITE . '/administrator/components/com_tracks/models/participants.php';

/**
 * Individual Entity.
 * @property int    $id
 * @property string $first_name
 * @property string $last_name
 * @property string $nickname
 *
 * @since  __deploy_version__
 */
class TrackslibEntityIndividual extends TrackslibEntityBase
{
	/**
	 * Get associated participants
	 *
	 * @return TrackslibEntityParticipant[]
	 */
	public function getParticipants($ordering = 'project.ordering desc')
	{
		$db    = \JFactory::getDbo();
		$query = $db->getQuery(true)
			->select('p.*')
			->from('#__tracks_participants AS p')
			->innerJoin('#__tracks_projects as project ON project.id = p.project_id')
			->where('p.individual_id = ' . (int) $this->id)
			->where('project.published = 1')
			->order($ordering);

		$db->setQuery($query);

		return array_map(
			function ($row)
			{
				$instance = TrackslibEntityParticipant::getInstance($row->id);
				$instance->bind($row);

				return $instance;
			},
			$db->loadObjectList()
		);
	}

	/**
	 * Get stats
	 *
	 * @return array
	 */
	public function getStats()
	{
		$participants = $this->getParticipants();

		$stats = [
			'projects' => count($participants),
			'results'  => [],
			'starts'   => 0,
			'points'   => 0,
			'wins'     => 0,
			'podiums'  => 0,
		];

		foreach ($participants as $participant)
		{
			$rankingtool = $participant->getProject()->getRankingtool();
			$ranking     = $rankingtool->getIndividualRanking($this->id);

			$stats['results'][] = [
				'project'     => $participant->getProject(),
				'rankingtool' => $rankingtool,
				'ranking'     => $ranking
			];

			$stats['starts'] += count($ranking->finishes);
			$stats['points']   += $ranking->points;
			$stats['wins']     += TrackslibHelperTools::getCustomTop($ranking, 1);
			$stats['podiums']  += TrackslibHelperTools::getCustomTop($ranking, 3);
		}

		return $stats;
	}

	/**
	 * Get name from first and last name
	 *
	 * @return string
	 */
	public function getFullName()
	{
		if (Config::get('nickname_over_name', 0))
		{
			return $this->nickname;
		}

		$name = [];

		if ($this->first_name)
		{
			$name[] = $this->first_name;
		}

		if ($this->last_name)
		{
			$name[] = $this->last_name;
		}

		return implode(' ', $name);
	}
}
