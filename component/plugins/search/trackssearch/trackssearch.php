<?php
/**
 * @package     JoomlaTracks
 * @subpackage  Plugins.site
 * @copyright   Copyright (C) 2008-2015 Julien Vonthron. All rights reserved.
 * @license     GNU General Public License version 2 or later
 */

defined('_JEXEC') or die('Restricted access');

$tracksLoader = JPATH_LIBRARIES . '/tracks/bootstrap.php';

if (!file_exists($tracksLoader))
{
	throw new Exception(JText::_('COM_TRACKS_LIB_INIT_FAILED'), 404);
}

include_once $tracksLoader;

// Bootstraps Tracks
TrackslibBootstrap::bootstrap();

jimport('joomla.plugin.plugin');

/**
 * Tracks Search plugin
 *
 * @package     JoomlaTracks
 * @subpackage  Plugins.site
 * @since       1.0
 */
class PlgSearchTrackssearch extends JPlugin
{
	/**
	 * Constructor
	 *
	 * @param   object  &$subject  The object to observe
	 * @param   array   $config    An array that holds the plugin configuration
	 */
	public function __construct(&$subject, $config)
	{
		parent::__construct($subject, $config);
		$this->loadLanguage();
	}

	/**
	 * Return search areas
	 *
	 * @return array An array of search areas
	 */
	public function onContentSearchAreas()
	{
		static $areas = array(
			'tracksindividuals' => 'PLG_SEARCH_TRACKS_INDIVIDUALS',
			'tracksteams' => 'PLG_SEARCH_TRACKS_TEAMS',
			'tracksprojects' => 'PLG_SEARCH_TRACKS_PROJECTS',
			'tracksrounds' => 'PLG_SEARCH_TRACKS_ROUNDS',
		);

		return $areas;
	}

	/**
	 * Tracks Search method
	 *
	 * The sql must return the following fields that are used in a common display
	 * routine: href, title, section, created, text, browsernav
	 *
	 * @param   string  $text      Target search string
	 * @param   string  $phrase    matching option, exact|any|all
	 * @param   string  $ordering  ordering option, newest|oldest|popular|alpha|category
	 * @param   mixed   $areas     An array if the search it to be restricted to areas, null if search all
	 *
	 * @return array
	 */
	public function onContentSearch($text, $phrase = '', $ordering = '', $areas = null)
	{
		$db = JFactory::getDbo();
		$app = JFactory::getApplication();
		$user = JFactory::getUser();
		$groups = implode(',', $user->getAuthorisedViewLevels());

		if (is_array($areas))
		{
			if (!array_intersect($areas, array_keys($this->onContentSearchAreas())))
			{
				return array();
			}
		}

		$limit = $this->params->def('search_limit', 50);

		$text = trim($text);

		if (!$text)
		{
			return array();
		}

		$section = JText::_('PLG_SEARCH_TRACKS_TRACKS');

		$rows = array();

		if (!$areas || in_array('tracksindividuals', $areas))
		{
			$query = $db->getQuery(true);

			switch ($phrase)
			{
				// Search exact
				case 'exact':
					$string = $db->Quote('%' . $db->escape($text, true) . '%', false);
					$wheres2 = array();
					$wheres2[] = 'LOWER(i.first_name) LIKE ' . $string;
					$wheres2[] = 'LOWER(i.last_name) LIKE ' . $string;
					$wheres2[] = 'LOWER(i.nickname) LIKE ' . $string;
					$query->where('(' . implode(' OR ', $wheres2) . ')');
					break;

				// Search all or any
				case 'all':
				case 'any':
				default:
					$words = explode(' ', $text);
					$wheres = array();

					foreach ($words as $word)
					{
						$word = $db->Quote('%' . $db->escape($word, true) . '%', false);
						$wheres2 = array();
						$wheres2[] = 'LOWER(i.first_name) LIKE ' . $word;
						$wheres2[] = 'LOWER(i.last_name) LIKE ' . $word;
						$wheres2[] = 'LOWER(i.nickname) LIKE ' . $word;
						$wheres[] = implode(' OR ', $wheres2);
					}

					$query->where('(' . implode(($phrase == 'all' ? ') AND (' : ') OR ('), $wheres) . ')');
					break;
			}

			$query->order('i.last_name ASC, i.first_name ASC');

			$query->select('CONCAT_WS(", ", i.last_name, i.first_name) AS title')
				->select('CONCAT_WS( " / ", ' . $db->Quote($section) . ', ' . $db->Quote(JText::_('PLG_SEARCH_TRACKS_INDIVIDUALS')) . ' ) AS section')
				->select('CASE WHEN CHAR_LENGTH( i.alias ) THEN CONCAT_WS( \':\', i.id, i.alias ) ELSE i.id END AS slug')
				->select('NULL AS created')
				->select('2 AS browsernav')
				->from('#__tracks_individuals AS i');

			$db->setQuery($query, 0, $limit);
			$results = $db->loadObjectList();

			// The 'output' of the displayed link
			foreach ($results as $key => $row)
			{
				$results[$key]->href = TrackslibHelperRoute::getIndividualRoute($row->slug);
			}

			$rows = array_merge($rows, $results);
		}

		if (!$areas || in_array('tracksteams', $areas))
		{
			$query = $db->getQuery(true);

			switch ($phrase)
			{
				case 'exact':
					$string = $db->Quote('%' . $db->escape($text, true) . '%', false);
					$query->where('LOWER(t.name) LIKE ' . $string);
					break;

				case 'all':
				case 'any':
				default:
					$words = explode(' ', $text);
					$wheres = array();

					foreach ($words as $word)
					{
						$word = $db->Quote('%' . $db->escape($word, true) . '%', false);
						$wheres[] = 'LOWER(t.name) LIKE ' . $word;
					}

					$query->where('(' . implode(($phrase == 'all' ? ') AND (' : ') OR ('), $wheres) . ')');
					break;
			}

			switch ($ordering)
			{
				case 'alpha':
					$query->order('t.name ASC');
					break;

				case 'oldest':
				case 'popular':
				case 'newest':
				default:
				$query->order('t.name ASC');
			}

			$query->select('t.name AS title')
				->select('CONCAT_WS( " / ", ' . $db->Quote($section) . ', ' . $db->Quote(JText::_('PLG_SEARCH_TRACKS_TEAMS')) . ' ) AS section')
				->select('CASE WHEN CHAR_LENGTH( t.alias ) THEN CONCAT_WS( \':\', t.id, t.alias ) ELSE t.id END AS slug')
				->select('NULL AS created')
				->select('"2" AS browsernav')
				->from('#__tracks_teams AS t');

			$db->setQuery($query, 0, $limit);
			$results = $db->loadObjectList();

			foreach ($results as $key => $row)
			{
				$results[$key]->href = TrackslibHelperRoute::getTeamRoute($row->slug);
			}

			$rows = array_merge($rows, $results);
		}

		if (!$areas || in_array('tracksprojects', $areas))
		{
			$query = $db->getQuery(true);

			switch ($phrase)
			{
				case 'exact':
					$string = $db->Quote('%' . $db->escape($text, true) . '%', false);
					$query->where('LOWER(p.name) LIKE ' . $string);
					break;

				case 'all':
				case 'any':
				default:
					$words = explode(' ', $text);
					$wheres = array();

					foreach ($words as $word)
					{
						$word = $db->Quote('%' . $db->escape($word, true) . '%', false);
						$wheres[] = 'LOWER(p.name) LIKE ' . $word;
					}

					$query->where('(' . implode(($phrase == 'all' ? ') AND (' : ') OR ('), $wheres) . ')');
					break;
			}

			switch ($ordering)
			{
				case 'alpha':
					$query->order('p.name ASC');
					break;

				case 'oldest':
				case 'popular':
				case 'newest':
				default:
					$query->order('p.name ASC');
			}

			$query->select('p.name AS title')
				->select('CONCAT_WS( " / ", ' . $db->Quote($section) . ', ' . $db->Quote(JText::_('PLG_SEARCH_TRACKS_PROJECTS')) . ' ) AS section')
				->select('CASE WHEN CHAR_LENGTH( p.alias ) THEN CONCAT_WS( \':\', p.id, p.alias ) ELSE p.id END AS slug')
				->select('NULL AS created')
				->select(' "2" AS browsernav')
				->from('#__tracks_projects AS p');

			$db->setQuery($query, 0, $limit);
			$results = $db->loadObjectList();

			foreach ($results as $key => $row)
			{
				$results[$key]->href = TrackslibHelperRoute::getProjectRoute($row->slug);
			}

			$rows = array_merge($rows, $results);
		}

		if (!$areas || in_array('tracksrounds', $areas))
		{
			$query = $db->getQuery(true);

			switch ($phrase)
			{
				case 'exact':
					$string = $db->Quote('%' . $db->escape($text, true) . '%', false);
					$query->where('LOWER(r.name) LIKE ' . $string);
					break;

				case 'all':
				case 'any':
				default:
					$words = explode(' ', $text);
					$wheres = array();

					foreach ($words as $word)
					{
						$word = $db->Quote('%' . $db->escape($word, true) . '%', false);
						$wheres[] = 'LOWER(r.name) LIKE ' . $word;
					}

					$query->where('(' . implode(($phrase == 'all' ? ') AND (' : ') OR ('), $wheres) . ')');
					break;
			}

			switch ($ordering)
			{
				case 'alpha':
					$query->order('r.name ASC');
					break;

				case 'oldest':
				case 'popular':
				case 'newest':
				default:
				$query->order('r.name ASC');
			}

			$query->select('r.name AS title')
				->select('CONCAT_WS( " / ", ' . $db->Quote($section) . ', ' . $db->Quote(JText::_('PLG_SEARCH_TRACKS_ROUNDS')) . ' ) AS section')
				->select('CASE WHEN CHAR_LENGTH( r.alias ) THEN CONCAT_WS( \':\', r.id, r.alias ) ELSE r.id END AS slug')
				->select('NULL AS created')
				->select('"2" AS browsernav')
				->from('#__tracks_rounds AS r');

			$db->setQuery($query, 0, $limit);
			$results = $db->loadObjectList();

			foreach ($results as $key => $row)
			{
				$results[$key]->href = TrackslibHelperRoute::getRoundRoute($row->slug);
			}

			$rows = array_merge($rows, $results);
		}

		return $rows;
	}
}
