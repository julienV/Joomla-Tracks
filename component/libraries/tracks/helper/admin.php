<?php
/**
 * @package     Tracks
 * @subpackage  Library
 * @copyright   Tracks (C) 2008-2015 Julien Vonthron. All rights reserved.
 * @license     GNU General Public License version 2 or later
 */

defined('_JEXEC') or die('Restricted access');

/**
 * Helper for admin
 *
 * @package  Tracks.Library
 * @since    3.0.5
 */
abstract class TracksHelperAdmin
{
	/**
	 * Return items for building menus
	 *
	 * @return array
	 */
	public static function getAdminMenuItems()
	{
		$return = base64_encode('index.php?option=com_tracks');
		$configurationLink = 'index.php?option=com_redcore&view=config&layout=edit&component=com_tracks&return=' . $return;

		$items = array(
			'structure' => array(
				'icon' => 'icon-flag-checkered',
				'text' => JText::_('COM_TRACKS_CPANEL_TITLE_STRUCTURE'),
				'items' => array(
					array(
						'view' => 'projects',
						'link' => 'index.php?option=com_tracks&view=events',
						'icon' => 'icon-flag-checkered',
						'text' => JText::_('COM_TRACKS_PROJECTS'),
						'count' => static::getProjectCount(),
						'access' => 'core.edit'
					),
					array(
						'view' => 'competitions',
						'link' => 'index.php?option=com_tracks&view=competitions',
						'icon' => 'icon-trophy',
						'text' => JText::_('COM_TRACKS_COMPETITIONS'),
						'count' => static::getCompetitionCount(),
						'access' => 'core.edit'
					),
					array(
						'view' => 'seasons',
						'link' => 'index.php?option=com_tracks&view=seasons',
						'icon' => 'icon-calendar',
						'text' => JText::_('COM_TRACKS_SEASONS'),
						'count' => static::getSeasonCount(),
						'access' => 'core.edit'
					),
					array(
						'view' => 'rounds',
						'link' => 'index.php?option=com_tracks&view=rounds',
						'icon' => 'icon-map-marker',
						'text' => JText::_('COM_TRACKS_ROUNDS'),
						'count' => static::getRoundCount(),
						'access' => 'core.edit'
					),
					array(
						'view' => 'eventtypes',
						'link' => 'index.php?option=com_tracks&view=eventtypes',
						'icon' => 'icon-calendar-plus-o',
						'text' => JText::_('COM_TRACKS_EVENTTYPES'),
						'count' => static::getEventtypeCount(),
						'access' => 'core.edit'
					),
				)
			),
			'participants' => array(
				'icon' => 'icon-group',
				'text' => JText::_('COM_TRACKS_PARTICIPANTS'),
				'items' => array(
					array(
						'view' => 'individuals',
						'link' => 'index.php?option=com_tracks&view=individuals',
						'icon' => 'icon-user',
						'text' => JText::_('COM_TRACKS_INDIVIDUALS'),
						'count' => static::getIndividualCount(),
						'access' => 'core.edit'
					),
					array(
						'view' => 'teams',
						'link' => 'index.php?option=com_tracks&view=teams',
						'icon' => 'icon-group',
						'text' => JText::_('COM_TRACKS_TEAMS'),
						'count' => static::getTeamsCount(),
						'access' => 'core.edit'
					),
				)
			),
			'more' => array(
				'icon' => 'icon-gears',
				'text' => JText::_('COM_TRACKS_MORE'),
				'items' => array(
					array(
						'view' => 'about',
						'link' => 'index.php?option=com_tracks&view=about',
						'icon' => 'icon-question',
						'text' => JText::_('COM_TRACKS_ABOUT'),
						'access' => 'core.edit'
					),
					array(
						'view' => 'config',
						'link' => 'index.php?option=com_redcore&view=config&layout=edit&component=com_tracks&return=' . $return,
						'icon' => 'icon-gears',
						'text' => JText::_('COM_TRACKS_SETTINGS'),
						'access' => 'core.manage'
					),
				)
			),
		);

		JPluginHelper::importPlugin('tracks');
		$dispatcher = RFactory::getDispatcher();
		$dispatcher->trigger('onGetTracksAdminMenuItems', array(&$items));

		return $items;
	}

	/**
	 * Get project stats
	 *
	 * @return array
	 */
	public static function getProjectCount()
	{
		$db = JFactory::getDbo();

		$query = $db->getQuery(true)
			->select('COUNT(*) AS total')
			->from('#__tracks_projects');

		$db->setQuery($query);

		return $db->loadAssoc();
	}

	/**
	 * Get Competition stats
	 *
	 * @return array
	 */
	public static function getCompetitionCount()
	{
		$db = JFactory::getDbo();

		$query = $db->getQuery(true)
			->select('COUNT(*) AS total')
			->from('#__tracks_competitions');

		$db->setQuery($query);

		return $db->loadAssoc();
	}

	/**
	 * Get Season stats
	 *
	 * @return array
	 */
	public static function getSeasonCount()
	{
		$db = JFactory::getDbo();

		$query = $db->getQuery(true)
			->select('COUNT(*) AS total')
			->from('#__tracks_seasons');

		$db->setQuery($query);

		return $db->loadAssoc();
	}

	/**
	 * Get Round stats
	 *
	 * @return array
	 */
	public static function getRoundCount()
	{
		$db = JFactory::getDbo();

		$query = $db->getQuery(true)
			->select('COUNT(*) AS total')
			->from('#__tracks_rounds');

		$db->setQuery($query);

		return $db->loadAssoc();
	}

	/**
	 * Get Eventtype stats
	 *
	 * @return array
	 */
	public static function getEventtypeCount()
	{
		$db = JFactory::getDbo();

		$query = $db->getQuery(true)
			->select('COUNT(*) AS total')
			->from('#__tracks_eventtypes');

		$db->setQuery($query);

		return $db->loadAssoc();
	}

	/**
	 * Get Individual stats
	 *
	 * @return array
	 */
	public static function getIndividualCount()
	{
		$db = JFactory::getDbo();

		$query = $db->getQuery(true)
			->select('COUNT(*) AS total')
			->from('#__tracks_individuals');

		$db->setQuery($query);

		return $db->loadAssoc();
	}

	/**
	 * Get Teams stats
	 *
	 * @return array
	 */
	public static function getTeamsCount()
	{
		$db = JFactory::getDbo();

		$query = $db->getQuery(true)
			->select('COUNT(*) AS total')
			->from('#__tracks_teams');

		$db->setQuery($query);

		return $db->loadAssoc();
	}

	/**
	 * Get the current version
	 *
	 * @return  string  The version
	 *
	 * @since   3.0
	 */
	public static function getVersion()
	{
		$xmlfile = JPATH_SITE . '/administrator/components/com_tracks/tracks.xml';

		if (file_exists($xmlfile))
		{
			$data = JApplicationHelper::parseXMLInstallFile($xmlfile);
			$version = $data['version'];
		}

		return $version ?: '?';
	}
}
