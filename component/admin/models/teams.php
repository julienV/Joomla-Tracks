<?php
/**
 * @version    .2 $Id$
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

class TracksModelTeams extends FOFModel
{
	/**
	 * Public class constructor
	 *
	 * @param   type  $config  The configuration array
	 */
	public function __construct($config = array())
	{
		parent::__construct($config);

		$this->setState('form_name', 'teams');
	}
}