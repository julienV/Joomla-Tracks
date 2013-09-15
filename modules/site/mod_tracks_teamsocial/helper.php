<?php
/**
 * @version     1.0
 * @package     JoomlaTracks
 * @subpackage  module.teamsocial
 * @copyright   Copyright (C) 2008 Julien Vonthron. All rights reserved.
 * @license     GNU/GPL, see LICENSE.php
 *              Joomla Tracks is free software. This version may have been modified pursuant
 *              to the GNU General Public License, and as distributed it includes or
 *              is derivative of works licensed under the GNU General Public License or
 *              other free or open source software licenses.
 *              See COPYRIGHT.php for copyright notices and details.
 */

// No direct access
defined('_JEXEC') or die('Restricted access');

require_once JPATH_SITE . '/components/com_tracks/helpers/tools.php';

/**
 * Class modTracksTeamsocial
 *
 * @since 1.0
 */
class modTracksTeamsocial
{
	/**
	 * return items to display
	 *
	 * @param   int  $team_id  team id
	 *
	 * @return object team
	 */
	public function getTeamLinks($team_id)
	{
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);

		$query->select('*');
		$query->from('#__tracks_teams');
		$query->where('id = ' . (int) $team_id);

		$db->setQuery($query);
		$res = $db->loadObject();

		$links = TracksHelperTools::getTeamSocialItems($res);

		return $links;
	}
}
