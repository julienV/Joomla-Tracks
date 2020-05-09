<?php
/**
 * @package    Tracks.Library
 * @copyright  Tracks (C) 2008-2020 Julien Vonthron. All rights reserved.
 * @license    GNU General Public License version 2 or later
 */

namespace Tracks\Helper;

use Joomla\CMS\Factory;
use Joomla\CMS\Plugin\PluginHelper;

defined('_JEXEC') or die('Restricted access');

/**
 * Tracks config helper
 *
 * @package  Tracks.Library
 * @since    3.0.12
 */
class Config
{
	/**
	 * Pulls settings from database and stores in an static object
	 *
	 * @return object
	 * @throws \Exception
	 */
	public static function getConfig()
	{
		static $config;

		if (empty($config))
		{
			$app    = Factory::getApplication();
			$config = \JComponentHelper::getParams('com_tracks');

			// See if there are any plugins that wish to alter the configuration
			PluginHelper::importPlugin('tracks_config');
			$app->triggerEvent('onGetTracksConfig', array(&$config));
		}

		return $config;
	}

	/**
	 * Return a config value
	 *
	 * @param   string  $key      config key
	 * @param   mixed   $default  default value
	 *
	 * @return mixed
	 */
	public static function get($key, $default = null)
	{
		$config = self::getConfig();

		return $config->get($key, $default);
	}
}
