<?php
/**
 * @package    Tracks.Site
 * @copyright  Tracks (C) 2008-2015 Julien Vonthron. All rights reserved.
 * @license    GNU General Public License version 2 or later
 */

// No direct access
defined('_JEXEC') or die('Restricted access');

$tracksLoader = JPATH_LIBRARIES . '/tracks/bootstrap.php';

if (!file_exists($tracksLoader))
{
	throw new Exception(JText::_('COM_TRACKS_LIB_INIT_FAILED'), 404);
}

include_once $tracksLoader;

// Bootstraps Tracks
TrackslibBootstrap::bootstrap();

// Register frontend prefix
RLoader::registerPrefix('Tracks', __DIR__);

$app = JFactory::getApplication();

// Instantiate and execute the front controller.
$controller = JControllerLegacy::getInstance('Tracks');

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
