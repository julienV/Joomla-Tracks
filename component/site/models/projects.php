<?php
/**
* @version    $Id: projects.php 109 2008-05-24 11:05:07Z julienv $ 
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
require_once( 'base.php' );

/**
 * Joomla Tracks Component Front page Model
 *
 * @package		Tracks
 * @since 0.1
 */
class TracksModelProjects extends baseModel
{
    /**
    * Gets the published projects (season and competition have to be published too)
    * @return string The projects to be displayed to the user
    */
    function getProjects()
    {
    	$app = JFactory::getApplication();
    	$params = $app->getParams( 'com_tracks' );
    	
    	$order = $params->get('order', 0);
      $order_dir = $params->get('order_dir', 'ASC');
      
      switch ($order) {
      	
      	case 0:
      		$ordering = ' ORDER BY s.ordering ' . $order_dir . ', c.ordering ' . $order_dir . ', p.ordering ' . $order_dir;
      		break;
      	case 1:
          $ordering = ' ORDER BY c.ordering ' . $order_dir . ', s.ordering ' . $order_dir . ', p.ordering ' . $order_dir;
          break;
        case 2:
          $ordering = ' ORDER BY p.ordering ' . $order_dir;
          break;
                	
      }
      
      $query = 	' SELECT p.*, '
              . ' s.id AS season_id, s.name AS season_name, '
              . ' c.id AS competition_id, c.name AS competition_name, ' 
              . ' CASE WHEN CHAR_LENGTH( p.alias ) THEN CONCAT_WS( \':\', p.id, p.alias ) ELSE p.id END AS slug '
              . ' FROM #__tracks_projects AS p '
              . ' INNER JOIN #__tracks_seasons AS s ON s.id = p.season_id '
              . ' INNER JOIN #__tracks_competitions AS c ON c.id = p.competition_id '
              . ' WHERE p.published = true AND s.published = true AND c.published = true '
              . $ordering
              ;
              
      $this->_db->setQuery( $query );
      $result = $this->_db->loadObjectList();
      return $result;
    }
}
