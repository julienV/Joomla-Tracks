<?php
/**
* @version    $Id: view.html.php 135 2008-06-08 21:50:12Z julienv $ 
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

/**
 * HTML View class for the Tracks component
 *
 * @static
 * @package		Tracks
 * @since 0.1
 */
class TracksViewProjectresults extends JView
{
  function display($tpl = null)
  {
    $mainframe = JFactory::getApplication();
		$option = JRequest::getCmd('option');

    $project_id = JRequest::getVar( 'p', 0, '', 'int' );
    
    $data        = $this->get('data');
    $rounds      = $this->get('rounds');
    $project     = $this->get('project');
    
    $params = $this->get('Params');
    $params->merge( JComponentHelper::getParams( 'com_tracks' ) );
    
    $title = JText::sprintf('COM_TRACKS_view_project_results', $project->name );
    
    $breadcrumbs = $mainframe->getPathWay();
    $breadcrumbs->addItem( $title, TracksHelperRoute::getProjectResultRoute($project_id) );

    $document = JFactory::getDocument();
    $document->setTitle( $title );

    $this->assignRef( 'params',    $params );
    $this->assignRef( 'project',   $project );
    $this->assignRef( 'rows',      $data );
    $this->assignRef( 'rounds',    $rounds );
    $this->assign( 'title',        $title );

    parent::display($tpl);
  }
}
?>
