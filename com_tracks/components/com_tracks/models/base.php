<?php
/**
* @version    $Id: base.php 54 2008-04-22 14:50:30Z julienv $ 
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

/**
 * Joomla Tracks Component Front page Model
 *
 * @package		Tracks
 * @since 0.1
 */
class baseModel extends JModel
{
    /**
     * associated project
     */
    var $project = null;

    var $_project_id = null;
    
		/**
		 * reference to ranking class
		 * @var unknown_type
		 */
		var $_rankingtool = null;
		
		var $_id = 0;
		
		/** 
		 * caching for data
		 */
		var $_data = null;

		function __construct($config = null)
		{
			parent::__construct($config);
			
			$project = JRequest::getInt('p');
			
			$this->setProjectId($project);
		}
	
		/**
		 * set id of object to display
		 * 
		 * depends on view context...
		 * 
		 * @param int $id
		 */
		function setId($id)
		{
			$this->_id = (int) $id;
			$this->_data = null;
		}
    
		/**
		 * set id of project to display
		 * 
		 * @param int $id
		 */
		function setProjectId($id)
		{
			$this->_project_id = (int) $id;
			$this->project = null;
		}
		
		
		
    /**
     * returns project object
     * 
     * @param int project_id          
     * @return object project
     */              
    function getProject( $project_id = 0 )
    {
    	if ( $this->project && ( $project_id == 0 || $project_id == $this->project->id ) ) {
        return $this->project;
      }
      if ($project_id && $project_id != $this->_project_id) {
      	$this->setProjectId($project_id);
      }
      
      $query =   ' SELECT * '
               . ' FROM #__tracks_projects AS p '
               . ' WHERE p.id = ' . $this->_project_id;
                
      $this->_db->setQuery( $query );
        
      if ( $result = $this->_db->loadObjectList() ) {
        $this->project = $result[0];
      	return $this->project;
      }
      else {
        return $result;
      }
    }    
        
    function getParams($project_id = 0, $xml='')
    {
      $project = $this->getProject( $project_id );
    	if ($xml == '') 
    	{
        $file   = JPATH_COMPONENT_ADMINISTRATOR.DS.'models'.DS.'project.xml';
        $params = new JParameter( $project->params, $file );
    	}
    	else 
    	{
		    $query = ' SELECT settings '
		           . ' FROM #__tracks_project_settings '
		           . ' WHERE project_id = ' . $this->_db->Quote($this->_project_id)
               . '   AND xml = ' . $this->_db->Quote($xml)
               ;
	      $this->_db->setQuery($query);
	      if ($settings = $this->_db->loadObject())
	      {
	        $xmlfolder = JPATH_COMPONENT.DS.'projectparameters'.DS.'default';
	        $params = new JParameter( $settings, $xmlfolder.DS.$xml ); 	      	
	      }
	      else {
	      	$params = false;
	      }
    	}
      /*print_r ($params->getParams('params','ranking view'));
      print_r ($params->getGroups());exit;*/
      return $params;
    }
	
	function _reset()
	{
		$this->_project_id = 0;
		$this->_id         = 0;
		$this->project     = null;
	}
    
	function _getRankingTool()
	{
		if (empty($this->_rankingtool)) 
		{
			// sport specific, for later ?
			require_once (JPATH_SITE.DS.'components'.DS.'com_tracks'.DS.'sports'.DS.'default'.DS.'rankingtool.php');
			$this->_rankingtool = new TracksRankingTool($this->_project_id);
		}
		return $this->_rankingtool;
	}
}
