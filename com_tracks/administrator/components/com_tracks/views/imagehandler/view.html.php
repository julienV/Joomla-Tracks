<?php
/**
* @version    $Id$ 
* @package    JoomlaTracks
* @copyright  Copyright (C) 2008 Julien Vonthron. All rights reserved.
* @license    GNU/GPL, see LICENSE.php
* Joomla Tracks is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/

defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.application.component.view');
require_once (JPATH_COMPONENT.DS.'helpers'.DS.'imageselect.php');

/**
 * View class for tracks imageselect screen
 * Based on the Joomla! media component
 *
 * @package Joomla
 * @subpackage Tracks
 * @since 0.9
 */
class TracksViewImagehandler extends JView  {

	/**
	 * Image selection List
	 *
	 * @since 0.9
	 */
	function display($tpl = null)
	{
		$mainframe = &JFactory::getApplication();
		$option = JRequest::getCmd('option');
		
		$document =& JFactory::getDocument();
		
		if($this->getLayout() == 'upload') {
			$this->_displayupload($tpl);
			return;
		}

		//get vars
    $type     = JRequest::getVar( 'type' );
    $folder = ImageSelect::getfolder($type);
    $field = JRequest::getVar( 'field' );
		$search 	= $mainframe->getUserStateFromRequest( 'com_tracks.imageselect', 'search', '', 'string' );
		$search 	= trim(JString::strtolower( $search ) );
		
    //add css
    $document->addStyleSheet('components/com_tracks/assets/css/tracksbackend.css');

		JRequest::setVar( 'folder', $folder );

		// Do not allow cache
		JResponse::allowCache(false);

		//get images
		$images 	= $this->get('Images');
		$pageNav 	= & $this->get('Pagination');
		
		if (count($images) > 0 || $search) {
			$this->assignRef('images', 	$images);
      $this->assignRef('type',  $type);
			$this->assignRef('folder', 	$folder);
			$this->assignRef('search', 	$search);
			$this->assignRef('state', 	$this->get('state'));
			$this->assignRef('pageNav', $pageNav);
			$this->assignRef('field',   $field);
			parent::display($tpl);
		} else {
			//no images in the folder, redirect to uploadscreen and raise notice
			JError::raiseNotice('SOME_ERROR_CODE', JText::_('NO IMAGES AVAILABLE'));
			$this->setLayout('upload');
			$this->_displayupload($tpl);
			return;
		}
	}

	function setImage($index = 0)
	{
		if (isset($this->images[$index])) {
			$this->_tmp_img = &$this->images[$index];
		} else {
			$this->_tmp_img = new JObject;
		}
	}

	/**
	 * Prepares the upload image screen
	 *
	 * @param $tpl
	 *
	 * @since 0.9
	 */
	function _displayupload($tpl = null)
	{
		//initialise variables
		$document	= & JFactory::getDocument();
		$uri 		= & JFactory::getURI();
		$params = & JComponentHelper::getParams('com_tracks');
    $type     = JRequest::getVar( 'type' );
    $folder = ImageSelect::getfolder($type);
    $field  = JRequest::getVar( 'field' );

		//get vars
		$task 		= JRequest::getVar( 'task' );
		
		jimport('joomla.client.helper');
		$ftp =& JClientHelper::setCredentialsFromRequest('ftp');

		//assign data to template
		$this->assignRef('params'  	, $params);
		$this->assignRef('request_url'	, $uri->toString());
		$this->assignRef('ftp'			, $ftp);
    $this->assignRef('folder'      , $folder);
    $this->assignRef('field',   $field);

		parent::display($tpl);
	}
}
?>