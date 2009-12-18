<?php
/**
* @version    $Id: project.php 15 2008-02-06 00:37:43Z julienv $ 
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
 * Joomla Tracks Component Project Model
 *
 * @author Julien Vonthron <julien.vonthron@gmail.com>
 * @package   Tracks
 * @since 0.1
 */
class TracksModelProject extends TracksModelItem
{
	/**
	 * Method to remove a project
	 *
	 * @access	public
	 * @return	boolean	True on success
	 * @since	1.5
	 */
	function delete($cid = array())
	{		
    $result = false;

    if (count( $cid ))
    {
      JArrayHelper::toInteger($cid);
      $cids = implode( ',', $cid );
      
      // delete rounds, subrounds and their results first
      $query = ' DELETE rr, sr, r, pi FROM #__tracks_projects AS p '
             . ' INNER JOIN #__tracks_projects_rounds AS r on p.id = r.project_id '
             . ' LEFT JOIN #__tracks_projects_individuals AS pi on p.id = pi.project_id '
             . ' LEFT JOIN #__tracks_projects_subrounds AS sr ON r.id = sr.projectround_id '
             . ' LEFT JOIN #__tracks_rounds_results AS rr ON  rr.subround_id = sr.id '
             . ' WHERE p.id IN ( '.$cids.' ) ';
      
      $this->_db->setQuery( $query );
      if(!$this->_db->query()) 
      {
        $this->setError($this->_db->getErrorMsg());
        return false;
      }
      else 
      {     
				$query = 'DELETE FROM #__tracks_projects'
					. ' WHERE id IN ( '.$cids.' )';
				
				$this->_db->setQuery( $query );
				if(!$this->_db->query()) {
					$this->setError($this->_db->getErrorMsg());
					return false;
				}
      }
		}

		return $this->_db->getAffectedRows();
	}
  
  /**
   * Method to return a season array (id, name)
   *
   * @access  public
   * @return  array seasons
   * @since 1.5
   */
	function getSeasons() 
	{
		$query = 'SELECT id, name'
		      . ' FROM #__tracks_seasons '
		      . ' ORDER BY name ASC';
	  $this->_db->setQuery( $query );
      if ( !$result = $this->_db->loadObjectList() ) {
        $this->setError($this->_db->getErrorMsg());
        return false;
      }  
    else return $result;  
	}
	
  /**
   * Method to return a competitions array (id, name)
   *
   * @access  public
   * @return  array
   * @since 1.5
   */
  function getCompetitions() 
  {
    $query = 'SELECT id, name'
          . ' FROM #__tracks_competitions '
          . ' ORDER BY name ASC';
    $this->_db->setQuery( $query );
      if ( !$result = $this->_db->loadObjectList() ) {
        $this->setError($this->_db->getErrorMsg());
        return false;
      }  
    else return $result;  
  }
  
/**
   * Method to return template independent projects (id, name)
   *
   * @access  public
   * @return  array
   * @since 1.5
   */
  function getMasters() 
  {
    $query = 'SELECT id, name'
          . ' FROM #__tracks '
          . ' WHERE master_template=0 '
          . ' ORDER BY name ASC';
    $this->_db->setQuery( $query );
      if ( !$result = $this->_db->loadObjectList() ) {
        $this->setError($this->_db->getErrorMsg());
        return false;
      }  
    else return $result;  
  }
	
	/**
	 * Method to (un)publish a project
	 *
	 * @access	public
	 * @return	boolean	True on success
	 * @since	1.5
	 */
	function publish($cid = array(), $publish = 1)
	{
		$user 	=& JFactory::getUser();
		if (count( $cid ))
		{
			JArrayHelper::toInteger($cid);
			$cids = implode( ',', $cid );

			$query = 'UPDATE #__tracks_projects'
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
	 * Method to load content project data
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
			$query = 'SELECT p.*'.
					' FROM #__tracks_projects AS p' .
					' WHERE p.id = '.(int) $this->_id;
			$this->_db->setQuery($query);
			$this->_data = $this->_db->loadObject();
			return (boolean) $this->_data;
		}
		return true;
	}

	/**
	 * Method to initialise the project data
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
			$project = new stdClass();
			$project->id					= 0;
			$project->competition_id				= 0;
			$project->season_id				= 0;
      $project->admin_id       = 0;
			$project->name				= null;
      $project->alias        = null;
			$project->published			= 0;
			$project->checked_out			= 0;
			$project->checked_out_time	= 0;
			$project->ordering			= 0;
			$project->params				= null;
			$project->comp_params				= null;
			$this->_data					= $project;
			return (boolean) $this->_data;
		}
		return true;
	}
}
?>
