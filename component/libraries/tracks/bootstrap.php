<?php
/**
 * @package     Tracks
 * @subpackage  Library
 * @copyright   Tracks (C) 2008-2015 Julien Vonthron. All rights reserved.
 * @license     GNU General Public License version 2 or later
 */

defined('_JEXEC') or die;

$redcoreLoader = JPATH_LIBRARIES . '/redcore/bootstrap.php';

if (!file_exists($redcoreLoader) || !JPluginHelper::isEnabled('system', 'redcore'))
{
	throw new Exception(JText::_('COM_TRACKS_REDCORE_INIT_FAILED'), 404);
}

include_once $redcoreLoader;

// Bootstraps redCORE
RBootstrap::bootstrap();

// Register library prefix
RLoader::registerPrefix('Trackslib', __DIR__);

// Make available the fields
JFormHelper::addFieldPath(JPATH_LIBRARIES . '/tracks/form/field');
JFormHelper::addFieldPath(JPATH_LIBRARIES . '/tracks/form/fields');

// Make available the form rules
JFormHelper::addRulePath(JPATH_LIBRARIES . '/tracks/form/rule');
JFormHelper::addRulePath(JPATH_LIBRARIES . '/tracks/form/rules');

JTable::addIncludePath(JPATH_ADMINISTRATOR . '/components/com_tracks/tables');
Joomla\CMS\MVC\Model\BaseDatabaseModel::addIncludePath(JPATH_ADMINISTRATOR . '/components/com_tracks/models');

JLoader::register('FieldsHelper', JPATH_ADMINISTRATOR . '/components/com_fields/helpers/fields.php');

require_once __DIR__ . '/vendor/autoload.php';

// Load library language
$lang = JFactory::getLanguage();
$lang->load('lib_tracks', __DIR__);

/**
 * Tracks bootstrap class
 *
 * @package     Tracks
 * @subpackage  Library
 * @since       3.0
 */
class TrackslibBootstrap
{
	/**
	 * Effectively bootstraps Tracks
	 *
	 * @return void
	 */
	public static function bootstrap()
	{
		if (!defined('TRACKS_BOOTSTRAPPED'))
		{
			// Sets bootstrapped variable, to avoid bootstrapping rEDEVENT twice
			define('TRACKS_BOOTSTRAPPED', 1);

			// For Joomla! 2.5 compatibility we load bootstrap2
			if (version_compare(JVERSION, '3.0', '<') && JFactory::getApplication()->input->get('view') == 'config')
			{
				RHtmlMedia::setFramework('bootstrap2');
			}
		}
	}
}
