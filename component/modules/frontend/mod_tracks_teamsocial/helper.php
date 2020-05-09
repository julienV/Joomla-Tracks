<?php
/**
 * @package     JoomlaTracks
 * @subpackage  Modules.site
 * @copyright   Copyright (C) 2008-2015 Julien Vonthron. All rights reserved.
 * @license     GNU General Public License version 2 or later
 */

defined('_JEXEC') or die('Restricted access');

/**
 * Class modTracksTeamsocial
 *
 * @package     JoomlaTracks
 * @subpackage  Modules.site
 * @since       1.0
 */
class ModTracksTeamsocial
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

		$links = TrackslibHelperTools::getTeamSocialItems($res);

		return $links;
	}
}
