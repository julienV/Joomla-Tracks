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
class TracksModelRound extends baseModel
{   
    function getRound( $round_id )
    {
        if ( $round_id )
        {
            $query =  ' SELECT r.* '
                . ' FROM #__tracks_rounds AS r '
                . ' WHERE r.id = ' . $round_id
                . ' AND r.published = 1 ';
                
            $this->_db->setQuery( $query );
        
            if ( $result = $this->_db->loadObjectList() ) {
                return $result[0];
            }
            else return $result;            
        }
        else return null;
    }
    
}
