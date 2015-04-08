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
	 * return link to details view of specified event
	 * @param int $id
	 * @param int $xref
	 * @return url
	 */
	public static function getRoundResultRoute($id = 0)
	{
		$parts = array( "option" => "com_tracks",
		                "view"   => "roundresult" );
		if ($id) {
			$parts['id'] = $id;
		}
		return self::buildUrl( $parts );
	}

	/**
	 * return link to details view of specified event
	 * @param int $id
	 * @param int $xref
	 * @return url
	 */
	public static function getRoundRoute($id = 0)
	{
		$parts = array( "option" => "com_tracks",
		                "view"   => "round" );
		if ($id) {
			$parts['id'] = $id;
		}
		return self::buildUrl( $parts );
	}

	public static function getIndividualRoute($id = 0, $project = 0)
	{
		$parts = array( "option" => "com_tracks",
		                "view"   => "individual" );
		if ($id) {
			$parts['id'] = $id;
		}
		if ($project) {
			$parts['p'] = $project;
		}
		return self::buildUrl( $parts );
	}

	public static function getEditIndividualRoute($id = 0)
	{
		$parts = array( "option" => "com_tracks",
		                "view"   => "individualedit",
		                "task" => "edit" );
		if ($id) {
			$parts['id'] = $id;
		}
		return self::buildUrl( $parts );
	}

	public static function getProjectRoute($id = 0)
	{
		$parts = array( "option" => "com_tracks",
		                "view"   => "project" );
		if ($id) {
			$parts['id'] = $id;
		}
		return self::buildUrl( $parts );
	}

	public static function getTeamRoute($id = 0, $project = 0)
	{
		$parts = array( "option" => "com_tracks",
		                "view"   => "team" );
		if ($id) {
			$parts['id'] = $id;
		}
		if ($project) {
			$parts['p'] = $project;
		}
		return self::buildUrl( $parts );
	}

	/**
	 * Get edit team route
	 *
	 * @param   int  $id      team id
	 * @param   int $project  the project id, if being edited from a project
	 *
	 * @return string
	 */
	public static function getTeamEditRoute($id = 0, $project = 0)
	{
		$parts = array( "option" => "com_tracks",
			"view"   => "teamedit",
			"task" => "edit");

		$parts['id'] = $id;

		if ($project)
		{
			$parts['project_id'] = $project;
		}

		return self::buildUrl( $parts );
	}

	public static function getRankingRoute($id = 0)
	{
		$parts = array( "option" => "com_tracks",
		                "view"   => "ranking" );
		if ($id) {
			$parts['id'] = $id;
		}
		return self::buildUrl( $parts );
	}

	public static function getProjectResultRoute($id = 0)
	{
		$parts = array( "option" => "com_tracks",
		                "view"   => "projectresults" );
		if ($id) {
			$parts['id'] = $id;
		}
		return self::buildUrl( $parts );
	}

	public static function getTeamRankingRoute($id = 0)
	{
		$parts = array( "option" => "com_tracks",
		                "view"   => "teamranking" );
		if ($id) {
			$parts['id'] = $id;
		}
		return self::buildUrl( $parts );
	}

	public static function buildUrl($parts)
	{
		if($item = self::_findItem($parts)) {
			$parts['Itemid'] = $item->id;
		};

		return 'index.php?'.JURI::buildQuery( $parts );
	}

	/**
	 * Determines the Itemid
	 *
	 * searches if a menuitem for this item exists
	 * if not the first match will be returned
	 *
	 * @param array url parameters
	 * @since 0.9
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
			foreach($items as $item)
			{
				if ((@$item->query['view'] == $query['view']))
				{
					switch ($query['view'])
					{
						case 'individual':
							if ((int) @$item->query['id'] == (int) @$query['id']) {
								return $item;
							}
							break;

						case 'ranking':
						case 'teamranking':
							if ((int) @$item->query['id'] == (int) @$query['id']) {
								return $item;
							}
							break;

						case 'round':
							if ((int) @$item->query['id'] == (int) @$query['id']) {
								return $item;
							}
						  break;

						case 'roundresult':
							if ((int) @$item->query['id'] == (int) @$query['id']) {
								return $item;
							}
						  break;

						case 'team':
							if ((int) @$item->query['id'] == (int) @$query['id']) {
								return $item;
							}
							break;

						default:
							if (!isset($query['id']) || (int) @$item->query['id'] == (int) @$query['id']) {
								return $item;
							}
					}
				}
			}
		}

		return false;
	}
}
?>
