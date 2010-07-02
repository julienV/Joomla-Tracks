<?php
/**
* @version    $Id: teamranking.php 126 2008-06-05 21:17:18Z julienv $ 
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
class TracksFrontModelTeamRanking extends baseModel
{
    /**
     * associated project
     */         
    var $project = null;
         
    /**
    * Gets the project teams ranking
    * 
    * @param int project_id
    * @return string The projects to be displayed to the user
    */
    function getTeamRankings( $project_id = 0 )
    {
        $teams = $this->_initTeams( $project_id );
        
        $query =  ' SELECT rr.team_id, rr.rank, rr.bonus_points, srt.points_attribution '
                . ' FROM #__tracks_rounds_results AS rr '
                . ' INNER JOIN #__tracks_projects_subrounds AS sr ON sr.id = rr.subround_id '
                . ' INNER JOIN #__tracks_subroundtypes AS srt ON srt.id = sr.type '
                . ' INNER JOIN #__tracks_projects_rounds AS pr ON pr.id = sr.projectround_id '
                . ' WHERE pr.project_id = ' . $project_id
                . '   AND rr.team_id > 0'
                . '   AND pr.published = 1 '
                . '   AND sr.published = 1 '
                ;
        $this->_db->setQuery( $query );
        if ( $results = $this->_db->loadObjectList() )
        {
          foreach ( $results as $r )
          {
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
     * returns project object
     * 
     * @param int project_id          
     * @return object project
     */              
    function getProject( $project_id = 0 )
    {
    	if ( $this->project && ( $project_id == 0 || $project_id == $this->project->id ) ) {
        return $this->project;
      }
      
      $query =   ' SELECT * '
               . ' FROM #__tracks_projects AS p '
               . ' WHERE p.id = ' . $project_id;
                
      $this->_db->setQuery( $query );
        
      if ( $result = $this->_db->loadObjectList() ) {
        $this->project = $result[0];
      	return $this->project;
      }
      else {
        return $result;
      }
    }
        
    function _initTeams( $project_id = 0 )
    {
      $query =  ' SELECT DISTINCT pi.team_id, '
              . ' t.name AS team_name, t.short_name AS team_short_name, t.acronym AS team_acronym, t.country_code, t.picture_small AS team_logo'
              . ' FROM #__tracks_projects_individuals AS pi '
              . ' INNER JOIN #__tracks_teams AS t ON t.id = pi.team_id '
              . ' WHERE pi.project_id = ' . $project_id
              . ' ORDER BY t.name ';

      $this->_db->setQuery( $query );
      if ($results = $this->_db->loadObjectList('team_id') )
      {
        foreach ( $results as $i => $ind )
        {
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
