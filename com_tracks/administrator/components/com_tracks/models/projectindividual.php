<?php
/**
* @version    $Id: projectindividual.php 23 2008-02-15 08:36:46Z julienv $ 
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
 * Joomla Tracks Component Projectindividual Model
 *
 * @author Julien Vonthron <julien.vonthron@gmail.com>
 * @package   Tracks
 * @since 0.1
 */
class TracksModelProjectindividual extends TracksModelItem
{
	/**
	 * Method to remove a projectindividual
	 *
	 * @access	public
	 * @return	boolean|int number of deleted rows
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
			// delete results first
			$query = ' DELETE rr FROM #__tracks_rounds_results AS rr '
			  . ' INNER JOIN #__tracks_projects_subrounds AS psr ON psr.id = rr.subround_id '
			  . ' INNER JOIN #__tracks_projects_rounds AS pr ON pr.id = psr.projectround_id '
			  . ' INNER JOIN #__tracks_projects_individuals AS pi ON pi.individual_id = rr.individual_id AND pi.project_id = pr.project_id '
				. ' WHERE pi.id IN ( '.$cids.' ) ';
			
			$this->_db->setQuery( $query );
			if(!$this->_db->query()) 
      {
				$this->setError($this->_db->getErrorMsg());
				return false;
			}
			else 
			{
				// now delete the individuals from the project
	      $query = 'DELETE FROM #__tracks_projects_individuals '
	        . ' WHERE id IN ( '.$cids.' ) ';
	      
	      $this->_db->setQuery( $query );
	      if(!$this->_db->query()) 
	      {
	        $this->setError($this->_db->getErrorMsg());
	        return false;
	      }
				
			}
		}

		return $this->_db->getAffectedRows();
	}

	/**
	 * Method to (un)publish a projectindividual
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

			$query = 'UPDATE #__tracks_projects_individuals'
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
	 * Method to load content projectindividual data
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
			$query = ' SELECT obj.*, p.first_name, p.last_name, t.name as team_name '
        			. ' FROM #__tracks_projects_individuals AS obj '
        			. ' INNER JOIN #__tracks_individuals AS p ON (p.id = obj.individual_id) '
        			. ' LEFT JOIN #__tracks_teams AS t ON (t.id = obj.team_id) '
              . ' WHERE obj.id = '.(int) $this->_id;
			$this->_db->setQuery($query);
			$this->_data = $this->_db->loadObject();
			return (boolean) $this->_data;
		}
		return true;
	}
	
  /**
   * Method to return a individuals array (id, name)
   *
   * @access  public
   * @return  array seasons
   * @since 1.5
   */
	function getIndividuals() 
	{
		$query = 'SELECT id, CONCAT( last_name, ", ", first_name ) as name '
		      . ' FROM #__tracks_individuals '
		      . ' ORDER BY name ASC ';
	  $this->_db->setQuery( $query );
    if ( !$result = $this->_db->loadObjectList() ) {
      $this->setError($this->_db->getErrorMsg());
      return false;
    }
    else return $result;
	}
	
  /**
   * Method to return a teams array (id, name)
   *
   * @access  public
   * @return  array seasons
   * @since 1.5
   */
	function getTeams() 
	{
		$query = 'SELECT id, name'
		      . ' FROM #__tracks_teams '
		      . ' ORDER BY name ASC ';
	  $this->_db->setQuery( $query );
    if ( !$result = $this->_db->loadObjectList() ) {
      $this->setError($this->_db->getErrorMsg());
      return false;
    }
    else return $result;
	}
	
	/**
	 * Method to store the item
	 *
	 * @access	public
	 * @return	boolean	True on success
	 * @since	1.5
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
			$this->setError($this->_db->getErrorMsg());
			return false;
		}

		// Store the item to the database
		if (!$row->store()) {
			$this->setError($this->_db->getErrorMsg());
			return false;
		}

		return $row->id;
	}
	
 /**
   * Method to store mutliple rows
   *
   * @access  public
   * @param array rows to be inserted
   * @return  false|int count of inserted rows
   */
  function assign($rows)
  {
    $i = 0;
    // count inserted
    $rec = 0;
    for ($i=0, $n=count( $rows ); $i < $n; $i++)
    {
      $row =& $rows[$i];
      
      $query = 'SELECT id '
          . ' FROM #__tracks_projects_individuals '
          . ' WHERE individual_id = ' . intval($row->individual_id)
          . ' AND project_id = ' . intval($row->project_id);
	    $this->_db->setQuery( $query );
	    if ( count( $this->_db->loadObjectList()) ) {
        // already assigned
        continue;
	    }
      
      $new =& $this->getTable();
      $new->bind($row);
	    // Store the item to the database
	    if (!$new->store()) {
	      $this->setError($this->_db->getErrorMsg());
	      return false;
	    }
	    $rec++;
    }
    
    return $rec;
  }
  
	/**
	 * Method to initialise the projectindividual data
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
			$projectindividual = new stdClass();
			$projectindividual->id					      = 0;
      $projectindividual->individual_id			= 0;
      $projectindividual->project_id				= 0;
      $projectindividual->team_id           = 0;
      $projectindividual->number            = 0;
			$projectindividual->checked_out			  = 0;
			$projectindividual->checked_out_time	= 0;
			$this->_data = $projectindividual;
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
	public function getTable($type = 'projectindividual', $prefix = 'table', $config = array())
	{
		return JTable::getInstance($type, $prefix, $config);
	}
}
?>
