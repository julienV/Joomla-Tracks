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
    	$mainframe = &JFactory::getApplication();
    	
    	          
    	$model =& $this->getModel();
    	$results = $model->getResults( JRequest::getVar( 'p', 0, '', 'int' ) );
    	$project = $model->getProject( JRequest::getVar( 'p', 0, '', 'int' ) );
    	$params =& $model->getParams();

    	$document =& JFactory::getDocument();
    	$document->setTitle( $project->name );

    	$breadcrumbs =& $mainframe->getPathWay();
    	$breadcrumbs->addItem( $project->name, 'index.php?option=com_tracks&view=project&p=' . $project->id );

    	$this->assignRef( 'results',    $results );
    	$this->assignRef( 'project',    $project );
    	$this->assignRef( 'params',    $params );

    	parent::display($tpl);
    }
}
?>