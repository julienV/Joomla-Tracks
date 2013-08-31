<?php
/**
* @version    $Id: individual.php 140 2008-06-10 16:47:22Z julienv $
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

/**
 * Joomla Tracks Component Controller
 *
 * @package  Tracks
 * @since    0.1
 */
class TracksControllerIndividuals extends FOFController
{
	/**
	 * individual element
	 */
	function element()
	{
		$model  = $this->getModel( 'individuals' );
		$view = $this->getView( 'individuals', 'html' );
		$view->setLayout( 'element' );
		$view->setModel( $model, true );
		$view->display();
	}
}
