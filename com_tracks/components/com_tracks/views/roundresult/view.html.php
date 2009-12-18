<?php
/**
* @version    $Id: view.html.php 77 2008-04-30 03:32:25Z julienv $ 
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
class TracksFrontViewRoundResult extends JView
{
    function display($tpl = null)
    {
    	global $mainframe;
    	$params = &JComponentHelper::getParams( 'com_tracks' );
    	$ordering = $params->def('subround_order', 0);
    	$ordering = ($ordering) ? 'DESC':'ASC';
    	$projectround_id = JRequest::getVar( 'pr', 0, '', 'int' );
    	
    	$model =& $this->getModel();
    	$subroundresults = $model->getSubrounds($projectround_id, 0, $ordering);
    	$round = $model->getRound( $projectround_id );
    	$project = $model->getRoundProject($projectround_id);
    	$projectparams = & $model->getParams($project->id);

    	$breadcrumbs =& $mainframe->getPathWay();
    	$breadcrumbs->addItem( $round->name,  'index.php?option=com_tracks&view=roundresult&pr=' . $projectround_id );

    	$document =& JFactory::getDocument();
    	$document->setTitle( $round->name . ' - ' . $round->project_name );

    	//print_r($subroundresults);exit;
    	$this->assignRef( 'points_attrib',   $points_attrib);
    	$this->assignRef( 'results',    $subroundresults );
    	$this->assignRef( 'round',    $round );
      $this->assignRef( 'projectparams',    $projectparams );
      
    	parent::display($tpl);
    }
}
?>
