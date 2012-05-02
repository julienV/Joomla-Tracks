<?php
/**
* @version    $Id$ 
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
 * Joomla Tracks Component Projectround Model
 *
 * @author Julien Vonthron <julien.vonthron@gmail.com>
 * @package   Tracks
 * @since 0.1
 */
class TracksModelProjectround extends TracksModelItem
{
	/**
	 * Method to remove a projectround
	 *
	 * @access	public
	 * @return	boolean	True on success
	 * @since	0.1
	 */
	function delete($cid = array())
	{		
    $result = false;

		if (count( $cid ))
		{
			JArrayHelper::toInteger($cid);
			$cids = implode( ',', $cid );
			
			// delete subrounds and their results first
			$query = ' DELETE rr, sr FROM #__tracks_projects_rounds AS r '
			       . ' INNER JOIN #__tracks_projects_subrounds AS sr ON r.id = sr.projectround_id '
			       . ' LEFT JOIN #__tracks_rounds_results AS rr ON  rr.subround_id = sr.id'
             . ' WHERE r.id IN ( '.$cids.' ) ';
      
      $this->_db->setQuery( $query );
      if(!$this->_db->query()) 
      {
        $this->setError($this->_db->getErrorMsg());
        return false;
      }
      else 
      {			
				$query = 'DELETE FROM #__tracks_projects_rounds'
					. ' WHERE id IN ( '.$cids.' )';
				
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
	 * Method to (un)publish a projectround
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

			$query = 'UPDATE #__tracks_projects_rounds'
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
	 * Method to load content projectround data
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
			$query = 'SELECT pr.*, r.name '.
					' FROM #__tracks_projects_rounds AS pr ' .
					' INNER JOIN #__tracks_rounds AS r ON (r.id=pr.round_id)' .
          ' WHERE pr.id = '.(int) $this->_id;
			$this->_db->setQuery($query);
			$this->_data = $this->_db->loadObject();
			return (boolean) $this->_data;
		}
		return true;
	}
	
  /**
   * Method to return a rounds array (id, name)
   *
   * @access  public
   * @return  array seasons
   * @since 1.5
   */
	function getRounds() 
	{
		$query = 'SELECT id, name'
		      . ' FROM #__tracks_rounds '
		      . ' ORDER BY name ASC';
	  $this->_db->setQuery( $query );
    if ( !$result = $this->_db->loadObjectList() ) {
      $this->setError($this->_db->getErrorMsg());
      return false;
    }
    else return $result;
	}


 /**
   * Method to store item(s)
   *
   * @access  public
   * @param array the project round ids to copy
   * @param int the destination project id
   * @return  boolean True on success
   * @since 1.5
   */
  function assign($cids, $project_id)
  {
    $row =& $this->getTable();
    
    $i = 0;
    for ($i=0, $n=count( $cids ); $i < $n; $i++)
    {
      $cid =& $cids[$i];
      
      $query = 'SELECT *'
          . ' FROM #__tracks_projects_rounds '
          . ' WHERE id = ' . intval($cid);
      $this->_db->setQuery( $query );
      $round = $this->_db->loadObject();
      if (!$round) {
      	JError::raise(500, 'Round not found. '.$this->_db->getErrorMsg());
      }
      if ( !$round ) {
        // not found...
        break;
      }
      
      $row->reset();
      $row->bind($round);
      $row->id = null;
      $row->project_id = $project_id;
      $row->checked_out = 0;
      $row->checked_out_time = null;      
      
      // Store the item to the database
      if (!$row->store()) {
        $this->setError($this->_db->getErrorMsg());
        JError::raise(500, 'Failed to copy round. '.$this->_db->getErrorMsg());
      }
      
      // now copy subrounds
      $query = ' SELECT * '
             . ' FROM #__tracks_projects_subrounds'
             . ' WHERE projectround_id = ' . $cid;
      $this->_db->setQuery( $query );
      $subrounds = $this->_db->loadObjectList();
      
      if (is_array($subrounds))
      {
        $subround =& $this->getTable('Subround');
        
      	foreach ($subrounds AS $s)
      	{
      		$subround->reset();
      		$subround->bind($s);
          $subround->id = null;
          $subround->projectround_id = $row->id;
          $subround->checked_out = 0;
          $subround->checked_out_time = null;
      		dump($subround);
          
		      if (!$subround->store()) {
		        $this->setError($this->_db->getErrorMsg());
		        JError::raise(500, 'Failed to copy subround. '.$this->_db->getErrorMsg());
		      }
      	}
      }
    }
    
    return true;
  }
  
	/**
	 * Method to initialise the projectround data
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
			$projectround = new stdClass();
			$projectround->id					      = 0;
      $projectround->round_id					= 0;
      $projectround->project_id				= 0;
      $projectround->start_date				= 0;
      $projectround->end_date					= 0;
      $projectround->description			= null;
      $projectround->comment					= null;
			$projectround->checked_out			= 0;
			$projectround->checked_out_time	= 0;
			$projectround->ordering			    = 0;
      $projectround->published        = 0;
			$this->_data					= $projectround;
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
	public function getTable($type = 'projectround', $prefix = 'table', $config = array())
	{
		return JTable::getInstance($type, $prefix, $config);
	}
}
?>
