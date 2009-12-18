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
 * Joomla Tracks Component subround Model
 *
 * @author Julien Vonthron <julien.vonthron@gmail.com>
 * @package   Tracks
 * @since 0.2
 */
class TracksModelSubround extends TracksModelItem
{
	/**
	 * associated project round id
	 *
	 * @var int
	 */
	var $_prid = 0;
	
 /**
   * Constructor
   *
   * @since 0.9
   */
  function __construct()
  {
    parent::__construct();

    $prid = JRequest::getVar('prid',  0, '', 'int');
    $this->setProjectroundId($prid);
  }
  
  /**
   * Method to set the project round id
   *
   * @access  public
   * @param int 
   */
  function setProjectroundId($prid)
  {
    // Set item id and wipe data
    $this->_prid    = $prid;
  }
  
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
    JArrayHelper::toInteger($cid);

		if (count( $cid ))
		{
      $cids = implode( ',', $cid );			
			$query = ' DELETE rr FROM #__tracks_projects_subrounds AS sr, #__tracks_rounds_results AS rr '
             . ' WHERE sr.id IN ( '.$cids.' ) AND sr.id = rr.subround_id ';
      
      $this->_db->setQuery( $query );
      if(!$this->_db->query()) 
      {
        $this->setError($this->_db->getErrorMsg());
        return false;
      }
      else 
      {
				$query = 'DELETE FROM #__tracks_projects_subrounds'
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

			$query = 'UPDATE #__tracks_projects_subrounds'
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
			$query = 'SELECT sr.* '.
					' FROM #__tracks_projects_subrounds AS sr ' .
          ' WHERE sr.id = '.(int) $this->_id;
			$this->_db->setQuery($query);
			$this->_data = $this->_db->loadObject();
			return (boolean) $this->_data;
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
			$obj = new stdClass();
			$obj->id					      = 0;
      $obj->projectround_id	  = 0;
      $obj->type              = 0;
      $obj->start_date				= 0;
      $obj->end_date					= 0;
      $obj->description			  = null;
      $obj->comment					  = null;
			$obj->checked_out			  = 0;
			$obj->checked_out_time	= 0;
			$obj->ordering			    = 0;
      $obj->published         = 0;
			$this->_data					  = $obj;
			return (boolean) $this->_data;
		}
		return true;
	}
	
  /**
   * Method to return a subround types array (id, name)
   *
   * @access  public
   * @return  array seasons
   * @since 0.2
   */
  function getSubroundTypes() 
  {
    $query = 'SELECT id, CONCAT(name, " (", note, ")") as name'
          . ' FROM #__tracks_subroundtypes '
          . ' ORDER BY name ASC';
    $this->_db->setQuery( $query );
    $result = $this->_db->loadObjectList();
    
    if ( !$result ) {
      $this->setError($this->_db->getErrorMsg());
      return false;
    }
    else return $result;
  }
}
?>
