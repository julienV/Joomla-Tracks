<?php
/**
* @version    $Id: roundresult.php 61 2008-04-24 15:20:36Z julienv $ 
* @package    JoomlaTracks
* @copyright    Copyright (C) 2008 Julien Vonthron. All rights reserved.
* @license      GNU/GPL, see LICENSE.php
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
 * @package     Tracks
 * @since 0.1
 */
class TracksModelTeam extends baseModel
{   		
	var $_individuals = null;
	
	function __construct($config = array())
	{
		parent::__construct($config);
		
		$id = JRequest::getInt('t');
		$project = JRequest::getInt('p');
		
		$this->setId($id);
		$this->setProjectId($project);
	}
		

	function setId($id)
	{
		$this->_id = (int) $id;
		$this->_data        = null;
		$this->_individuals = null;
	}
	
	function getData()
	{		 
		if ( empty($this->_data) )
		{
			$query =  ' SELECT t.* '
			. ' FROM #__tracks_teams as t '
			. ' WHERE t.id = ' . $this->_id;

			$this->_db->setQuery( $query );

			if ( $result = $this->_db->loadObject() ) 
			{
				$this->_data = $result;
				$attribs['class']="pic";
			  
				if ($this->_data->picture != '') {
					$this->_data->picture = JHTML::image(JURI::root().'media/com_tracks/images/teams/'.$this->_data->picture, $this->_data->name, $attribs);
				} else {
					//$this->_data->picture = JHTML::image(JURI::base().'media/com_tracks/images/misc/tnnophoto.jpg', $this->_data->name, $attribs);
				}
			}
			else return $result;
		}
		else {
			return null;
		}
		return $this->_data;
	}
    
	function getIndividuals()
	{
		if (empty($this->_individuals))
		{
			$query = ' SELECT p.name as project_name, i.*, pi.number, pi.project_id, s.name, ' 
			       . ' CASE WHEN CHAR_LENGTH( i.alias ) THEN CONCAT_WS( \':\', i.id, i.alias ) ELSE i.id END AS slug, '
			       . ' CASE WHEN CHAR_LENGTH( p.alias ) THEN CONCAT_WS( \':\', p.id, p.alias ) ELSE p.id END AS projectslug '
			       . ' FROM #__tracks_individuals AS i '
			       . ' INNER JOIN #__tracks_projects_individuals AS pi ON pi.individual_id = i.id '
			       . ' INNER JOIN #__tracks_projects AS p ON pi.project_id = p.id '
			       . ' INNER JOIN #__tracks_seasons AS s ON s.id = p.season_id '
			       . ' WHERE pi.team_id = ' . $this->_db->Quote($this->_id)
			       . ($this->_project_id ? ' AND p.id = '.$this->_db->Quote($this->_project_id) : '')
			       . ' ORDER BY p.ordering, i.last_name ASC '
			       ;
			$this->_db->setQuery($query);
			$res = $this->_db->loadObjectList();
			
			// sort by projects
			$proj = array();
			foreach ((array) $res as $i)
			{
				if (!isset($proj[$i->project_id])) {
					$proj[$i->project_id] = array();
				}
				$proj[$i->project_id][] = $i;
			}
			
			$this->_individuals = $proj;
		}
		return $this->_individuals;
	}
}
