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
require_once (JPATH_COMPONENT_ADMINISTRATOR.DS.'helpers'.DS.'imageselect.php');

/**
 * Joomla Tracks Component Front page Model
 *
 * @package     Tracks
 * @since 0.1
 */
class TracksFrontModelIndividual extends baseModel
{ 
	/**
	 * individual id
	 *
	 * @var int
	 */  
	var $_id = 0;
	
	var $_data = null;
	
	function __construct($config = array())
	{
		parent::__construct($config = array());
	  $id = JRequest::getInt('i');
    $this->setId((int)$id);
  }

  /**
   * Method to set the id
   *
   * @access  public
   * @param int details ID number
   */

  function setId($id)
  {
    // Set new details ID and wipe data
    $this->_id      = $id;
  }
	
	function getData()
	{
		if ( !$this->_data )
		{
			$query =  ' SELECT i.* '
			. ' FROM #__tracks_individuals as i '
			. ' WHERE i.id = ' . $this->_id;

			$this->_db->setQuery( $query );

			if ( $result = $this->_db->loadObjectList() ) 
			{
				$this->_data = $result[0];				
        $attribs['class']="pic";
        $nopic = $this->_data->gender == 2 ? 'tnnophoto2.jpg' : 'tnnophoto.jpg';
			
        if ($this->_data->picture != '') {
          $this->_data->picture = JHTML::image(JURI::root().$this->_data->picture, $this->_data->first_name. ' ' . $this->_data->last_name, $attribs);
        } else {
          $this->_data->picture = JHTML::image(JURI::base().'media/com_tracks/images/misc/'.$nopic, $this->_data->first_name. ' ' . $this->_data->last_name, $attribs);
        }

        if ($this->_data->picture_small != '') {
//          $this->_data->picture_small = JHTML::image(JURI::root().$this->_data->picture_small, $this->_data->first_name. ' ' . $this->_data->last_name, $attribs);
        } else {
          $this->_data->picture_small = JHTML::image(JURI::base().'media/com_tracks/images/misc/'.$nopic, $this->_data->first_name. ' ' . $this->_data->last_name, $attribs);
        }

        if ($this->_data->picture_background != '') {
          $this->_data->picture_background = JHTML::image(JURI::root().$this->_data->picture_background, $this->_data->first_name. ' ' . $this->_data->last_name, $attribs);
        } else {
          $this->_data->picture_background = JHTML::image(JURI::base().'media/com_tracks/images/misc/'.$nopic, $this->_data->first_name. ' ' . $this->_data->last_name, $attribs);
        }
        
        $this->_loadProjectDetails();                
			}
			else {
				$this->_initData();
			}
		}
		
		return $this->_data;
	}
	
	/**
	 * add info relevant to the individual in the current project, if a project is set
	 *  
	 */
	function _loadProjectDetails()
	{
		if ($this->_project_id && $this->_data)
		{
			$query = ' SELECT t.name as team_name, pi.number, ' 
			       . ' CASE WHEN CHAR_LENGTH( t.alias ) THEN CONCAT_WS( \':\', t.id, t.alias ) ELSE t.id END AS teamslug, '
			       . ' CASE WHEN CHAR_LENGTH( p.alias ) THEN CONCAT_WS( \':\', p.id, p.alias ) ELSE p.id END AS projectslug '
			       . ' FROM #__tracks_projects_individuals AS pi ' 
			       . ' LEFT JOIN #__tracks_teams AS t ON t.id = pi.team_id ' 
			       . ' LEFT JOIN #__tracks_projects AS p ON p.id = pi.project_id ' 
			       . ' WHERE pi.project_id = ' . $this->_db->Quote($this->_project_id)
			       . '   AND pi.individual_id = '. $this->_id;
			       ;
			$this->_db->setQuery($query);
			$res = $this->_db->loadObject();
			$this->_data->projectdata = $res;
		}
		else {
			$this->_data->projectdata = null;
		}
			
		return $this->_data;
	}
	
	function getRaceResults()
	{
		$app = &JFactory::getApplication();
		$params = $app->getParams( 'com_tracks' );
    
		$ind = $this->getData();
		if (!$ind->id) return null;
		
		$query = ' SELECT rr.rank, rr.performance, rr.bonus_points, rr.subround_id, '
           . '        r.name AS roundname, r.id as pr, '
           . '        rr.failed AS failed, '
           . '        rr.id AS id, '
		       . '        srt.name AS subroundname, '
           . '        srt.points_attribution, '
           . '        p.name AS projectname, p.id as project_id, p.finished, '
           . '        c.name AS competitionname, '
           . '        s.name AS seasonname, '
           . '        t.name AS teamname, '
           . '        ride.file AS ride,'
		       . ' CASE WHEN CHAR_LENGTH( r.alias ) THEN CONCAT_WS( \':\', pr.id, r.alias ) ELSE pr.id END AS prslug, '
		       . ' CASE WHEN CHAR_LENGTH( r.alias ) THEN CONCAT_WS( \':\', r.id, r.alias ) ELSE r.id END AS rslug '
		       . ' FROM #__tracks_rounds_results AS rr '
		       . ' INNER JOIN #__tracks_projects_subrounds AS psr ON psr.id = rr.subround_id '
		       . ' INNER JOIN #__tracks_subroundtypes AS srt ON srt.id = psr.type '
		       . ' INNER JOIN #__tracks_projects_rounds AS pr ON pr.id = psr.projectround_id '
		       . ' INNER JOIN #__tracks_rounds AS r ON r.id = pr.round_id'
		       . ' INNER JOIN #__tracks_projects AS p ON p.id = pr.project_id'
           . ' INNER JOIN #__tracks_seasons AS s ON s.id = p.season_id'  
           . ' INNER JOIN #__tracks_competitions AS c ON c.id = p.competition_id '
           . ' LEFT JOIN  #__tracks_teams AS t ON t.id = rr.team_id'
           . ' LEFT JOIN #__tracks_result_ride AS ride ON ride.result_id = rr.id '
           . ' WHERE rr.individual_id = ' . $this->_db->Quote($this->_id)
           . '   AND p.published AND pr.published AND psr.published '
           ;
    if ($params->get('indview_results_onlypointssubrounds', 1)) {
    	$query .= ' AND CHAR_LENGTH(srt.points_attribution) > 0 ';
    }
    
//     if ($params->get('indview_results_ordering', 1) == 0) {
//     	$query .= ' ORDER BY s.ordering DESC, c.ordering DESC, p.ordering DESC, pr.ordering ASC, psr.ordering ASC';
//     }
//     else {
//     	$query .= ' ORDER BY s.ordering ASC, c.ordering ASC, p.ordering ASC, pr.ordering ASC, psr.ordering ASC';
//     }
    
    $query .= ' ORDER BY s.ordering ASC, p.name ASC, pr.ordering ASC, psr.ordering ASC';
    
		if (!$result = $this->_getList($query)) {
			//echo $this->_db->getQuery();
		}
		return $result;
	}
   	
	/**
	 * get rankings of individual in projects
	 * 
	 * @return array
	 */
	function getRankings()
	{
		require_once (JPATH_SITE.DS.'components'.DS.'com_tracks'.DS.'sports'.DS.'default'.DS.'rankingtool.php');
		
		$app = &JFactory::getApplication();
		$params = $app->getParams( 'com_tracks' );
		
		// all individual projects
		$query = ' SELECT p.id, p.name, s.name as seasonname, c.name as competitionname, p.finished, '
		       . ' CASE WHEN CHAR_LENGTH( p.alias ) THEN CONCAT_WS( \':\', p.id, p.alias ) ELSE p.id END AS projectslug '
		       . ' FROM #__tracks_projects_individuals AS pi '
		       . ' INNER JOIN #__tracks_individuals AS i ON i.id = pi.individual_id ' 
		       . ' INNER JOIN #__tracks_projects AS p ON pi.project_id = p.id ' 
		       . ' INNER JOIN #__tracks_seasons AS s ON p.season_id = s.id ' 
		       . ' INNER JOIN #__tracks_competitions AS c ON p.competition_id = c.id ' 
		       . ' WHERE i.id = ' . $this->_db->Quote($this->_id)
		       ;
		
		if ($params->get('indview_results_ordering', 1) == 0) {
			$query .= ' ORDER BY s.ordering DESC, c.ordering DESC, p.ordering DESC';
		}
		else {
			$query .= ' ORDER BY s.ordering ASC, c.ordering ASC, p.ordering ASC';
		}
		$this->_db->setQuery($query);
		$projects = $this->_db->loadObjectList();
		
		$res = array();
		foreach ((array) $projects as $project)
		{
			$rankingtool = new TracksRankingTool($project->id);
			$project->ranking = $rankingtool->getIndividualRanking($this->_id);
			$res[$project->id] = $project;
		}
		return $res;
	}
	
  /**
   * Method to store the individual data
   *
   * @access  public
   * @return  boolean True on success
   * @since 1.5
   */
  function store($data, $picture, $picture_small, $picture_background)
  {
		// Require the base controller
		require_once (JPATH_COMPONENT_ADMINISTRATOR.DS.'tables'.DS.'individual.php');
		
    $table = $this->getTable('individual');
    $params = &JComponentHelper::getParams('com_tracks');
    
  	$user   = JFactory::getUser();
    $username = $user->get('username');
    
    if ($data['id']) 
    {
    	$table->load($data['id']);
    	
     	if ($table->user_id != $user->get('id') && !$user->authorise('core.manage', 'com_tracks')) {
     		JError::raiseError(403, JText::_('COM_TRACKS_ACCESS_NOT_ALLOWED'));
     	}
    }
    		
    // Bind the form fields to the user table
    if (!$table->bind($data)) {
      $this->setError($this->_db->getErrorMsg());
      return false;
    }
    
    if (!$table->id) {
    	$table->created = gmdate('Y-m-d H:i:s');
    	$table->created_by = JFactory::getUser()->get('id');
    }
    $table->modified = gmdate('Y-m-d H:i:s');
    $table->modified_by = JFactory::getUser()->get('id');
    
    $targetpath = 'images'.DS.$params->get('default_individual_images_folder', 'tracks/individuals');

    jimport('joomla.filesystem.file');
    jimport('joomla.filesystem.folder');

    if ( !empty($picture['name']) )  {
    	if ($table->id) {
    		$this->delpic();
    	}

	    $base_Dir = JPATH_SITE.DS.$targetpath.DS;
	    if (!JFolder::exists($base_Dir)) {
	    	JFolder::create($base_Dir);
	    }

      //check the image
      $check = ImageSelect::check($picture);

      if ($check === false) {
	      $this->setError( 'IMAGE CHECK FAILED' );
	      return false;
      }

      //sanitize the image filename
      $filename = ImageSelect::sanitize($base_Dir, $picture['name']);
      $filepath = $base_Dir . $filename;
      //dump($filepath);

      if (!JFile::upload( $picture['tmp_name'], $filepath )) {
        $this->setError( JText::_('COM_TRACKS_UPLOAD_FAILED' ) );
        return false;
      } else {
        $table->picture = $targetpath.DS.$filename;
      }
    } else {
      //keep image if edited and left blank
      unset($table->picture);
    }//end image upload if

    if ( !empty($picture_small['name']) )  {
    	if ($table->id) {
    		$this->delpic('small');
    	}

      jimport('joomla.filesystem.file');

      $base_Dir = JPATH_SITE.DS.$targetpath.DS.'small'.DS;
      if (!JFolder::exists($base_Dir)) {
      	JFolder::create($base_Dir);
      }

      //check the image
      $check = ImageSelect::check($picture_small);

      if ($check === false) {
        $this->setError( 'IMAGE CHECK FAILED' );
        return false;
      }

      //sanitize the image filename
      $filename = ImageSelect::sanitize($base_Dir, $picture_small['name']);
      $filepath = $base_Dir . $filename;

      if (!JFile::upload( $picture_small['tmp_name'], $filepath )) {
        $this->setError( JText::_('COM_TRACKS_UPLOAD_FAILED' ) );
        return false;
      } else {
        $table->picture_small = $targetpath.DS.'small'.DS.$filename;
      }
    } else {
      //keep image if edited and left blank
      unset($table->picture_small);
    }//end image upload if

    if ( !empty($picture_background['name']) )  {
    	if ($table->id) {
    		$this->delpic('back');
    	}

	    $base_Dir = JPATH_SITE.DS.$targetpath.DS.'background'.DS;
	    if (!JFolder::exists($base_Dir)) {
	    	JFolder::create($base_Dir);
	    }

      //check the image
      $check = ImageSelect::check($picture_background);

      if ($check === false) {
	      $this->setError( 'IMAGE CHECK FAILED' );
	      return false;
      }

      //sanitize the image filename
      $filename = ImageSelect::sanitize($base_Dir, $picture_background['name']);
      $filepath = $base_Dir . $filename;
      //dump($filepath);

      if (!JFile::upload( $picture_background['tmp_name'], $filepath )) {
        $this->setError( JText::_('COM_TRACKS_UPLOAD_FAILED' ) );
        return false;
      } else {
        $table->picture_background = $targetpath.DS.'background'.DS.$filename;
      }
    } else {
      //keep image if edited and left blank
      unset($table->picture_background);
    }//end image upload if

    // Store the individual to the database
    if (!$table->save($data)) {
      $this->setError( $user->getError() );
      return false;
    }
		$this->_id = $table->id;

		// clear sponsors
		$query = ' DELETE FROM #__tracks_individuals_sponsors '
		       . ' WHERE individual_id = ' . $this->_db->Quote($table->id);
		$this->_db->setQuery($query);
		$res = $this->_db->query();

		// save sponsors
		foreach ($data['sponsors'] as $sp)
		{
			$obj = JTable::getInstance('Individualsponsor', 'trackstable');
			$obj->individual_id = $table->id;
			$obj->sponsor_id = $sp;
			$obj->store();
		}
    
    return $this->_id;
  }
  
  /**
   * returns individual id if stored.
   *
   * @return int
   */
  function getId()
  {
  	return $this->_id;
  }
  
  function _initData()
  {
      $object = new stdClass();
      $object->id               = 0;
      $object->last_name        = "";
      $object->first_name       = "";
      $object->nickname         = "";
      $object->dob              = null;
      $object->height           = null;
      $object->weight           = null;
      $object->hometown         = null;
      $object->country          = null;
      $object->user_id          = 0;
      $object->picture          = null;
      $object->picture_small    = null;
      $object->picture_background = null;
      $object->address          = null;
      $object->postcode         = null;
      $object->city             = null;
      $object->state            = null;
      $object->country_code     = null;
      $object->description      = null;
      $object->checked_out      = 0;
      $object->checked_out_time = 0;
      $this->_data          = $object;
      return (boolean) $this->_data;
  }
  
  /**
   * adds a hit
   * 
   */
  public function addHit()
  {
  	$query = ' UPDATE #__tracks_individuals SET hits = hits+1, last_hit = NOW()'
  	       . ' WHERE id = ' . $this->_db->Quote($this->_id);
  	$this->_db->setQuery($query);
  	$res = $this->_db->query();
  }
  
  /**
   * returns all individuals the user competed against
   * 
   * @return array
   */
  public function getCompetedAgainst()
  {
  	$user = &JFactory::getUser();
  	$ind = $this->getData();
  	if (!$user->get('id') || $user->get('id') != $ind->user_id) {
  		//return false;
  	}
  	  	
  	$query = $this->_db->getQuery(true);
  	$query->select('sr.id');
  	$query->from('#__tracks_rounds_results AS rr');
  	$query->join('INNER', '#__tracks_projects_subrounds AS sr ON sr.id = rr.subround_id');
  	$query->where('rr.individual_id = '.$this->_id);
  	$this->_db->setQuery($query);
  	$res = $this->_db->loadResultArray();
  	if (!$res || !count($res)) {
  		return false;
  	}
  	
  	$query = $this->_db->getQuery(true);
  	$query->select('COUNT(i.id) AS num, i.id, i.first_name, i.last_name, i.picture_small, i.country_code');
  	$query->select('CASE WHEN CHAR_LENGTH( i.alias ) THEN CONCAT_WS( \':\', i.id, i.alias ) ELSE i.id END AS slug');
  	$query->from('#__tracks_individuals AS i');
  	$query->join('INNER', '#__tracks_rounds_results AS rr ON rr.individual_id = i.id');
  	$query->join('INNER', '#__tracks_projects_subrounds AS sr ON sr.id = rr.subround_id');
  	$query->where('sr.id IN ('.implode(",", $res).')');
  	$query->where('i.id <> '.$this->_id);
  	$query->order(array('i.first_name ASC', 'i.last_name', 'i.first_name'));
  	$query->group('i.id');
  	$this->_db->setQuery($query);
  	$res = $this->_db->loadObjectList();
  	
  	return $res;
  }
  
  /**
   * returns subround ids where current user participated too
   * 
   * @return array
   */
  public function getUserParticipatedAgainst()
  {
  	$user = &JFactory::getUser();
  	$ind = $this->getData();
  	if (!$user->get('id') || $user->get('id') == $ind->user_id) {
  		return false;
  	}
  	
  	// does the user have a corresponding ind
  	$query = ' SELECT id ' 
  	       . ' FROM #__tracks_individuals ' 
  	       . ' WHERE user_id = ' . $user->get('id');
  	$this->_db->setQuery($query);
  	$uind = $this->_db->loadResult();
  	
  	if (!$uind) {
  		return false;
  	}
  	
  	$query = ' SELECT rr1.subround_id '
		       . ' FROM #__tracks_rounds_results AS rr1 ' 
		       . ' INNER JOIN #__tracks_rounds_results AS rr2 ON rr1.subround_id = rr2.subround_id'
		       . ' WHERE rr1.individual_id = ' . $ind->id
		       . '   AND rr2.individual_id = ' . $uind
		;
  	$this->_db->setQuery($query);
  	$res = $this->_db->loadResultArray();
  	
  	return $res;
  }
  
  public function delpic($type = '')
  {
		require_once (JPATH_COMPONENT_ADMINISTRATOR.DS.'tables'.DS.'individual.php');
  	$user = &JFactory::getUser();
		
    $table = $this->getTable('individual');
  	$table->load($this->_id);
  	
  	if (!$user->get('id') || $user->get('id') != $table->user_id) {
//  		Jerror::raiseError(403, 'not allowed');
  	}
  	
  	switch($type)
  	{
  		case 'back':
  		case 'background':
  			$file = $table->picture_background;
  			$table->picture_background = '';
  			break;
  		case 'small':
  			$file = $table->picture_small;
  			$table->picture_small = '';
  			break;
  		default:
  			$file = $table->picture;
  			$table->picture = '';
  	}
  	if (!$table->store()) {
  		$this->setError($table->getError());
  		return false;
  	}
  	
  	// remove file and thumbnails from file system too
  	if ($file && file_exists(JPATH_SITE.DS.$file)) 
  	{
  		unlink($file);
  		$thumb_key = md5(basename($file));
  		if (file_exists(dirname(JPATH_SITE.DS.$file).DS.'small')) 
  		{
  			$thumbs = JFolder::files(dirname(JPATH_SITE.DS.$file).DS.'small');
  			foreach ($thumbs as $th)
  			{
  				if (strpos($th, $thumb_key) === 0) {
  					unlink(dirname(JPATH_SITE.DS.$file).DS.'small'.DS.$th);
  				}
  			}
  		}
  	}
  	
  	return true;
  }  

  /**
   * stores a tip from pro
   * @param int $individual_id
   * @param string $category
   * @param string $tip
   * @return boolean true on success
   */
  public function storeTip($individual_id, $category, $tip)
  {
  	$query = ' INSERT INTO #__tracks_tips ' 
  	       . ' SET individual_id = '.$individual_id
  	       . '   , category = '.$this->_db->Quote($category)
  	       . '   , tips = '.$this->_db->Quote($tip)
  	       . '   , time = NOW() '
  	       ;
  	$this->_db->setQuery($query);
  	$res = $this->_db->query();
  	if (!$res) {
  		$this->setError($this->_db->getErrorMsg());
  		return false;
  	}
  	return true;
  }

}
