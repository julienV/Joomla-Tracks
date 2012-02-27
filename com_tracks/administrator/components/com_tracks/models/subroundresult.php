<?php
/**
* @version    0.2 $Id$ 
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
require_once (JPATH_COMPONENT.DS.'models'.DS.'item.php');

/**
 * Joomla Tracks Component ProjectroundResult Model
 *
 * @author Julien Vonthron <julien.vonthron@gmail.com>
 * @package   Tracks
 * @since 0.1
 */
class TracksModelSubroundResult extends TracksModelItem
{
  var $subround_id = 0;
  
  var $project_id = 0;
  
  function __construct()
  {  	
    parent::__construct();
    $this->subround_id = JRequest::getVar('srid', 0);
  }
  
	/**
	 * Method to remove a projectroundResult
	 *
	 * @access	public
	 * @return	boolean	True on success
	 * @since	0.1
	 */
	function delete($cid = array())
	{		
		// TODO: delete the subobjects too ?
    $result = false;

		if (count( $cid ))
		{
			JArrayHelper::toInteger($cid);
			$cids = implode( ',', $cid );
			$query = 'DELETE FROM #__tracks_rounds_results'
				. ' WHERE id IN ( '.$cids.' )';
			
			$this->_db->setQuery( $query );
			if(!$this->_db->query()) 
      {
				$this->setError($this->_db->getErrorMsg());
				return false;
			}
		}

		return true;
	}

	/**
	 * Method to (un)publish a projectroundResult
	 *
	 * @access	public
	 * @return	boolean	True on success
	 * @since	0.1
	 */
	function publish($cid = array(), $publish = 1)
	{
		$user 	=& JFactory::getUser();

		if (count( $cid ))
		{
			JArrayHelper::toInteger($cid);
			$cids = implode( ',', $cid );

			$query = 'UPDATE #__tracks_rounds_results'
				. ' SET published = '.(int) $publish
				. ' WHERE id IN ( '.$cids.' )'
				. ' AND ( checked_out = 0 OR ( checked_out = '.(int) $user->get('id').' ) )'
			;
			$this->_db->setQuery( $query );
			if (!$this->_db->query()) {
				$this->setError($this->_db->getErrorMsg());
				return false;
			}
		}

		return true;
	}

	/**
	 * Method to save ranks
	 *
	 * @access	public
	 * @return	boolean	True on success
	 * @since	1.5
	 */
	function saveranks($cid = array(), $rank, $bonus_points, $performance, $individual, $team, $subround_id)
	{
		$row =& $this->getTable('projectroundresult');
		
		// update ordering values
		for( $i=0; $i < count($cid); $i++ )
		{
			if (!$row->load( (int) $cid[$i] ))
			{
			  // no entry yet for this player/round/project, create it
			  $row->reset();
			  $row->individual_id = $individual[$i];
			  $row->team_id       = $team[$i];
			  $row->subround_id    = $subround_id;
			  $row->rank          = $rank[$i];
        $row->bonus_points  = $bonus_points[$i];
			  $row->performance   = $performance[$i];
			  
			  if (!$row->store())
			  {
			    $this->setError($this->_db->getErrorMsg());
			    return false;
			  }
			}
			else 
			{
				if ( $row->rank != $rank[$i] 
				  || $row->bonus_points != $bonus_points[$i] 
				  || $row->performance != $performance[$i] )
				{
          $row->rank          = $rank[$i];
          $row->bonus_points  = $bonus_points[$i];
          $row->performance   = $performance[$i];
          $row->team_id       = $team[$i];
					
					if (!$row->store()) 
					{
						$this->setError($this->_db->getErrorMsg());
						return false;
					}
				}
			}
		}		
		return true;
	}
	
	/**
	 * Method to load content projectroundResult data
	 *
	 * @access	private
	 * @return	boolean	True on success
	 * @since	1.5
	 */
	function _loadData()
	{
		// Lets load the content if it doesn't already exist
		if (empty($this->_data))
		{
			$query = 'SELECT rr.*, r.name AS round_name, i.first_name, i.last_name, srt.name AS typename '.
					' FROM #__tracks_rounds_results AS rr ' .
          ' INNER JOIN #__tracks_projects_subrounds AS sr ON (sr.id=rr.subround_id)' .
          ' INNER JOIN #__tracks_subroundtypes AS srt ON (srt.id=sr.type)' .
			    ' INNER JOIN #__tracks_projects_rounds AS pr ON (pr.id=sr.projectround_id)' .
          ' INNER JOIN #__tracks_individuals AS i ON (i.id=rr.individual_id)' .
					' INNER JOIN #__tracks_rounds AS r ON (r.id=pr.round_id)' .
          ' WHERE rr.id = '.(int) $this->_id;
			$this->_db->setQuery($query);
			$this->_data = $this->_db->loadObject();
			return (boolean) $this->_data;
		}
		return true;
	}
	
	/**
	 * Method to initialise the projectroundResult data
	 *
	 * @access	private
	 * @return	boolean	True on success
	 * @since	1.5
	 */
	function _initData()
	{
		// Lets load the content if it doesn't already exist
		if (empty($this->_data))
		{
			$query = 'SELECT r.name AS round_name, srt.name AS typename, sr.id AS subround_id '.
          ' FROM #__tracks_projects_subrounds AS sr ' .
          ' INNER JOIN #__tracks_subroundtypes AS srt ON (srt.id=sr.type)' .
          ' INNER JOIN #__tracks_projects_rounds AS pr ON (pr.id=sr.projectround_id)' .
          ' INNER JOIN #__tracks_rounds AS r ON (r.id=pr.round_id)' .
          ' WHERE sr.id = '.(int) $this->subround_id;
      $this->_db->setQuery($query);
      $projectroundResult = $this->_db->loadObject();  
      
			$projectroundResult->id					      = 0;
			$projectroundResult->individual_id		= 0;
			$projectroundResult->team_id					= 0;
			$projectroundResult->rank     				= 0;
      $projectroundResult->performance      = '';
			$projectroundResult->bonus_points			= 0;
			$projectroundResult->comment					= null;
			$projectroundResult->checked_out			= 0;
			$projectroundResult->checked_out_time	= 0;
			$this->_data					= $projectroundResult;
			return (boolean) $this->_data;
		}
		return true;
	}

 /**
   * Method to get a table object, load it if necessary.
   *
   * @access  public
   * @param string The table name. Optional.
   * @param string The class prefix. Optional.
   * @param array Configuration array for model. Optional.
   * @return  object  The table
   * @since 1.5
   */
  function &getTable($name='', $prefix='Table', $options = array())
  {
    return parent::getTable('projectroundresult', $prefix, $options);
  }

  /**
   * Method to store the item
   *
   * @access  public
   * @return  boolean True on success
   * @since 1.5
   */
  function store($data)
  {
    $row =& $this->getTable();

    // Bind the form fields to the items table
    if (!$row->bind($data)) {
      $this->setError($this->_db->getErrorMsg());
      return false;
    }

    // Create the timestamp for the date
    $row->checked_out_time = gmdate('Y-m-d H:i:s');

    // Make sure the item is valid
    if (!$row->check()) {
      $this->setError($row->getError());
    echo 'check error !!';
      return false;
    }

    // Store the item to the database
    if (!$row->store()) {
      $this->setError($this->_db->getErrorMsg());
      echo 'bad !!';
      return false;
    }

    return true;
  }
  
  function getProjectId()
  {
    if (!$this->project_id) {
      // get project id 
      $this->_db->setQuery( 
            ' SELECT pr.project_id FROM #__tracks_projects_rounds AS pr '
          . ' INNER JOIN #__tracks_projects_subrounds AS sr ON sr.projectround_id = pr.id '
          . ' WHERE sr.id=' . $this->subround_id 
          );
      $this->project_id = $this->_db->loadResult();
    }
    return $this->project_id;  	
  }
  
  /**
   * Add individuals from project to sub round results.
   *
   */
  function addAll()
  {
    $project_id = $this->getProjectId();
    
    if ( $project_id )
    {
      $query = ' INSERT IGNORE INTO #__tracks_rounds_results (individual_id, team_id, subround_id) '
           . ' SELECT pi.individual_id, pi.team_id, ' . $this->subround_id 
           . ' FROM #__tracks_projects_individuals AS pi '
           . ' WHERE pi.project_id = ' .$project_id;
      $this->_db->setQuery($query);
      
      $this->_db->query();
      if ($this->_db->getErrorNum()) {
        echo $this->_db->getErrorMsg();
        $this->setError($this->_db->getErrorMsg());
        return false;
      }
    }
    return true;
  }
  
  /**
   * return project participants as value / text objects
   *
   * @return array
   */
  function getParticipantsOptions()
  {
    $project_id = $this->getProjectId();
  	$query = ' SELECT i.id AS value, CONCAT_WS(",", i.last_name, i.first_name) AS text'
  	       . ' FROM #__tracks_projects_individuals AS pi '
  	       . ' INNER JOIN #__tracks_individuals AS i on i.id = pi.individual_id '
           . ' WHERE pi.project_id = ' . $this->_db->Quote($project_id)
           . '   AND i.id NOT IN (SELECT individual_id FROM #__tracks_rounds_results WHERE subround_id = '.$this->_db->Quote($this->subround_id).')'
  	       ;
    $this->_db->setQuery($query);
    return $this->_db->loadObjectList();
  }
}
?>
