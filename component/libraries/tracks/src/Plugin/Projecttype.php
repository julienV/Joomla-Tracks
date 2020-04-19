<?php
/**
 * @package     Tracks.Plugin
 * @subpackage  Tracks.projecttype
 *
 * @copyright   Copyright (C) 2008-2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 3 or later, see LICENSE.
 */

namespace Tracks\Plugin;

defined('JPATH_BASE') or die;

// Import library dependencies
jimport('joomla.plugin.plugin');

abstract class Projecttype extends \JPlugin
{
	/**
	 * @var boolean
	 */
	protected $autoloadLanguage = true;

	/**
	 * @var string
	 */
	protected $context;

	/**
	 * Get available project types
	 *
	 * @param   array  $array  project types
	 *
	 * @return boolean true on success
	 */
	public abstract function onTracksGetProjectTypes(&$array);

	/**
	 * Callback to set rankingtool for project
	 *
	 * @param   integer                       $projectId    project id
	 * @param   \TrackslibRankingtoolDefault  $rankingTool  ranking tool
	 *
	 * @return boolean
	 */
	public function onTracksGetRankingTool($projectId, &$rankingTool)
	{
		$project = \TrackslibEntityProject::load($projectId);

		// If project type is not set, continue and assign default ranking tool (for legacy)
		if ($project->type && $project->type !== $this->context)
		{
			return true;
		}

		$rankingTool = new \TrackslibRankingtoolDefault($projectId);

		return true;
	}
}
