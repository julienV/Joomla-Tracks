<?php
/**
* @version    $Id: projects.php 109 2008-05-24 11:05:07Z julienv $ 
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
class TracksFrontModelSeasonwinners extends baseModel
{
	protected $_season_id = 0;
	
	/**
	 * set season id
	 * 
	 * @param int $s
	 */
	public function setSeasonId($s)
	{
		$this->_season_id = intval($s);
	}
	
	/**
	 * return info about season
	 * 
	 * @return object
	 */
	public function getSeason()
	{
		$query = ' SELECT s.*, ' 
		       . ' CASE WHEN CHAR_LENGTH( s.alias ) THEN CONCAT_WS( \':\', s.id, s.alias ) ELSE s.id END AS slug '
		       . ' FROM #__tracks_seasons AS s ' 
		       . ' WHERE s.id = ' . $this->_db->Quote($this->_season_id);
		$this->_db->setQuery($query);
		$res = $this->_db->loadObject();
		
		return $res;
	}
	
	/**
	 * get competitions winners for the season
	 * @return array
	 */
	public function getWinners()
	{
		require_once (JPATH_SITE.DS.'components'.DS.'com_tracks'.DS.'sports'.DS.'default'.DS.'rankingtool.php');
		
		// get projects
		$query = ' SELECT p.name, p.id, p.competition_id, p.season_id '
		       . '   , c.name as competition_name ' 
		       . '   , CASE WHEN CHAR_LENGTH( p.alias ) THEN CONCAT_WS( \':\', p.id, p.alias ) ELSE p.id END AS slug '
		       . ' FROM #__tracks_projects AS p ' 
		       . ' INNER JOIN #__tracks_competitions AS c ON c.id = p.competition_id ' 
		       . ' WHERE p.season_id = ' . $this->_db->Quote($this->_season_id)
		       . ' ORDER BY c.name ASC '
		;
		$this->_db->setQuery($query);
		$projects = $this->_db->loadObjectList();
				
		if (!count($projects)) {
			return false;
		}
		
		$res = array();
		foreach ($projects as $project)
		{
			$rankingtool = new TracksRankingTool($project->id);
			$rankings = $rankingtool->getIndividualsRankings();
			// there can be tied winners
			$winners = array();
			foreach ($rankings as $r) 
			{
				if ($r->best_rank && $r->rank == 1) {
					$winners[] = $r;
				}
				else {
					break;
				}
			}
			$project->winners = $winners;
			if ($project->winners) {
				$res[$project->id] = $project;
			}
		}
		
		// take into account event season
		$season = $this->getSeason();
		$query = ' SELECT p.name, p.id, p.competition_id, p.season_id, pr.id as prid '
				       . '   , c.name as competition_name ' 
				       . '   , CASE WHEN CHAR_LENGTH( p.alias ) THEN CONCAT_WS( \':\', p.id, p.alias ) ELSE p.id END AS slug '
				       . ' FROM #__tracks_projects AS p ' 
				       . ' INNER JOIN #__tracks_competitions AS c ON c.id = p.competition_id ' 
				       . ' INNER JOIN #__tracks_projects_rounds AS pr ON pr.project_id = p.id '
				       . ' INNER JOIN #__tracks_rounds AS r ON r.id = pr.round_id '
				       . ' WHERE p.season_id = 23 '
				       . '   AND r.name = '.$this->_db->Quote($season->name)
		;
		$this->_db->setQuery($query);
		$eventrounds = $this->_db->loadObjectList();
		foreach ((array) $eventrounds as $event)
		{
			$rankingtool = new TracksRankingTool($event->id);
			$rankings = $rankingtool->getIndividualsRankings($event->prid);
			// there can be tied winners
			$winners = array();
			foreach ($rankings as $r) 
			{
				if ($r->best_rank && $r->rank == 1) {
					$winners[] = $r;
				}
				else {
					break;
				}
			}
			$event->winners = $winners;
			if ($event->winners) {
				$res[$event->id] = $event;
			}
		}
		
		return $res;
	}
}
