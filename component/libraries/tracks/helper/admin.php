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
abstract class TrackslibHelperAdmin
{
	/**
	 * Return items for building menus
	 *
	 * @param   bool  $getCurrent  get current
	 *
	 * @return array
	 */
	public static function getAdminMenuItems($getCurrent = false)
	{
		$return = base64_encode('index.php?option=com_tracks');

		$items = array(
			array(
				'id'   => 'structure',
				'icon' => 'icon-plus',
				'text' => JText::_('COM_TRACKS_STRUCTURE'),
				'items' => array(
					array(
						'view' => 'projects',
						'link' => 'index.php?option=com_tracks&view=projects',
						'icon' => 'icon-flag-checkered',
						'text' => JText::_('COM_TRACKS_PROJECTS'),
						'stats' => static::getProjectStats(),
						'access' => 'core.edit'
					),
					array(
						'view' => 'competitions',
						'link' => 'index.php?option=com_tracks&view=competitions',
						'icon' => 'icon-trophy',
						'text' => JText::_('COM_TRACKS_COMPETITIONS'),
						'stats' => static::getCompetitionStats(),
						'access' => 'core.edit'
					),
					array(
						'view' => 'seasons',
						'link' => 'index.php?option=com_tracks&view=seasons',
						'icon' => 'icon-calendar',
						'text' => JText::_('COM_TRACKS_SEASONS'),
						'stats' => static::getSeasonStats(),
						'access' => 'core.edit'
					),
					array(
						'view' => 'rounds',
						'link' => 'index.php?option=com_tracks&view=rounds',
						'icon' => 'icon-map-marker',
						'text' => JText::_('COM_TRACKS_ROUNDS'),
						'stats' => static::getRoundStats(),
						'access' => 'core.edit'
					),
					array(
						'view' => 'eventtypes',
						'link' => 'index.php?option=com_tracks&view=eventtypes',
						'icon' => 'icon-sort-by-attributes',
						'text' => JText::_('COM_TRACKS_EVENTTYPES'),
						'stats' => static::getEventtypeStats(),
						'access' => 'core.edit'
					),
				)
			),
			array(
				'id'   => 'participants',
				'icon' => 'icon-group',
				'text' => JText::_('COM_TRACKS_PARTICIPANTS'),
				'items' => array(
					array(
						'view' => 'individuals',
						'link' => 'index.php?option=com_tracks&view=individuals',
						'icon' => 'icon-user',
						'text' => JText::_('COM_TRACKS_INDIVIDUALS'),
						'stats' => static::getIndividualStats(),
						'access' => 'core.edit'
					),
					array(
						'view' => 'teams',
						'link' => 'index.php?option=com_tracks&view=teams',
						'icon' => 'icon-group',
						'text' => JText::_('COM_TRACKS_TEAMS'),
						'stats' => static::getTeamsStats(),
						'access' => 'core.edit'
					),
				)
			),
			array(
				'id'   => 'more',
				'icon' => 'icon-gears',
				'text' => JText::_('COM_TRACKS_MORE'),
				'items' => array(
					array(
						'view' => 'import',
						'link' => 'index.php?option=com_tracks&view=import',
						'icon' => 'icon-cloud-download',
						'text' => JText::_('COM_TRACKS_MENU_IMPORT'),
						'access' => 'core.edit'
					),
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

		if ($getCurrent && $currentproject = JFactory::getApplication()->getUserState('currentproject'))
		{
			$project = TrackslibEntityProject::load($currentproject);

			$projectMenu = array(
				array(
					'id'   => 'current',
					'icon' => 'icon-hand-right',
					'text' => $project->name,
					'items' => array(
						array(
							'view' => 'projectrounds',
							'link' => 'index.php?option=com_tracks&view=projectrounds',
							'icon' => 'icon-calendar-empty',
							'text' => JText::_('COM_TRACKS_PROJECT_ROUNDS'),
							'access' => 'core.edit'
						),
						array(
							'view' => 'participants',
							'link' => 'index.php?option=com_tracks&view=participants',
							'icon' => 'icon-user',
							'text' => JText::_('COM_TRACKS_PARTICIPANTS'),
							'access' => 'core.edit'
						),
					)
				)
			);

			$items = array_merge($projectMenu, $items);
		}

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
	public static function getProjectStats()
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
	public static function getCompetitionStats()
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
	public static function getSeasonStats()
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
	public static function getRoundStats()
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
	public static function getEventtypeStats()
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
	public static function getIndividualStats()
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
	public static function getTeamsStats()
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
