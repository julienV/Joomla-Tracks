<?php
/**
 * @package     Tracks
 * @subpackage  Admin
 * @copyright   Tracks (C) 2008-2015 Julien Vonthron. All rights reserved.
 * @license     GNU General Public License version 2 or later
 */

// No direct access
defined('_JEXEC') or die('Restricted access');

$redcoreLoader = JPATH_LIBRARIES . '/redcore/bootstrap.php';

if (!file_exists($redcoreLoader) || !JPluginHelper::isEnabled('system', 'redcore'))
{
	throw new Exception(JText::_('COM_REDEVENT_REDCORE_REQUIRED'), 404);
}

include_once $redcoreLoader;

// Bootstraps redCORE
RBootstrap::bootstrap();

// Register libraray
RLoader::registerPrefix('Trackslib', JPATH_LIBRARIES . '/tracks');

// Register backend prefix
RLoader::registerPrefix('Tracks', __DIR__);

$app = JFactory::getApplication();

// Instantiate and execute the front controller.
$controller = JControllerLegacy::getInstance('Tracks');

// Check access.
if (!JFactory::getUser()->authorise('core.manage', 'com_tracks'))
{
	$app->enqueueMessage(JText::_('JERROR_ALERTNOAUTHOR'), 'error');

	return false;
}

try
{
	$controller->execute($app->input->get('task'));
	$controller->redirect();
}
catch (Exception $e)
{
	if (JDEBUG || 1)
	{
		echo 'Exception:' . $e->getMessage();
		echo "<pre>" . $e->getTraceAsString() . "</pre>";
		exit(0);
	}

	throw $e;
}
