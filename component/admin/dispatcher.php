<?php
/**
 * @package   JoomlaTracks
 * @copyright Copyright (C) 2008-2013 Julien Vonthron. All rights reserved.
 * @license   GNU/GPL, see LICENSE.php
 * Joomla Tracks is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * See COPYRIGHT.php for copyright notices and details.
 */

defined('_JEXEC') or die();

/**
 * Class TracksDispatcher
 *
 */
class TracksDispatcher extends FOFDispatcher
{
	public $defaultView = 'projects';

	public function onBeforeDispatch()
	{
		// Load the base classes
		require_once JPATH_COMPONENT_ADMINISTRATOR.'/views/view.html.php';
		require_once JPATH_COMPONENT_ADMINISTRATOR.'/views/menuview.html.php';

		return true;
	}
}
