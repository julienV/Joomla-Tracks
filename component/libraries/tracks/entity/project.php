<?php
/**
 * @package     Tracks.library
 * @subpackage  Entity
 * @copyright   Tracks (C) 2008-2015 Julien Vonthron. All rights reserved.
 * @license     GNU General Public License version 2 or later
 */

defined('_JEXEC') or die;

/**
 * Project Entity.
 *
 * @since  3.0
 */
class TrackslibEntityProject extends TrackslibEntityBase
{
	/**
	 * Return project type
	 *
	 * @return bool|mixed
	 */
	public function getProjectType()
	{
		$item = $this->loadItem();

		if (!$item)
		{
			return false;
		}

		$params = new JRegistry($item->params);

		return $params->get('project_type') ?: 'default';
	}
}
