<?php
/**
 * @package     Tracks
 * @subpackage  Install
 * @copyright   Tracks (C) 2008-2015 Julien Vonthron. All rights reserved.
 * @license     GNU General Public License version 2 or later
 */

defined('_JEXEC') or die('Restricted access');

// Find redCORE installer to use it as base system
if (!class_exists('Com_RedcoreInstallerScript'))
{
	$searchPaths = array(
		// Install
		dirname(__FILE__) . '/redCORE',
		// Discover install
		JPATH_ADMINISTRATOR . '/components/com_redcore'
	);

	if ($redcoreInstaller = JPath::find($searchPaths, 'install.php'))
	{
		require_once $redcoreInstaller;
	}
	else
	{
		exit('COM_TRACKS_INSTALLER_ERROR_REDCORE_IS_REQUIRED');
		throw new Exception(JText::_('COM_TRACKS_INSTALLER_ERROR_REDCORE_IS_REQUIRED'), 500);
	}
}

/**
 * Custom installation of Redevent.
 *
 * @package     Redevent
 * @subpackage  Install
 * @since       3.0
 */
class Com_TracksInstallerScript extends Com_RedcoreInstallerScript
{
}
