<?php
/**
* @version    $Id: controller.php 109 2008-05-24 11:05:07Z julienv $ 
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

$document = &JFactory::getDocument();
$document->addStyleSheet( JURI::base() . 'components/com_tracks/css/tracks.css', 'text/css', null, array( 'id' => 'tracksstyleSheet' ) );

/**
 * Tracks Component Controller
 *
 * @package		Tracks
 * @since 0.1
 */
class TracksController extends JController
{	
	function __construct($config = array())
	{
		// frontpage Editor pagebreak proxying:
		if(JRequest::getCmd('view') === 'individuals' && JRequest::getCmd('layout') === 'modal') {
			JHtml::_('stylesheet','system/adminlist.css', array(), true);
			$config['base_path'] = JPATH_COMPONENT_ADMINISTRATOR;
		}
	
		parent::__construct($config);
	}
	
	function display()
	{
		// Set a default view if none exists
		if ( ! JRequest::getCmd( 'view' ) ) {
			JRequest::setVar('view', 'projects' );
		}

		parent::display();
	}
}
?>
