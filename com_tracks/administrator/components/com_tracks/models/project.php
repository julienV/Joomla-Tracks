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
	
	function &getData()
	{
		parent::getData();
		
		// Convert the params field to an array.
		$registry = new JRegistry;
		$registry->loadString($this->_data->params);
		$this->_data->params = $registry->toArray();
		return $this->_data;
	}
	
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
	
	/**
	* Returns a Table object, always creating it
	*
	* @param	type	The table type to instantiate
	* @param	string	A prefix for the table class name. Optional.
	* @param	array	Configuration array for model. Optional.
	* @return	JTable	A database object
	* @since	1.6
	*/
	public function getTable($type = 'project', $prefix = 'table', $config = array())
	{
		return JTable::getInstance($type, $prefix, $config);
	}
	
	/**
	 * copy sepcified projects
	 *  
	 * @param array $cid ids of project to copy
	 */
	public function copy($cid)
	{
		$count = 0;
		foreach ($cid as $projectid)
		{
			$project = &$this->getTable();
			if (!$project->load($projectid)) {
				$this->setError(JText::_('COM_TRACKS_UNKOWN_PROJECT').': '.$projectid);
				return false;
			}
			$project->id = null;
			$project->name = 'Copy of'.$project->name;
			$project->alias = null;
			$project->published = 0;
			$project->ordering = 0;
			
			if (!$project->check() || !$project->store()) {
				$this->setError($project->getError());
				return false;				
			}
			
			// now copy rounds
			$db = &JFactory::getDbo();
			$query = $db->getQuery(true);
			
			$query->select('sr.id as subround_id, sr.projectround_id');
			$query->from('#__tracks_projects_rounds AS pr');
			$query->innerjoin('#__tracks_projects_subrounds AS sr ON sr.projectround_id = pr.id ');
			$query->where('pr.project_id = '.$projectid);
			$db->setQuery($query);
			$subrounds = $db->loadObjectList();
			
			// sort by round
			$rounds = array();
			foreach ($subrounds as $sr)
			{
				$rounds[$sr->projectround_id][] = $sr;
			}
			
			// now do the copy
			foreach ($rounds as $rid => $sr)
			{
				$round = $this->getTable('projectround');
				if (!$round->load($rid)) {
					$this->setError(JText::_('COM_TRACKS_UNKOWN_PROJECTROUND').': '.$rid);
					return false;
				}
				$round->id = null;
				$round->project_id = $project->id;
				if (!$round->check() || !$round->store()) {
					$this->setError($round->getError());
					return false;				
				}

				foreach ($sr as $sub)
				{
					$subround = $this->getTable('subround');
					if (!$subround->load($sub->subround_id)) {
						$this->setError(JText::_('COM_TRACKS_UNKOWN_SUBROUND').': '.$rid);
						return false;
					}
					$subround->id = null;
					$subround->projectround_id = $round->id;
					if (!$subround->check() || !$subround->store()) {
						$this->setError($subround->getError());
						return false;				
					}					
				} 
			}
			
			// and last the participant
			$db = &JFactory::getDbo();
			$query = $db->getQuery(true);
			
			$query->select('pi.id');
			$query->from('#__tracks_projects_individuals AS pi');
			$query->where('pi.project_id = '.$projectid);
			$db->setQuery($query);
			$res = $db->loadResultArray();
			
			foreach ($res as $pi_id)
			{
				$participant = $this->getTable('projectindividual');
				if (!$participant->load($pi_id)) {
					$this->setError(JText::_('COM_TRACKS_UNKOWN_PARTICIPANT').': '.$pi_id);
					return false;
				}
				$participant->id = null;
				$participant->project_id = $project->id;
				if (!$participant->check() || !$participant->store()) {
					$this->setError($participant->getError());
					return false;				
				}					
			}
			
			$count++;
		}
		return $count;
	}
}
?>
