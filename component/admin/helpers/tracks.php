<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_contact
 *
 * @copyright   Copyright (C) 2005 - 2020 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

use Joomla\CMS\Plugin\PluginHelper;

defined('_JEXEC') or die;

/**
 * Tracks component helper.
 */
class TracksHelper extends JHelperContent
{
	/**
	 * Configure the Linkbar.
	 *
	 * @param   string  $vName  The name of the active view.
	 *
	 * @return  void
	 *
	 * @since   1.6
	 */
	public static function addSubmenu($vName)
	{
		if (JComponentHelper::isEnabled('com_fields') && PluginHelper::isEnabled('tracks', 'customfields'))
		{
			PluginHelper::importPlugin('tracks');
			JEventDispatcher::getInstance()->trigger('onTracksAddCustomFieldsEntries', [$vName]);
		}
	}

	/**
	 * Returns valid contexts
	 *
	 * @return  array
	 *
	 * @since   3.7.0
	 */
	public static function getContexts()
	{
		$contexts = [];

		if (JComponentHelper::isEnabled('com_fields') && PluginHelper::isEnabled('tracks', 'customfields'))
		{
			PluginHelper::importPlugin('tracks');
			JEventDispatcher::getInstance()->trigger('onTracksCustomFieldsGetContext', [&$contexts]);
		}

		return $contexts;
	}
}
