<<<<<<< HEAD
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
class TracksModelProject extends baseModel
{	
	var $_rounds = null;
	
	function __construct($projectid = null)
	{
		parent::__construct();
		
		$projectid = $projectid ? $projectid : JRequest::getInt('p');
		
		if ($projectid) {
			$this->setProjectId($projectid);
		}
	}

	function setProjectId($projectid)
	{
		if ($this->_project_id == $projectid) {
			return true;
		}
		$this->_project_id = intval($projectid);
		$this->_rankingtool = null;
		return true;
	}
	
	/**
	 * Gets winner of each round, indexed by projectround_id
	 *
	 * @param array ids of projectrounds
	 * @return the results to be displayed to the user
	 */
	function getWinners( $project_id )
	{
		$rounds = $this->getRounds();
		$projectround_ids = array();	
		foreach ($rounds as $r) {
			$projectround_ids[] = $r->projectround_id;
		}
		$winners = array();
		if ( count( $projectround_ids ) )
		{
			$rankingtool = $this->getRankingToolInstance();
			foreach ($projectround_ids as $pr)
			{
				$ranking = $rankingtool->getIndividualsRankings($pr);
				$first = reset($ranking);
				if ($first->best_rank) // was actually ranked (for rounds not finished, all rank can be 0)
				{ 
					$pwins = array();
					$mi = 0;
					foreach ($ranking as $r)
					{
						if ($mi++ > 3) {
							break;
						}
						if ($r->rank <= 3 && $r->best_rank <= 3 && $r->best_rank ) {
							$pwins[] = $r;
						}
					}
					$winners[$pr] = $pwins;
					//$winners[$pr] = array_slice($ranking, 0, 3);
				}
				else {
					$winners[$pr] = false;
				}
			}
			return $winners;
		}
		else return null;
	}

	function getRounds()
	{
		if (empty($this->_rounds))
		{
			$query =  ' SELECT pr.id AS projectround_id, '
			. ' pr.start_date, pr.end_date, '
			. ' r.name AS round_name, r.id as round_id, '
      . ' CASE WHEN CHAR_LENGTH( r.alias ) THEN CONCAT_WS( \':\', pr.id, r.alias ) ELSE pr.id END AS slug '
			. ' FROM #__tracks_projects_rounds AS pr '
			. ' INNER JOIN #__tracks_rounds AS r ON r.id = pr.round_id '
			. ' WHERE pr.published = 1 '
			. '   AND pr.project_id = ' . $this->_project_id
			. ' ORDER BY pr.ordering ';

			$this->_db->setQuery( $query );

			$this->_rounds = $this->_db->loadObjectList();
		}
		return $this->_rounds;
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
