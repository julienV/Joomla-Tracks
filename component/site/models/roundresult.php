<?php
/**
* @version    $Id: roundresult.php 121 2008-05-30 08:33:23Z julienv $
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
class TracksModelRoundResult extends baseModel
{

	var $subrounds;

    /**
     * call back function to place the individual with
     * position 0 in the end
     *
     * @param mixed result
     * @param mixed result
     * @return bool
     */
    function reorder($a, $b)
    {
      if ( $a->rank == 0 && $b->rank == 0 )
      {
      	if ($a->bonus_points != $b->bonus_points) {
      		return $a->bonus_points < $b->bonus_points ? 1 : -1;
      	}
        return strcmp( $a->last_name.$a->first_name, $b->last_name.$b->first_name );
      }
      else if ( $a->rank == 0 ) {
        return true;
      }
      else if ( $b->rank == 0 ) {
        return false;
      }
      else {
      	if ($a->rank != $b->rank) {
        	return $a->rank < $b->rank ? -1 : 1;
      	}
      	if ($a->bonus_points != $b->bonus_points) {
      		return $a->bonus_points < $b->bonus_points ? 1 : -1;
      	}
        return strcmp( $a->last_name.$a->first_name, $b->last_name.$b->first_name );
      }
    }

    /**
    * Gets the sub-round results
    *
    * @param int projectround_id
    * @return string The projects to be displayed to the user
    */
    function _getSubroundResults( $event_id = 0 )
    {
        $query = 	' SELECT rr.*, pr.project_id AS project_id, srt.points_attribution, srt.count_points, '
                . ' i.first_name, i.last_name, i.country_code, i.country_code, pi.number, '
                . ' t.name AS team_name, t.short_name AS team_short_name, t.acronym AS team_acronym, t.picture_small AS team_logo, '
                . ' CASE WHEN CHAR_LENGTH( i.alias ) THEN CONCAT_WS( \':\', i.id, i.alias ) ELSE i.id END AS slug, '
                . ' CASE WHEN CHAR_LENGTH( t.alias ) THEN CONCAT_WS( \':\', t.id, t.alias ) ELSE t.id END AS teamslug '
                . ' FROM #__tracks_events_results AS rr '
                . ' INNER JOIN #__tracks_events AS sr ON sr.id = rr.event_id '
                . ' INNER JOIN #__tracks_eventtypes AS srt ON srt.id = sr.type '
                . ' INNER JOIN #__tracks_projects_rounds AS pr ON pr.id = sr.projectround_id '
                . ' INNER JOIN #__tracks_individuals AS i ON i.id = rr.individual_id '
                . ' INNER JOIN #__tracks_participants AS pi ON pi.individual_id = rr.individual_id AND pi.project_id = pr.project_id '
                . ' LEFT JOIN #__tracks_teams AS t ON t.id = rr.team_id '
                . ' WHERE rr.event_id = ' . $event_id
        ;

        $this->_db->setQuery( $query );

        $ranked = false;
        if ( $result = $this->_db->loadObjectList() )
        {
          // reorder by rank, with rank 0 in the end.
          uasort($result, array ($this, "reorder"));

          if ( count( $result ) )
          {
            foreach ( $result as $k => $r )
            {
            	if ($r->rank > 0) {
            		$ranked = true;
            	}
              $points_attrib = explode(',',  $r->points_attribution );
              if ( isset( $points_attrib[$r->rank - 1] ) ) {
                $result[$k]->points = $points_attrib[$r->rank - 1];
              }
              else {
                $result[$k]->points = 0;
              }
            }
          }
        }
        if ($ranked) {
	        return $result;
        }
        else { // no results yet !
					return false;
				}
    }

    function getRound( $projectround_id )
    {
    	if ( $projectround_id )
    	{
    		$query =  ' SELECT r.*, p.name AS project_name '
                . ' FROM #__tracks_projects_rounds AS pr '
                . ' INNER JOIN #__tracks_rounds AS r ON r.id = pr.round_id '
                . ' INNER JOIN #__tracks_projects AS p ON p.id = pr.project_id '
                . ' WHERE pr.id = ' . $projectround_id;

            $this->_db->setQuery( $query );

            if ( $result = $this->_db->loadObjectList() ) {
            	return $result[0];
            }
            else return $result;
    	}
    	else return null;
    }

    /**
     * return results for subrounds
     *
     * @param int $projectround_id
     * @param int $subroundtype_id, if 0 displays all type, otherwise only specific type
     * @param string $ordering, specify ordering of subrounds
     * @return array of subround results
     */
    function getSubrounds( $projectround_id, $subroundtype_id = 0, $ordering='ASC' )
    {
      if ( $projectround_id )
      {
        $query =  ' SELECT sr.*, srt.name AS typename, srt.points_attribution '
                . ' FROM #__tracks_events AS sr '
                . ' INNER JOIN #__tracks_eventtypes AS srt ON srt.id = sr.type '
                . ' WHERE sr.projectround_id = ' . $projectround_id
                . '   AND sr.published = 1 '
                ;
        if ($subroundtype_id) $query .= ' AND srt.id = ' . $subroundtype_id;
        $query .= ' ORDER BY sr.ordering ' . $ordering;

        $this->_db->setQuery( $query );

        $subrounds = $this->_db->loadObjectList();

        if ( $subrounds && count($subrounds) )
        {
        	for ($i = 0, $n = count($subrounds); $i < $n; $i++ ) {
        		$subrounds[$i]->results = $this->_getSubroundResults($subrounds[$i]->id);
        	}
        }
        return $subrounds;
      }
      else return null;
    }

    /**
     * get associated project
     *
     * @param unknown_type $projectround_id
     */
    function getRoundProject($projectround_id)
    {
    	if (empty($this->project))
    	{
	    	if ( $projectround_id )
	    	{
					$query = ' SELECT pr.project_id '
	               . ' FROM #__tracks_projects_rounds AS pr '
					       . ' WHERE pr.id = ' . $projectround_id;

					$this->_db->setQuery( $query );
					if ( $result = $this->_db->loadresult() ) {
						$this->setProjectId($result);
						return $this->getProject();
					}
					else return false;
	    	}
	    	else return null;
    	}
    	return $this->project;
    }
}
