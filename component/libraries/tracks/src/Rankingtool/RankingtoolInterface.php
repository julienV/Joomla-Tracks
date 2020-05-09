<?php
/**
 * @package     Tracks
 * @subpackage  Library
 * @copyright   Tracks (C) 2008-2015 Julien Vonthron. All rights reserved.
 * @license     GNU General Public License version 2 or later
 */

namespace Tracks\Rankingtool;

defined('_JEXEC') or die;

/**
 * Ranking Tool interface
 *
 * @package     Tracks
 * @subpackage  Library
 * @since       1.0
 */
interface RankingtoolInterface
{
	/**
	 * Class constructor, overridden in descendant classes.
	 *
	 * @param   int  $projectid  project id
	 */
	public function __construct($projectid = null);

	/**
	 * set project id
	 *
	 * @param   int  $projectid  project id
	 *
	 * @return bool
	 */
	public function setProjectId($projectid);

	/**
	 * return individual ranking for the project or only specified round
	 *
	 * @param   int  $individual_id    individual_id
	 * @param   int  $projectround_id  project round
	 *
	 * @return array
	 */
	public function getIndividualRanking($individual_id, $projectround_id = null);

	/**
	 * Gets the project individuals ranking of whole rounds or only specified one
	 *
	 * @param   int  $projectround_id  project round
	 *
	 * @return array of objects
	 */
	public function getIndividualsRankings($projectround_id = null);

	/**
	 * return team ranking for the project
	 *
	 * @param   int  $team_id  team_id
	 *
	 * @return object
	 */
	public function getTeamRanking($team_id);

	/**
	 * Gets the project teams ranking
	 *
	 * @return array
	 */
	public function getTeamsRankings();
}
