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

/**
 * Joomla Tracks Component Front page Model
 *
 * @package     Tracks
 * @since 0.1
 */
class TracksFrontModelTeam extends baseModel
{   
    function getData( $id )
    {
        if ( $id )
        {
	        $query =  ' SELECT t.* '
	        . ' FROM #__tracks_teams as t '
            . ' WHERE t.id = ' . $id;
                
            $this->_db->setQuery( $query );
        
            if ( $result = $this->_db->loadObjectList() ) {
			        $this->_data = $result[0];        
			        $attribs['class']="pic";
			      
			        if ($this->_data->picture != '') {
			          $this->_data->picture = JHTML::image(JURI::root().'media/com_tracks/images/teams/'.$this->_data->picture, $this->_data->name, $attribs);
			        } else {
			          //$this->_data->picture = JHTML::image(JURI::base().'media/com_tracks/images/misc/tnnophoto.jpg', $this->_data->name, $attribs);
			        }
            }
            else return $result;            
        }
        else {
        	return null;
        }
        return $this->_data;
    }
    
}
