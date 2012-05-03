<?php
/**
* @version    $Id: controller.php 133 2008-06-08 10:24:29Z julienv $ 
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

jimport('joomla.application.component.controller');

/**
 * Tracks Component Base Controller
 *
 * @package		Tracks
 * @since 0.1
 */
class BaseController extends JController
{
  function display()
  {
    // Set a default view if none exists
    if ( ! JRequest::getCmd( 'view' ) ) {
      JRequest::setVar('view', 'projects' );
    }
    $viewName = JRequest::getCmd( 'view' );
    $layout   = JRequest::getCmd( 'layout' );
    
    $showmenu = true;
    switch (strtolower($viewName))
    {
      case 'about':
    	case 'competition':
      case 'individual':
      case 'project':
      case 'projectindividual':
      case 'projectround':
      case 'projectroundresult':
      case 'round':
      case 'season':
      case 'subround':
      case 'subroundresult':
      case 'subroundtype':
      case 'team':
      case 'competition':
      case 'imagehandler':
      	$showmenu = false;
      	break;      	
    }
    
    if ($showmenu && $layout != 'modal') 
    {
	    $this->ShowMenuStart();
			parent::display();
	    $this->ShowMenuEnd();    	
    }
    else {
			parent::display();
    }
	}

	function ShowMenuStart()
	{
		$mainframe = &JFactory::getApplication();
		$option = JRequest::getCmd('option');

		$document =& JFactory::getDocument();

		$viewType	= $document->getType();
		$view = & $this->getView( 'Menu', $viewType );

		if ($model = & $this->getModel('Menu', 'TracksModel')) 
		{
			// Push the model into the view (as default)
			$view->setModel($model, true);
		}
		$view->setLayout('start');
		$view->display();
	}

	function ShowMenuEnd()
	{
		$mainframe = &JFactory::getApplication();
		$option = JRequest::getCmd('option');

		$document =& JFactory::getDocument();

		$viewType	= $document->getType();
		$view = & $this->getView( 'Menu', $viewType );

		if ($model = & $this->getModel('Menu', 'TracksModel')) 
		{
			// Push the model into the view (as default)
			$view->setModel($model, true);
		}
		$view->setLayout('end');
		$view->display();
	}
}
?>
