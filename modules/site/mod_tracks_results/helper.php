<?php
/**
* @version    $Id: helper.php 123 2008-05-30 09:23:23Z julienv $ 
* @package    JoomlaTracks
* @subpackage ResultsModule
* @copyright  Copyright (C) 2008 Julien Vonthron. All rights reserved.
* @license    GNU/GPL, see LICENSE.php
* Joomla Tracks is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/

// no direct access
defined('_JEXEC') or die('Restricted access');

//require_once (JPATH_SITE.DS.'components'.DS.'com_tracks'.DS.'helpers'.DS.'route.php');

class modTracksResults
{
	/**
	 * instance of the ranking model
	 *
	 * @var object
	 */
  var $_model = null;
  
  /**
	 * Round to display
	 *
	 * @var object
	 */
  var $_round = null;
  
  function _getModel()
  {       
    if ( $this->_model == null )
    {
      require_once (JPATH_SITE.DS.'components'.DS.'com_tracks'.DS.'models'.DS.'roundresult.php');
      $this->_model = new TracksModelRoundResult();
    }
    return $this->_model;      
  }
  
  function getList(&$params)
	{
    $model = $this->_getModel();
    $round = $this->getRound($params);
		$result = $model->getSubrounds( $round->projectround_id, $params->get('subroundtype_id') );
		if ($result) return $result[0];
		else return null;
	}
	
	function getProject(&$params)
	{
	  $model = $this->_getModel();    
    return $model->getProject( $params->get('project_id') );
	}
	
	function getRound(&$params)
	{
    if ( $this->_round ) return $this->_round;
    
    $db = JFactory::getDBO();
    
    $sql = ' SELECT pr.id AS projectround_id, pr.start_date, pr.end_date, pr.round_id, '
         . ' r.name, '
         . ' CASE WHEN CHAR_LENGTH( r.alias ) THEN CONCAT_WS( \':\', r.id, r.alias ) ELSE r.id END AS slug '
         . ' FROM #__tracks_projects_rounds AS pr '
         . ' INNER JOIN #__tracks_rounds AS r ON r.id = pr.round_id '
         . ' WHERE project_id = ' . $params->get('project_id')
         . '   AND pr.published = 1 '
         . ' ORDER BY start_date ASC, pr.ordering ASC '
         ;
    $db->setQuery( $sql );
    $rounds = $db->loadObjectList();
    
    if ( $db->getErrorNum() ) {
      echo $db->getErrorMsg();
    }
    
    if ( $rounds )
    {
      $this->_round = $rounds[0];
      
      // advance round until round start date is in the future.
      foreach ($rounds as $r)
      {
        if ( $r->start_date != '0000-00-00 00:00:00' ) 
        {
          if ( strtotime($r->start_date) > time() ) {
            break;
          }
        }
        else {
          break; // rounds need to have at least a start date for this module !
        }
        $this->_round = $r;
      }
    }
    return $this->_round;
  }
}
