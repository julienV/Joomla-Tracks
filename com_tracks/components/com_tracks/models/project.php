<?php
/**
* @version    $Id: roundresult.php 43 2008-02-24 23:47:38Z julienv $ 
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

jimport('joomla.application.component.model');
require_once( 'base.php' );

/**
 * Joomla Tracks Component Front page Model
 *
 * @package		Tracks
 * @since 0.1
 */
class TracksFrontModelProject extends baseModel
{
	/**
	 * Gets winner of each round, indexed by projectround_id
	 *
	 * @param array ids of projectrounds
	 * @return the results to be displayed to the user
	 */
	function getWinners( $projectround_ids )
	{
		if ( count( $projectround_ids ) )
		{
			$query = 	' SELECT rr.*, sr.projectround_id,'
			. ' i.first_name, i.last_name, '
			. ' t.name AS team_name '
			. ' FROM #__tracks_rounds_results AS rr '
      . ' INNER JOIN #__tracks_projects_subrounds AS sr ON rr.subround_id = sr.id '
      . ' INNER JOIN #__tracks_subroundtypes AS srt ON srt.id = sr.type '
			. ' INNER JOIN #__tracks_projects_rounds AS pr ON pr.id = sr.projectround_id '
			. ' INNER JOIN #__tracks_individuals AS i ON i.id = rr.individual_id '
			. ' LEFT JOIN #__tracks_teams AS t ON t.id = rr.team_id '
			. ' WHERE pr.published = 1 '
			. '   AND rr.rank = 1 '
      . '   AND CHAR_LENGTH(srt.points_attribution) > 0 '
			. '   AND pr.id IN (' . implode( ", ", $projectround_ids ) . ') ';

			$this->_db->setQuery( $query );

			$result = $this->_db->loadObjectList( 'projectround_id' );
			return $result;
		}
		else return null;
	}

	function getRounds( $project_id = 0 )
	{
		if ( $project_id )
		{
			$query =  ' SELECT pr.id AS projectround_id, '
			. ' pr.start_date, pr.end_date, '
			. ' r.name AS round_name, r.id as round_id, '
      . ' CASE WHEN CHAR_LENGTH( r.alias ) THEN CONCAT_WS( \':\', pr.id, r.alias ) ELSE pr.id END AS slug '
			. ' FROM #__tracks_projects_rounds AS pr '
			. ' INNER JOIN #__tracks_rounds AS r ON r.id = pr.round_id '
			. ' WHERE pr.published = 1 '
			. '   AND pr.project_id = ' . $project_id
			. ' ORDER BY pr.ordering ';

			$this->_db->setQuery( $query );

			return $this->_db->loadObjectList();
		}
		else return null;
	}

	function getResults( $project_id = 0 )
	{
	  if ( $project_id )
		{
			$results = $this->getRounds( $project_id );
			$projectround_ids = array();
			foreach ( $results as $r )
			{
				$projectround_ids[] = $r->projectround_id;
			}
			$winners = $this->getWinners( $projectround_ids );
			 
			foreach ( $results as $k => $r )
			{
				if ( isset( $winners[$r->projectround_id] ) ) {
					$results[$k]->winner = $winners[$r->projectround_id];
				}
			}
			return $results;
		}
	}
}
