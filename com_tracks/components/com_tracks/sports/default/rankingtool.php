<?php
/**
* @version    $Id: controller.php 109 2008-05-24 11:05:07Z julienv $ 
* @package    JoomlaTracks
* @copyright	Copyright (C) 2008 Julien Vonthron. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* Joomla Tracks is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();

class TracksRankingTool extends JObject {
		
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
	
	function __construct($projectid = null)
	{
		parent::__construct();
		
		$this->_db = &JFactory::getDBO();
		
		if ($projectid) {
			$this->setProjectId($projectid);
		}
	}

	function setProjectId($projectid)
	{
		$this->_project_id = $projectid;
		$this->_project = null;
		$this->_results = null;
		return true;
	}
	
	/**
	 * Gets the project individuals ranking of whole rounds or only specified one
	 *
	 * @param int project round
	 * @return array of objects
	 */
	function getIndividualsRankings($projectround_id = null)
	{
		$individuals = $this->_initIndividuals();
		
		if ( $results = $this->_getResults() )
		{
			foreach ( $results as $r )
			{
				if (!isset($individuals[$r->id])) {
					continue;
				}
				if ($projectround_id && $projectround_id != $r->projectround_id) {
					continue;
				}
				// always count the bonus points
				$points = $r->bonus_points;
				// points for the round only if countpoints is set to true
				if (!empty($r->points_attribution))
				{
          $points_attrib = explode(',', $r->points_attribution);
					if ( isset( $points_attrib[$r->rank-1] ) ) {
						$points += $points_attrib[$r->rank-1];
					}
	
					if ( $r->rank > 0 ) // rank = 0 means 'did not participate'
					{
						if ( $individuals[$r->id]->best_rank ) {
							$individuals[$r->id]->best_rank = min( $individuals[$r->id]->best_rank, $r->rank );
						}
						else { // best_rank was 0, not a rank
							$individuals[$r->id]->best_rank = $r->rank;
						}
						if ( $r->rank == 1 ) {
							$individuals[$r->id]->wins++;
						}
					}
				}
				$individuals[$r->id]->points += $points;
			}
			uasort( $individuals, array( $this, "orderRankings" ) );
			$rank = 1;
			$previous = null;
			foreach ( $individuals as $k => $value )
			{
				if ($previous && self::orderRankingsNoAlpha($previous, $value) == 0) {
					$individuals[$k]->rank = $previous->rank;
				}
				else {
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
	 * Gets the project teams ranking
	 *
	 * @return string The projects to be displayed to the user
	 * @return array
	 */
	function getTeamsRankings()
	{
		$teams = $this->_initTeams();
		
		if ( $results = $this->_getResults() )
		{
			foreach ( $results as $r )
			{
				if (!$r->team_id || !isset($teams[$r->team_id])) {
					continue;
				}
				// points for the round
				$points = $r->bonus_points;
				if (!empty($r->points_attribution))
				{
					$points_attrib = explode(',', $r->points_attribution);
					if ( isset( $points_attrib[$r->rank-1] ) ) {
						$points += $points_attrib[$r->rank-1];
					}
					 
					if ( $r->rank > 0 )
					{
						if ($teams[$r->team_id]->best_rank ) {
							$teams[$r->team_id]->best_rank = min( $teams[$r->team_id]->best_rank, $r->rank );
						}
						else {
							$teams[$r->team_id]->best_rank = $r->rank;
						}

						if ( $r->rank == 1 ) {
							$teams[$r->team_id]->wins++;
						}
					}
				}
				$teams[$r->team_id]->points += $points;
			}
			uasort( $teams, array( $this, "orderTeamRankings" ) );
		}
		return $teams;
	}

	/**
	 * return individual ranking for the project or only specified round
	 * 
	 * @param int individual_id
	 * @param int project round
	 * @return array
	 */
	function getIndividualRanking($individual_id, $projectround_id = null)
	{
		$ranking = $this->getIndividualsRankings($projectround_id);
		return $ranking[$individual_id];
	}

	/**
	 * return team ranking for the project
	 * 
	 * @param int team_id
	 */
	function getTeamRanking($team_id)
	{
		$ranking = $this->getTeamsRankings();
		return $ranking[$team_id];
	}	
		
	/**
	 * Return the round results
	 *
	 * @param int $project_id
	 * @return array of objects
	 */
	function _getResults()
	{
		if (empty($this->_results))
		{
			$query =  ' SELECT rr.individual_id as id, rr.rank, rr.bonus_points, rr.team_id, '
			       . '   sr.projectround_id, srt.points_attribution '
			       . ' FROM #__tracks_rounds_results AS rr '
			       . ' INNER JOIN #__tracks_projects_subrounds AS sr ON sr.id = rr.subround_id '
			       . ' INNER JOIN #__tracks_subroundtypes AS srt ON srt.id = sr.type '
			       . ' INNER JOIN #__tracks_projects_rounds AS pr ON pr.id = sr.projectround_id '
			       . ' WHERE pr.project_id = ' . $this->_project_id
			       . '   AND pr.published = 1 '
			       . '   AND sr.published = 1 '
			       ;

			$this->_db->setQuery( $query );
			$this->_results = $this->_db->loadObjectList();
		}
		return $this->_results;
	}

	function _initIndividuals()
	{
		if (empty($this->_individuals))
		{
			$query =  ' SELECT i.id, i.first_name, i.last_name, i.country_code, i.picture_small, '
			. ' pi.team_id, pi.number, '
			. ' t.name AS team_name, t.short_name AS team_short_name, t.acronym AS team_acronym, t.picture_small AS team_logo,'
			. ' CASE WHEN CHAR_LENGTH( i.alias ) THEN CONCAT_WS( \':\', i.id, i.alias ) ELSE i.id END AS slug, '
			. ' CASE WHEN CHAR_LENGTH( t.alias ) THEN CONCAT_WS( \':\', t.id, t.alias ) ELSE t.id END AS teamslug '
			. ' FROM #__tracks_projects_individuals AS pi '
			. ' INNER JOIN #__tracks_individuals AS i ON i.id = pi.individual_id '
			. ' LEFT JOIN #__tracks_teams AS t ON t.id = pi.team_id '
			. ' WHERE pi.project_id = ' . $this->_project_id
			. ' ORDER BY pi.number ASC, i.last_name ASC, i.first_name ASC '
			;
	
			$this->_db->setQuery( $query );
			$this->_individuals = $this->_db->loadObjectList('id');
		}
		$results = array();
		if ($this->_individuals)
		{
			foreach ( $this->_individuals as $i => $ind )
			{
				$result = clone($ind);
				$result->points = 0;
				$result->best_rank = 0;
				$result->wins = 0;
				$result->rank = null;
				$results[$i] = $result;
			}
		}
		return $results;
	}

	function _initTeams()
	{
		if (empty($this->_teams))
		{
			$query =  ' SELECT DISTINCT pi.team_id, '
			. ' t.name AS team_name, t.short_name AS team_short_name, t.acronym AS team_acronym, t.country_code, t.picture_small AS team_logo, '
			. ' CASE WHEN CHAR_LENGTH( t.alias ) THEN CONCAT_WS( \':\', t.id, t.alias ) ELSE t.id END AS slug '
			. ' FROM #__tracks_projects_individuals AS pi '
			. ' INNER JOIN #__tracks_teams AS t ON t.id = pi.team_id '
			. ' WHERE pi.project_id = ' . $this->_project_id
			. ' ORDER BY t.name ';
		
			$this->_db->setQuery( $query );
			$this->_teams = $this->_db->loadObjectList('team_id');
		}
		$results = array();
		if ($this->_teams)
		{
			foreach ( $this->_teams as $i => $ind )
			{
				$results[$i] = clone($ind);
				$results[$i]->points = 0;
				$results[$i]->best_rank = 0;
				$results[$i]->wins = 0;
				$results[$i]->rank = null;
			}
		}
		return $results;
	}

	/**
	 * order rankings by points, wins, best_rank
	 *
	 */
	function orderRankings( $a, $b )
	{
		$res = self::orderRankingsNoAlpha($a, $b);
		if ( $res != 0 ) {
			return $res;
		}
		else {
			return strcasecmp($a->last_name, $b->last_name);
		}
	}

	/**
	 * order rankings by points, wins, best_rank
	 *
	 */
	function orderRankingsNoAlpha( $a, $b )
	{
		if ( $a->points != $b->points ) {
			return (-( $a->points - $b->points ) > 0) ? 1 : -1;
		}
		else if ( $a->wins != $b->wins ) {
			return -( $a->wins - $b->wins );
		}
		else if ( $a->best_rank != $b->best_rank ) {
			return ( $a->best_rank - $b->best_rank );
		}
		return 0;
	}
          
	/**
	 * order rankings by points, wins, best_rank
	 *
	 */
	function orderTeamRankings( $a, $b )
	{
		if ( $a->points != $b->points ) {
			return (-( $a->points - $b->points ) > 0) ? 1 : -1;
		}
		else if ( $a->wins != $b->wins ) {
			return -( $a->wins - $b->wins );
		}
		else if ( $a->best_rank != $b->best_rank ) {
			return ( $a->best_rank - $b->best_rank );
		}
		else {
			return strcasecmp($a->team_name, $b->team_name);
		}
	}
}