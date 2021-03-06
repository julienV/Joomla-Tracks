<?php
/**
 * @package    Tracks.Site
 * @copyright  Tracks (C) 2008-2015 Julien Vonthron. All rights reserved.
 * @license    GNU General Public License version 2 or later
 */

// No direct access
use Joomla\Utilities\ArrayHelper;

defined('_JEXEC') or die('Restricted access');

/**
 * Joomla Tracks Component Front page Model
 *
 * @package  Tracks
 * @since    0.1
 */
class TracksModelProject extends TrackslibModelFrontbase
{
	var $_rounds = null;

	/**
	 * TracksModelProject constructor.
	 *
	 * @param   int  $projectid  project id
	 */
	public function __construct($projectid = null)
	{
		parent::__construct();

		$projectid = $projectid ? $projectid : JFactory::getApplication()->input->getInt('p', 0);

		if ($projectid)
		{
			$this->setProjectId($projectid);
		}
	}

	/**
	 * Set project id
	 *
	 * @param   int  $projectid  project id
	 *
	 * @return bool
	 */
	public function setProjectId($projectid)
	{
		if ($this->project_id == $projectid)
		{
			return true;
		}

		$this->project_id  = intval($projectid);
		$this->rankingtool = null;

		return true;
	}

	/**
	 * Get results
	 *
	 * @param   int  $projectId  project id
	 *
	 * @return mixed|null
	 */
	public function getResults($projectId = 0)
	{
		if ($projectId)
		{
			$rounds  = $this->getRounds($projectId);
			$winners = $this->getWinners();

			foreach ($rounds as $k => $r)
			{
				if (isset($winners[$r->projectround_id]))
				{
					$rounds[$k]->winner = $winners[$r->projectround_id];
				}
				else
				{
					$rounds[$k]->winner = array();
				}
			}

			return $rounds;
		}
	}

	/**
	 * Get rounds
	 *
	 * @return mixed|null
	 */
	public function getRounds()
	{
		if (empty($this->_rounds))
		{
			$query = ' SELECT pr.id AS projectround_id, '
				. ' pr.start_date, pr.end_date, '
				. ' r.name AS round_name, r.id as round_id, '
				. ' CASE WHEN CHAR_LENGTH( r.alias ) THEN CONCAT_WS( \':\', pr.id, r.alias ) ELSE pr.id END AS slug '
				. ' FROM #__tracks_projects_rounds AS pr '
				. ' INNER JOIN #__tracks_rounds AS r ON r.id = pr.round_id '
				. ' WHERE pr.published = 1 '
				. '   AND pr.project_id = ' . $this->project_id
				. ' ORDER BY pr.ordering ';

			$this->_db->setQuery($query);

			$this->_rounds = $this->_db->loadObjectList();
		}

		return $this->_rounds;
	}

	/**
	 * Gets winner of each round, indexed by projectround_id
	 *
	 * @return the results to be displayed to the user
	 */
	public function getWinners()
	{
		$rounds           = $this->getRounds();
		$projectroundIds = ArrayHelper::getColumn($rounds, 'projectround_id');

		$winners = array();

		if (count($projectroundIds))
		{
			$rankingtool = $this->_getRankingTool();

			foreach ($projectroundIds as $pr)
			{
				$ranking = $rankingtool->getIndividualsRankings($pr);
				$first   = reset($ranking);

				if ($first->best_rank)
				{
					// Was actually ranked (for rounds not finished, all rank can be 0)
					$winners[$pr] = array();

					foreach ($ranking as $r)
					{
						if ($r->rank == $first->rank)
						{
							$winners[$pr][] = $r;
						}
						else
						{
							break;
						}
					}
				}
				else
				{
					$winners[$pr] = array();
				}
			}

			return $winners;
		}
		else
		{
			return null;
		}
	}
}
