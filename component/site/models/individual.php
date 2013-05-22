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
class TracksModelIndividual extends baseModel
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

        if ($this->_data->picture != '') {
          $this->_data->picture = JHTML::image(JURI::root().$this->_data->picture, $this->_data->first_name. ' ' . $this->_data->last_name, $attribs);
        } else {
          $this->_data->picture = JHTML::image(JURI::base().'media/com_tracks/images/misc/tnnophoto.jpg', $this->_data->first_name. ' ' . $this->_data->last_name, $attribs);
        }

        if ($this->_data->picture_small != '') {
          $this->_data->picture_small = JHTML::image(JURI::root().$this->_data->picture_small, $this->_data->first_name. ' ' . $this->_data->last_name, $attribs);
        } else {
          $this->_data->picture_small = JHTML::image(JURI::base().'media/com_tracks/images/misc/tnnophoto.jpg', $this->_data->first_name. ' ' . $this->_data->last_name, $attribs);
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
			       . ' CASE WHEN CHAR_LENGTH( p.alias ) THEN CONCAT_WS( \':\', p.id, p.alias ) ELSE t.id END AS projectslug '
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
		$app = JFactory::getApplication();
		$params = $app->getParams( 'com_tracks' );

		$ind = $this->getData();
		if (!$ind->id) return null;

		$query = ' SELECT rr.rank, rr.performance, rr.bonus_points, pr.project_id, '
           . '        r.name AS roundname, r.id as pr, '
		       . '        srt.name AS subroundname, '
           . '        srt.points_attribution, srt.count_points, '
           . '        p.name AS projectname, '
           . '        c.name AS competitionname, '
           . '        s.name AS seasonname, '
           . '        t.name AS teamname, '
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
           . ' WHERE rr.individual_id = ' . $this->_db->Quote($this->_id)
           . '   AND p.published AND pr.published AND psr.published '
           ;
    if ($params->get('indview_results_onlypointssubrounds', 1)) {
    	$query .= ' AND srt.count_points = 1 ';
    }

    if ($params->get('indview_results_ordering', 1) == 0) {
    	$query .= ' ORDER BY s.ordering DESC, c.ordering DESC, p.ordering DESC, pr.ordering ASC, psr.ordering ASC';
    }
    else {
    	$query .= ' ORDER BY s.ordering ASC, c.ordering ASC, p.ordering ASC, pr.ordering ASC, psr.ordering ASC';
    }

		if (!$result = $this->_getList($query)) {
			//echo $this->_db->getQuery();
		}
		return $result;
	}


  /**
   * Method to store the individual data
   *
   * @access  public
   * @return  boolean True on success
   * @since 1.5
   */
  function store($data, $picture, $picture_small)
  {
		// Require the base controller
		require_once (JPATH_COMPONENT_ADMINISTRATOR.DS.'tables'.DS.'individual.php');

    $table = $this->getTable('individual');
    $params = JComponentHelper::getParams('com_tracks');

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

    $targetpath = 'images'.DS.$params->get('default_individual_images_folder', 'tracks/individuals');

    jimport('joomla.filesystem.file');
    jimport('joomla.filesystem.folder');

    if ( !empty($picture['name']) )  {

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

    // Store the individual to the database
    if (!$table->save($data)) {
      $this->setError( $user->getError() );
      return false;
    }
    $this->_id = $table->id;

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
}
