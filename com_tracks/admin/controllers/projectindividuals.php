<?php
/**
* @version    $Id: projectindividual.php 94 2008-05-02 10:28:05Z julienv $ 
* @package    JoomlaTracks
* @copyright  Copyright (C) 2008 Julien Vonthron. All rights reserved.
* @license    GNU/GPL, see LICENSE.php
* Joomla Tracks is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();

jimport('joomla.application.component.controller');

/**
 * Joomla Tracks Component Controller
 *
 * @package   Tracks
 * @since 0.1
 */
class TracksControllerProjectindividuals extends BaseController
{
  function display() {
  	
    switch($this->getTask())
    {
      case 'assign'     :
      {
        JRequest::setVar( 'hidemainmenu', 1 );
        JRequest::setVar( 'layout', 'assign'  );
        JRequest::setVar( 'view'  , 'projectindividuals');
      } break;
    }
    parent::display();
  }
}
?>