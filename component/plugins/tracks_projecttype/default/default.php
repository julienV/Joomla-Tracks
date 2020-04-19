<?php
/**
 * @package     Tracks.Plugin
 * @subpackage  Tracks.projecttype
 *
 * @copyright   Copyright (C) 2008-2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 3 or later, see LICENSE.
 */

defined('JPATH_BASE') or die;

// Import library dependencies
jimport('joomla.plugin.plugin');

jimport('tracks.bootstrap');

/**
 * redFORM custom acymailing lists from redEVENT event
 *
 * @since  __deploy_version__
 */
class PlgTracks_ProjecttypeDefault extends Tracks\Plugin\Projecttype
{
	/**
	 * The plugin identifier.
	 *
	 * @var    string
	 * @since  __deploy_version__
	 */
	protected $context = 'default';

	/**
	 * Get available project types
	 *
	 * @param   array  $array  project types
	 *
	 * @return boolean true on success
	 */
	public function onTracksGetProjectTypes(&$array)
	{
		$array[] = array('name' => $this->context, 'label' => JText::_('PLG_TRACKS_PROJECTTYPE_DEFAULT_TYPE_LABEL'));

		return true;
	}
}
