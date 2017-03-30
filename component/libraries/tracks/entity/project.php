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
	 * @return boolean|mixed
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

	/**
	 * Get a param
	 *
	 * @param   string   $name     param name
	 * @param   mixed    $default  default value if not found
	 *
	 * @return mixed
	 *
	 * @throws LogicException
	 */
	public function getParam($name, $default = null)
	{
		$item = $this->loadItem();

		if (!$item)
		{
			throw new LogicException('Project not loaded in entity');
		}

		$params = new JRegistry($item->params);

		return $params->get($name, $default);
	}
}
