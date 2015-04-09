<?php
/**
 * @package     Tracks
 * @subpackage  Library
 * @copyright   Tracks (C) 2008-2015 Julien Vonthron. All rights reserved.
 * @license     GNU General Public License version 2 or later
 */

defined('_JEXEC') or die;

/**
 * Route helper
 *
 * @package     Tracks
 * @subpackage  Library
 * @since       3.0
 */
class TrackslibHelperRoute
{
	/**
	 * return link to rounds list
	 *
	 * @return url
	 */
	public static function getRoundsRoute()
	{
		$parts = array(
			"option" => "com_tracks",
			"view"   => "rounds"
		);

		return self::buildUrl($parts);
	}

	/**
	 * return link to teams list
	 *
	 * @return url
	 */
	public static function getTeamsRoute()
	{
		$parts = array(
			"option" => "com_tracks",
			"view"   => "teams"
		);

		return self::buildUrl($parts);
	}

	/**
	 * return link to round results
	 *
	 * @param   int  $id  project round id
	 *
	 * @return url
	 */
	public static function getRoundResultRoute($id = 0)
	{
		$parts = array(
			"option" => "com_tracks",
			"view"   => "roundresult"
		);

		if ($id)
		{
			$parts['id'] = $id;
		}

		return self::buildUrl($parts);
	}

	/**
	 * return link to round
	 *
	 * @param   int  $id  round id
	 *
	 * @return url
	 */
	public static function getRoundRoute($id = 0)
	{
		$parts = array(
			"option" => "com_tracks",
			"view"   => "round"
		);

		if ($id)
		{
			$parts['id'] = $id;
		}

		return self::buildUrl($parts);
	}


	/**
	 * return link to individual
	 *
	 * @param   int  $id       ind id
	 * @param   int  $project  project id
	 *
	 * @return url
	 */
	public static function getIndividualRoute($id = 0, $project = 0)
	{
		$parts = array(
			"option" => "com_tracks",
			"view"   => "individual"
		);

		if ($id)
		{
			$parts['id'] = $id;
		}

		if ($project)
		{
			$parts['p'] = $project;
		}

		return self::buildUrl($parts);
	}

	/**
	 * return link to edit individual
	 *
	 * @param   int  $id  ind id
	 *
	 * @return url
	 */
	public static function getEditIndividualRoute($id = 0)
	{
		$parts = array(
			"option" => "com_tracks",
			"view"   => "individual"
		);

		if ($id)
		{
			$parts['task'] = 'individual.edit';
			$parts['id'] = $id;
		}
		else
		{
			$parts['task'] = 'individual.add';
		}

		return self::buildUrl($parts);
	}

	/**
	 * return link to individuals
	 *
	 * @param   int  $id       ind id
	 * @param   int  $project  project id
	 *
	 * @return url
	 */
	public static function getIndividualsRoute($id = 0, $project = 0)
	{
		$parts = array( "option" => "com_tracks",
			"view"   => "individuals" );

		return self::buildUrl($parts);
	}

	/**
	 * return link to project
	 *
	 * @param   int  $id  project id
	 *
	 * @return url
	 */
	public static function getProjectRoute($id)
	{
		$parts = array(
			"option" => "com_tracks",
			"view"   => "project"
		);

		if ($id)
		{
			$parts['p'] = $id;
		}

		return self::buildUrl($parts);
	}

	/**
	 * return link to team
	 *
	 * @param   int  $id       team id
	 * @param   int  $project  project id
	 *
	 * @return url
	 */
	public static function getTeamRoute($id = 0, $project = 0)
	{
		$parts = array(
			"option" => "com_tracks",
			"view"   => "team"
		);

		if ($id)
		{
			$parts['id'] = $id;
		}

		if ($project)
		{
			$parts['p'] = $project;
		}

		return self::buildUrl($parts);
	}

	/**
	 * Get edit team route
	 *
	 * @param   int  $id  team id
	 *
	 * @return string
	 */
	public static function getTeamEditRoute($id = 0)
	{
		$parts = array(
			"option" => "com_tracks",
			"view"   => "team",
			"task" => "team.edit"
		);

		$parts['id'] = $id;

		return self::buildUrl($parts);
	}

	/**
	 * return link to ranking
	 *
	 * @param   int  $id  project id
	 *
	 * @return url
	 */
	public static function getRankingRoute($id)
	{
		$parts = array(
			"option" => "com_tracks",
			"view"   => "ranking"
		);

		if ($id)
		{
			$parts['p'] = $id;
		}

		return self::buildUrl($parts);
	}

	/**
	 * return link to results
	 *
	 * @param   int  $id  project id
	 *
	 * @return url
	 */
	public static function getProjectResultRoute($id)
	{
		$parts = array(
			"option" => "com_tracks",
			"view"   => "projectresults"
		);

		if ($id)
		{
			$parts['p'] = $id;
		}

		return self::buildUrl($parts);
	}

	/**
	 * return link to participants
	 *
	 * @param   int  $id  project id
	 *
	 * @return url
	 */
	public static function getParticipantsRoute($id)
	{
		$parts = array(
			"option" => "com_tracks",
			"view"   => "projectresults",
			"p" => $id
		);

		return self::buildUrl($parts);
	}

	/**
	 * return link to team ranking
	 *
	 * @param   int  $id  project id
	 *
	 * @return url
	 */
	public static function getTeamRankingRoute($id)
	{
		$parts = array(
			"option" => "com_tracks",
			"view"   => "teamranking"
		);

		if ($id)
		{
			$parts['p'] = $id;
		}

		return self::buildUrl($parts);
	}

	/**
	 * build url
	 *
	 * @param   array  $parts  parts
	 *
	 * @return string
	 */
	protected static function buildUrl($parts)
	{
		if ($item = self::_findItem($parts))
		{
			$parts['Itemid'] = $item->id;
		}

		return 'index.php?' . JURI::buildQuery($parts);
	}

	/**
	 * Determines the Itemid
	 *
	 * searches if a menuitem for this item exists
	 * if not the first match will be returned
	 *
	 * @param   array  $query  url parameters
	 *
	 * @return int Itemid
	 */
	protected static function _findItem($query)
	{
		$component = JComponentHelper::getComponent('com_tracks');
		$menus	= JSite::getMenu();
		$items	= $menus->getItems('component_id', $component->id);
		$user 	= JFactory::getUser();

		if ($items)
		{
			foreach ($items as $item)
			{
				if ((@$item->query['view'] == $query['view']))
				{
					switch ($query['view'])
					{
						case 'individual':
							if ((int) @$item->query['id'] == (int) @$query['id'])
							{
								return $item;
							}

							break;

						case 'ranking':
						case 'teamranking':
							if ((int) @$item->query['p'] == (int) @$query['p'])
							{
								return $item;
							}

							break;

						case 'round':
							if ((int) @$item->query['id'] == (int) @$query['id'])
							{
								return $item;
							}

							break;

						case 'roundresult':
							if ((int) @$item->query['id'] == (int) @$query['id'])
							{
								return $item;
							}

							break;

						case 'team':
							if ((int) @$item->query['id'] == (int) @$query['id'])
							{
								return $item;
							}

							break;

						default:
							if (!isset($query['id']) || (int) @$item->query['id'] == (int) @$query['id'])
							{
								return $item;
							}
					}
				}
			}
		}

		return false;
	}
}
