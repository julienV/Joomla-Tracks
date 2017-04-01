<?php
/**
 * @package     Tracks
 * @subpackage  Library
 * @copyright   Tracks (C) 2008-2015 Julien Vonthron. All rights reserved.
 * @license     GNU General Public License version 2 or later
 */

defined('_JEXEC') or die;

/**
 * Default Ranking Tool
 *
 * @package     Tracks
 * @subpackage  Library
 * @since       1.0
 */
class TracksRankingToolDefault extends JObject
{
	/**
	 * project id
	 */
	var $_project_id = null;

	/**
	 * associated project
	 */
	var $_project = null;

	/**
	 * results
	 */
	var $_results = null;

	/**
	 * init individuals
	 */
	var $_individuals = null;

	/**
	 * init teams
	 */
	var $_teams = null;

	/**
	 * reference to database object
	 * @var object
	 */
	var $_db = null;

	/**
	 * Class constructor, overridden in descendant classes.
	 *
	 * @param   int  $projectid  project id
	 */
	public function __construct($projectid = null)
	{
		parent::__construct();

		$this->_db = JFactory::getDBO();

		if ($projectid)
		{
			$this->setProjectId($projectid);
		}
	}

	/**
	 * set project id
	 *
	 * @param   int  $projectid  project id
	 *
	 * @return bool
	 */
	public function setProjectId($projectid)
	{
		$this->_project_id = $projectid;
		$this->_project    = null;
		$this->_results    = null;

		return true;
	}

	/**
	 * return individual ranking for the project or only specified round
	 *
	 * @param   int  $individual_id    individual_id
	 * @param   int  $projectround_id  project round
	 *
	 * @return array
	 */
	public function getIndividualRanking($individual_id, $projectround_id = null)
	{
		$ranking = $this->getIndividualsRankings($projectround_id);

		return $ranking[$individual_id];
	}

	/**
	 * Gets the project individuals ranking of whole rounds or only specified one
	 *
	 * @param   int  $projectround_id  project round
	 *
	 * @return array of objects
	 */
	public function getIndividualsRankings($projectround_id = null)
	{
		$individuals = $this->_initIndividuals();

		if ($results = $this->_getResults())
		{
			foreach ($results as $r)
			{
				if (!isset($individuals[$r->id]))
				{
					continue;
				}

				if ($projectround_id && $projectround_id != $r->projectround_id)
				{
					continue;
				}

				// Always count the bonus points
				$points = $r->bonus_points;

				// Points for the round only if countpoints is set to true
				if ($r->count_points)
				{
					$points_attrib = explode(',', $r->points_attribution);

					if ($r->points_attribution && isset($points_attrib[$r->rank - 1]))
					{
						$points += (float) $points_attrib[$r->rank - 1];
					}

					// Add in Stats
					if ($r->rank > 0 && $r->enable_stats)
					{
						// -> rank = 0 means 'did not participate'
						if ($individuals[$r->id]->best_rank)
						{
							$individuals[$r->id]->best_rank = min($individuals[$r->id]->best_rank, $r->rank);
						}
						else
						{
							// Best_rank was 0, not a rank
							$individuals[$r->id]->best_rank = $r->rank;
						}

						if ($r->rank == 1)
						{
							$individuals[$r->id]->wins++;
						}

						$individuals[$r->id]->finishes[] = $r->rank;
					}
				}

				$individuals[$r->id]->points += $points;
			}

			uasort($individuals, array($this, "orderRankings"));

			$rank     = 1;
			$previous = null;

			foreach ($individuals as $k => $value)
			{
				if ($previous && self::orderRankingsNoAlpha($previous, $value) == 0)
				{
					$individuals[$k]->rank = $previous->rank;
				}
				else
				{
					$individuals[$k]->rank = $rank;
				}

				$previous = $individuals[$k];
				$rank++;
			}

			return $individuals;
		}

		return $individuals;
	}

	/**
	 * Init individuals
	 *
	 * @return array
	 */
	protected function _initIndividuals()
	{
		if (empty($this->_individuals))
		{
			$query = ' SELECT i.id, i.first_name, i.last_name, i.country_code, i.picture_small, '
				. ' pi.team_id, pi.number, '
				. ' t.name AS team_name, t.short_name AS team_short_name, t.acronym AS team_acronym, t.picture_small AS team_logo,'
				. ' CASE WHEN CHAR_LENGTH( i.alias ) THEN CONCAT_WS( \':\', i.id, i.alias ) ELSE i.id END AS slug, '
				. ' CASE WHEN CHAR_LENGTH( t.alias ) THEN CONCAT_WS( \':\', t.id, t.alias ) ELSE t.id END AS teamslug '
				. ' FROM #__tracks_participants AS pi '
				. ' INNER JOIN #__tracks_individuals AS i ON i.id = pi.individual_id '
				. ' LEFT JOIN #__tracks_teams AS t ON t.id = pi.team_id '
				. ' WHERE pi.project_id = ' . $this->_project_id
				. ' ORDER BY pi.number ASC, i.last_name ASC, i.first_name ASC ';

			$this->_db->setQuery($query);
			$this->_individuals = $this->_db->loadObjectList('id');
		}

		$results = array();

		if ($this->_individuals)
		{
			foreach ($this->_individuals as $i => $ind)
			{
				$result            = clone $ind;
				$result->points    = 0;
				$result->best_rank = 0;
				$result->wins      = 0;
				$result->rank      = null;
				$result->finishes  = array();
				$results[$i]       = $result;
			}
		}

		return $results;
	}

	/**
	 * Return the round results
	 *
	 * @return array of objects
	 */
	protected function _getResults()
	{
		if (empty($this->_results))
		{
			$query = ' SELECT rr.individual_id as id, rr.rank, rr.bonus_points, rr.team_id, rr.params, '
				. '   sr.projectround_id, srt.points_attribution, srt.count_points, srt.enable_stats '
				. ' FROM #__tracks_events_results AS rr '
				. ' INNER JOIN #__tracks_events AS sr ON sr.id = rr.event_id '
				. ' INNER JOIN #__tracks_eventtypes AS srt ON srt.id = sr.type '
				. ' INNER JOIN #__tracks_projects_rounds AS pr ON pr.id = sr.projectround_id '
				. ' WHERE pr.project_id = ' . $this->_project_id
				. '   AND pr.published = 1 '
				. '   AND sr.published = 1 ';

			$this->_db->setQuery($query);
			$this->_results = $this->_db->loadObjectList();
		}

		return $this->_results;
	}

	/**
	 * order rankings by points, wins, best_rank
	 *
	 * @param   object  $a  individual
	 * @param   object  $b  individual
	 *
	 * @return int
	 */
	protected function orderRankingsNoAlpha($a, $b)
	{
		if ($a->points != $b->points)
		{
			return (- ($a->points - $b->points) > 0) ? 1 : - 1;
		}
		elseif ($a->wins != $b->wins)
		{
			return -($a->wins - $b->wins);
		}
		elseif ($a->best_rank != $b->best_rank)
		{
			return ($a->best_rank - $b->best_rank);
		}

		return 0;
	}

	/**
	 * return team ranking for the project
	 *
	 * @param   int  $team_id  team_id
	 *
	 * @return object
	 */
	public function getTeamRanking($team_id)
	{
		$ranking = $this->getTeamsRankings();

		return $ranking[$team_id];
	}

	/**
	 * Gets the project teams ranking
	 *
	 * @return array
	 */
	public function getTeamsRankings()
	{
		$teams = $this->_initTeams();

		if ($results = $this->_getResults())
		{
			foreach ($results as $r)
			{
				if (!$r->team_id || !isset($teams[$r->team_id]))
				{
					continue;
				}

				// Points for the round
				$points = $r->bonus_points;

				if ($r->count_points)
				{
					$points_attrib = explode(',', $r->points_attribution);

					if ($r->points_attribution && isset($points_attrib[$r->rank - 1]))
					{
						$points += $points_attrib[$r->rank - 1];
					}

					// Add in Stats
					if ($r->rank > 0 && $r->enable_stats)
					{
						if ($teams[$r->team_id]->best_rank)
						{
							$teams[$r->team_id]->best_rank = min($teams[$r->team_id]->best_rank, $r->rank);
						}
						else
						{
							$teams[$r->team_id]->best_rank = $r->rank;
						}

						if ($r->rank == 1)
						{
							$teams[$r->team_id]->wins++;
						}
					}
				}

				$teams[$r->team_id]->points += $points;
			}

			uasort($teams, array($this, "orderTeamRankings"));

			$rank     = 1;
			$previous = null;

			foreach ($teams as $k => $value)
			{
				if ($previous && self::orderTeamRankingsNoAlpha($previous, $value) == 0)
				{
					$teams[$k]->rank = $previous->rank;
				}
				else
				{
					$teams[$k]->rank = $rank;
				}

				$previous = $teams[$k];
				$rank++;
			}

			return $teams;
		}

		return $teams;
	}

	/**
	 * Init teams data
	 *
	 * @return array
	 */
	protected function _initTeams()
	{
		if (empty($this->_teams))
		{
			$query = ' SELECT DISTINCT pi.team_id, '
				. ' t.name AS team_name, t.short_name AS team_short_name, t.acronym AS team_acronym, t.country_code, t.picture_small AS team_logo, '
				. ' CASE WHEN CHAR_LENGTH( t.alias ) THEN CONCAT_WS( \':\', t.id, t.alias ) ELSE t.id END AS slug '
				. ' FROM #__tracks_participants AS pi '
				. ' INNER JOIN #__tracks_teams AS t ON t.id = pi.team_id '
				. ' WHERE pi.project_id = ' . $this->_project_id
				. ' ORDER BY t.name ';

			$this->_db->setQuery($query);
			$this->_teams = $this->_db->loadObjectList('team_id');
		}

		$results = array();

		if ($this->_teams)
		{
			foreach ($this->_teams as $i => $ind)
			{
				$results[$i]            = clone $ind;
				$results[$i]->points    = 0;
				$results[$i]->best_rank = 0;
				$results[$i]->wins      = 0;
				$results[$i]->rank      = null;
			}
		}

		return $results;
	}

	/**
	 * order rankings by points, wins, best_rank
	 *
	 * @param   object  $a  team
	 * @param   object  $b  team
	 *
	 * @return int
	 */
	protected function orderTeamRankingsNoAlpha($a, $b)
	{
		if ($a->points != $b->points)
		{
			return (-($a->points - $b->points) > 0) ? 1 : - 1;
		}
		elseif ($a->wins != $b->wins)
		{
			return -($a->wins - $b->wins);
		}
		elseif ($a->best_rank != $b->best_rank)
		{
			return ($a->best_rank - $b->best_rank);
		}

		return 0;
	}

	/**
	 * order rankings by points, wins, best_rank
	 *
	 * @param   object  $a  object to order
	 * @param   object  $b  object to order
	 *
	 * @return int
	 */
	protected function orderRankings($a, $b)
	{
		$res = self::orderRankingsNoAlpha($a, $b);

		if ($res != 0)
		{
			return $res;
		}
		else
		{
			return strcasecmp($a->last_name, $b->last_name);
		}
	}

	/**
	 * order rankings by points, wins, best_rank
	 *
	 * @param   object  $a  object to order
	 * @param   object  $b  object to order
	 *
	 * @return int
	 */
	protected function orderTeamRankings($a, $b)
	{
		$res = self::orderTeamRankingsNoAlpha($a, $b);

		if ($res != 0)
		{
			return $res;
		}

		return strcasecmp($a->team_name, $b->team_name);
	}
}
