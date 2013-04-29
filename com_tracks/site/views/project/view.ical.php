<?php
/**
* @version    $Id: view.html.php 43 2008-02-24 23:47:38Z julienv $ 
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

jimport( 'joomla.application.component.view');
require_once (JPATH_COMPONENT.DS.'helpers'.DS.'iCalcreator.class.php');

/**
 * HTML View class for the Tracks component
 *
 * @static
 * @package		Tracks
 * @since 0.1
 */
class TracksViewProject extends JView
{
    function display($tpl = null)
    {
			$mainframe = JFactory::getApplication();
			$option = JRequest::getCmd('option');
    	  	          
    	$model = $this->getModel();
      $results = $model->getResults( JRequest::getVar( 'p', 0, '', 'int' ) );
      $project = $model->getProject( JRequest::getVar( 'p', 0, '', 'int' ) );
        
      $document = JFactory::getDocument();
      
      $v = new vcalendar();                          // initiate new CALENDAR
      $v->setConfig( 'project'.$project->id
             , $mainframe->getCfg('live_site') );                   // config with site domain
			$v->setProperty( 'X-WR-CALNAME'
			               , $project->name );          // set some X-properties, name, content.. .
			$v->setProperty( 'X-WR-CALDESC'
			               , JText::_('COM_TRACKS_Project_calendar') );
			$v->setConfig( "filename", 'project_'.$project->id.'.ics' ); 

			foreach ((array) $results AS $result)
			{
				if (!ereg('([0-9]{4})-([0-9]{1,2})-([0-9]{1,2}) ([0-9]{1,2}):([0-9]{1,2}):([0-9]{1,2})',$result->start_date, $start_date)) {
					continue; 
				}
				$e = new vevent();                             // initiate a new EVENT
				$e->setProperty( 'categories', $project->name );                   // catagorize
				$e->setProperty( 'dtstart'
				               ,  $start_date[1], $start_date[2], $start_date[3], $start_date[4], $start_date[5], $start_date[6]);

			  if (ereg('([0-9]{4})-([0-9]{1,2})-([0-9]{1,2}) ([0-9]{1,2}):([0-9]{1,2}):([0-9]{1,2})',$result->end_date, $end_date)) {
          $e->setProperty( 'dtend'
                       ,  $end_date[1], $end_date[2], $end_date[3], $end_date[4], $end_date[5], $end_date[6]);
			  }
			  $description = array();
			  $description[] = $result->round_name;
			  if ($result->winner && $result->winner->last_name) 
			  {
			  	$winner = JText::_('COM_TRACKS_Winner').$result->winner->first_name.' '.$result->winner->last_name;
			  	if ($result->winner->team_name) {
			  		$winner .= ' ('.$result->winner->team_name.')';
			  	}
			  	$description[] = $winner;
			  }
			  $description = implode('\\n', $description);
				$e->setProperty( 'description'
				               , $description );    // describe the event
				$e->setProperty( 'location'
				               , $result->round_name );                     // locate the event
				
				$v->addComponent( $e );                        // add component to calendar
			}
			
			/* alt. production */
			// $v->returnCalendar();                       // generate and redirect output to user browser
			/* alt. dev. and test */
			$str = $v->createCalendar();                   // generate and get output in string, for testing?
//      echo $str;return;
			$v->returnCalendar();
    }
}
?>