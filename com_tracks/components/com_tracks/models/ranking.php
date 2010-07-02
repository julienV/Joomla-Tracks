<?php
/**
* @version    $Id: ranking.php 126 2008-06-05 21:17:18Z julienv $ 
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
class TracksFrontModelRanking extends baseModel
{
	/**
	 * associated project
	 */
	var $project = null;

	/**
	 * Gets the project individuals ranking
	 *
	 * @param int project_id
	 * @return array of objects
	 */
	function getRankings( $project_id = 0 )
	{
		$individuals = $this->_initIndividuals( $project_id );
		
		if ( $results = $this->getResults($project_id) )
		{
			foreach ( $results as $r )
			{
				if (!isset($individuals[$r->id])) {
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
			foreach ( $individuals as $k => $value )
			{
				$individuals[$k]->rank = $rank++;
			}			
			return $individuals;
		}
		return $individuals;
	}

    /**
     * Return the round results
     *
     * @param int $project_id
     * @return array of objects
     */
    function getResults( $project_id = 0 )
    {
        $query =  ' SELECT rr.individual_id as id, rr.rank, rr.bonus_points, srt.points_attribution '
                . ' FROM #__tracks_rounds_results AS rr '
                . ' INNER JOIN #__tracks_projects_subrounds AS sr ON sr.id = rr.subround_id '
                . ' INNER JOIN #__tracks_subroundtypes AS srt ON srt.id = sr.type '
                . ' INNER JOIN #__tracks_projects_rounds AS pr ON pr.id = sr.projectround_id '
                . ' WHERE pr.project_id = ' . $project_id
                . '   AND pr.published = 1 '
                . '   AND sr.published = 1 '
                ;
                
        $this->_db->setQuery( $query );
        return $this->_db->loadObjectList();
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
    
    function _initIndividuals( $project_id = 0 )
    {
    	$query =  ' SELECT i.id, i.first_name, i.last_name, i.country_code, i.picture_small, '
    	        . ' pi.team_id, pi.number, '
				    	. ' t.name AS team_name, t.short_name AS team_short_name, t.acronym AS team_acronym, t.picture_small AS team_logo,'
              . ' CASE WHEN CHAR_LENGTH( i.alias ) THEN CONCAT_WS( \':\', i.id, i.alias ) ELSE i.id END AS slug, '
              . ' CASE WHEN CHAR_LENGTH( t.alias ) THEN CONCAT_WS( \':\', t.id, t.alias ) ELSE t.id END AS teamslug '
				    	. ' FROM #__tracks_projects_individuals AS pi '
				    	. ' INNER JOIN #__tracks_individuals AS i ON i.id = pi.individual_id '
				    	. ' LEFT JOIN #__tracks_teams AS t ON t.id = pi.team_id '
				    	. ' WHERE pi.project_id = ' . $project_id
				    	. ' ORDER BY pi.number ASC, i.last_name ASC, i.first_name ASC '
				    	;

    	$this->_db->setQuery( $query );
    	if ($results = $this->_db->loadObjectList('id') )
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
    function orderRankings( $a, $b )
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
        return strcasecmp($a->last_name, $b->last_name);
      }
    }
    
    function getIndividualRanking($project_id, $individual_id)
    {
    	$ranking = self::getRankings($project_id);
    	return $ranking[$individual_id];
    }
}
