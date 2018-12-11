<?php
/**
 * @package    Tracks.Site
 * @copyright  Tracks (C) 2008-2015 Julien Vonthron. All rights reserved.
 * @license    GNU General Public License version 2 or later
 */

// No direct access
defined('_JEXEC') or die('Restricted access');

/**
 * Joomla Tracks Component Front page Model
 *
 * @package  Tracks
 * @since    0.1
 */
class TracksModelRoundResult extends TrackslibModelFrontbase
{
	var $subrounds;

	/**
	 * call back function to place the individual with
	 * position 0 in the end
	 *
	 * @param   mixed  $a  result
	 * @param   mixed  $b  result
	 *
	 * @return int
	 */
	protected function reorder($a, $b)
	{
		if ($a->rank == 0 && $b->rank == 0)
		{
			if ($a->bonus_points != $b->bonus_points)
			{
				return ($a->bonus_points < $b->bonus_points ? 1 : - 1);
			}

			return strcmp($a->last_name . $a->first_name, $b->last_name . $b->first_name);
		}
		elseif ($a->rank == 0)
		{
			return 1;
		}
		elseif ($b->rank == 0)
		{
			return -1;
		}
		else
		{
			if ($a->rank != $b->rank)
			{
				return $a->rank < $b->rank ? - 1 : 1;
			}

			if ($a->bonus_points != $b->bonus_points)
			{
				return $a->bonus_points < $b->bonus_points ? 1 : - 1;
			}

			return strcmp($a->last_name . $a->first_name, $b->last_name . $b->first_name);
		}
	}

	/**
	 * Get round
	 *
	 * @param   int  $projectround_id  project round id
	 *
	 * @return mixed|null
	 */
	public function getRound($projectround_id)
	{
		if ($projectround_id)
		{
			$query = ' SELECT r.*, p.name AS project_name '
				. ' FROM #__tracks_projects_rounds AS pr '
				. ' INNER JOIN #__tracks_rounds AS r ON r.id = pr.round_id '
				. ' INNER JOIN #__tracks_projects AS p ON p.id = pr.project_id '
				. ' WHERE pr.id = ' . $projectround_id;

			$this->_db->setQuery($query);

			if ($result = $this->_db->loadObjectList())
			{
				return $result[0];
			}
			else
			{
				return $result;
			}
		}
		else
		{
			return null;
		}
	}

	/**
	 * return results for subrounds
	 *
	 * @param   int     $projectround_id  project round id
	 * @param   int     $subroundtype_id  if 0 displays all type, otherwise only specific type
	 * @param   string  $ordering         specify ordering of subrounds
	 *
	 * @return array of subround results
	 */
	public function getSubrounds($projectround_id, $subroundtype_id = 0, $ordering = 'ASC')
	{
		if ($projectround_id)
		{
			$query = ' SELECT sr.*, srt.name AS typename, srt.points_attribution, srt.count_points, srt.enable_stats '
				. ' FROM #__tracks_events AS sr '
				. ' INNER JOIN #__tracks_eventtypes AS srt ON srt.id = sr.type '
				. ' WHERE sr.projectround_id = ' . $projectround_id
				. '   AND sr.published = 1 ';

			if ($subroundtype_id)
			{
				$query .= ' AND srt.id = ' . $subroundtype_id;
			}

			$query .= ' ORDER BY sr.ordering ' . $ordering;

			$this->_db->setQuery($query);

			$subrounds = $this->_db->loadObjectList();

			if ($subrounds && count($subrounds))
			{
				for ($i = 0, $n = count($subrounds); $i < $n; $i++)
				{
					$subrounds[$i]->results = $this->_getSubroundResults($subrounds[$i]->id);
				}
			}

			return $subrounds;
		}
		else
		{
			return null;
		}
	}

	/**
	 * Gets the sub-round results
	 *
	 * @param   int  $event_id  event id
	 *
	 * @return string The projects to be displayed to the user
	 */
	public function _getSubroundResults($event_id = 0)
	{
		$db = JFactory::getDbo();
		$query = $db->getQuery(true)
			->select('rr.*')
			->select('pr.project_id AS project_id')
			->select('srt.points_attribution, srt.count_points')
			->select('sr.rank_offset')
			->select('i.first_name, i.last_name, i.country_code, i.country_code')
			->select('CASE WHEN rr.number THEN rr.number ELSE pi.number END AS number')
			->select('t.name AS team_name, t.short_name AS team_short_name, t.acronym AS team_acronym, t.picture_small AS team_logo')
			->select('CASE WHEN CHAR_LENGTH( i.alias ) THEN CONCAT_WS( \':\', i.id, i.alias ) ELSE i.id END AS slug')
			->select('CASE WHEN CHAR_LENGTH( t.alias ) THEN CONCAT_WS( \':\', t.id, t.alias ) ELSE t.id END AS teamslug')
			->from('#__tracks_events_results AS rr')
			->innerJoin('#__tracks_events AS sr ON sr.id = rr.event_id')
			->innerJoin('#__tracks_eventtypes AS srt ON srt.id = sr.type')
			->innerJoin('#__tracks_projects_rounds AS pr ON pr.id = sr.projectround_id')
			->innerJoin('#__tracks_individuals AS i ON i.id = rr.individual_id')
			->innerJoin('#__tracks_participants AS pi ON pi.individual_id = rr.individual_id AND pi.project_id = pr.project_id')
			->leftJoin('#__tracks_teams AS t ON t.id = rr.team_id')
			->where('rr.event_id = ' . $event_id);

		$db->setQuery($query);

		$ranked = false;

		if ($result = $db->loadObjectList())
		{
			// Reorder by rank, with rank 0 in the end.
			uasort($result, array($this, "reorder"));

			if (count($result))
			{
				foreach ($result as $k => $r)
				{
					$result[$k]->points = 0;

					if (!empty($r->rank) || !empty($r->bonus_points) || !empty($r->performance))
					{
						$ranked = true;
					}

					if ($r->rank)
					{
						$rank = $r->rank + $r->rank_offset;
						$points_attrib = explode(',', $r->points_attribution);
						$points_attrib = array_map('floatval', $points_attrib);

						if (isset($points_attrib[$rank - 1]))
						{
							$result[$k]->points += $points_attrib[$rank - 1];
						}
					}
				}
			}
		}

		if ($ranked)
		{
			return $result;
		}
		else
		{
			// No results yet !
			return false;
		}
	}

	/**
	 * get associated project
	 *
	 * @param   int  $projectround_id  project round id
	 *
	 * @return object
	 */
	public function getRoundProject($projectround_id)
	{
		if (empty($this->project))
		{
			if ($projectround_id)
			{
				$query = ' SELECT pr.project_id '
					. ' FROM #__tracks_projects_rounds AS pr '
					. ' WHERE pr.id = ' . $projectround_id;

				$this->_db->setQuery($query);

				if ($result = $this->_db->loadresult())
				{
					$this->setProjectId($result);

					return $this->getProject();
				}
				else
				{
					return false;
				}
			}
			else
			{
				return null;
			}
		}

		return $this->project;
	}
}
