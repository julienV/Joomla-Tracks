<?php
/**
 * @package    Tracks.Site
 * @copyright  Tracks (C) 2008-2015 Julien Vonthron. All rights reserved.
 * @license    GNU General Public License version 2 or later
 */

// No direct access
defined('_JEXEC') or die('Restricted access');

/**
 * Joomla Tracks Component Front page Model
 *
 * @package  Tracks
 * @since    0.1
 */
class TracksModelProjectindividuals extends TracksModelFrontbase
{
	/**
	 * associated project
	 */
	var $project = null;

	/**
	 * Get individuals
	 *
	 * @param   int  $project_id  project id
	 *
	 * @return mixed|null
	 */
	public function getIndividuals($project_id = 0)
	{
		if (!$project_id)
		{
			$this->setError(JText::_('COM_TRACKS_No_project_specified'));

			return null;
		}

		$query = ' SELECT i.id, i.first_name, i.last_name, i.country_code, i.picture, i.picture_small, '
			. ' pi.number, pi.team_id, '
			. ' t.name as team_name, t.picture_small AS team_logo, '
			. ' CASE WHEN CHAR_LENGTH( i.alias ) THEN CONCAT_WS( \':\', i.id, i.alias ) ELSE i.id END AS slug, '
			. ' CASE WHEN CHAR_LENGTH( t.alias ) THEN CONCAT_WS( \':\', t.id, t.alias ) ELSE t.id END AS teamslug '
			. ' FROM #__tracks_participants as pi '
			. ' INNER JOIN #__tracks_individuals as i ON i.id = pi.individual_id '
			. ' LEFT JOIN #__tracks_teams as t ON t.id = pi.team_id '
			. ' WHERE pi.project_id = ' . $project_id
			. ' ORDER BY pi.number ASC, i.last_name ASC, i.first_name ASC ';

		$this->_db->setQuery($query);

		$result = $this->_db->loadObjectList();

		$count = count($result);

		for ($i = 0; $i < $count; $i++)
		{
			$obj              =& $result[$i];
			$attribs['class'] = "pic";

			if ($obj->picture != '')
			{
				$obj->picture = JHTML::image(JURI::root() . $obj->picture, $obj->first_name . ' ' . $obj->last_name, $attribs);
			}
			else
			{
				$obj->picture = JHTML::image(JURI::root() . 'media/com_tracks/images/misc/tnnophoto.jpg', $obj->first_name . ' ' . $obj->last_name, $attribs);
			}
		}

		return $result;
	}
}
