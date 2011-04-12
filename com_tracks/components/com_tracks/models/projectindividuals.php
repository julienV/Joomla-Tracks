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
require_once( 'base.php' );
/**
 * Joomla Tracks Component Front page Model
 *
 * @package		Tracks
 * @since 0.1
 */
class TracksFrontModelProjectindividuals extends baseModel
{
	/**
	 * associated project
	 */
	var $project = null;
    	
	function getIndividuals($project_id = 0) 
	{
		if (!$project_id) {
			$this->setError(JText::_('No project specified'));
			return null;
		}
		
		$query =  ' SELECT i.id, i.first_name, i.last_name, i.country_code, i.picture, i.picture_small, '
		        . ' pi.number, pi.team_id, '
            . ' t.name as team_name, t.picture_small AS team_logo, '
            . ' CASE WHEN CHAR_LENGTH( i.alias ) THEN CONCAT_WS( \':\', i.id, i.alias ) ELSE i.id END AS slug, '
            . ' CASE WHEN CHAR_LENGTH( t.alias ) THEN CONCAT_WS( \':\', t.id, t.alias ) ELSE t.id END AS teamslug '
						. ' FROM #__tracks_projects_individuals as pi '
            . ' INNER JOIN #__tracks_individuals as i ON i.id = pi.individual_id '
            . ' LEFT JOIN #__tracks_teams as t ON t.id = pi.team_id '
            . ' WHERE pi.project_id = ' . $project_id
						. ' ORDER BY pi.number ASC, i.last_name ASC, i.first_name ASC ';

		$this->_db->setQuery( $query );

		$result = $this->_db->loadObjectList();
		
	  $count = count($result);
    for($i = 0; $i < $count; $i++)
    {
        $obj =& $result[$i];
        $attribs['class']="pic";
        
        if ($obj->picture != '') {
          $obj->picture = JHTML::image(JURI::root().'media/com_tracks/images/individuals/'.$obj->picture, $obj->first_name. ' ' . $obj->last_name, $attribs);
        } else {
          $obj->picture = JHTML::image(JURI::base().'media/com_tracks/images/misc/tnnophoto.jpg', $obj->first_name. ' ' . $obj->last_name, $attribs);
        }
    }
    return $result;
	}
    
}
