<?php
/**
 * @version        $Id: base.php 54 2008-04-22 14:50:30Z julienv $
 * @package        JoomlaTracks
 * @copyright      Copyright (C) 2008 Julien Vonthron. All rights reserved.
 * @license        GNU/GPL, see LICENSE.php
 * Joomla Tracks is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * See COPYRIGHT.php for copyright notices and details.
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();

jimport('joomla.application.component.model');

/**
 * Joomla Tracks Component Front page Model
 *
 * @package        Tracks
 * @since          0.1
 */
class baseModel extends RModel
{
	/**
	 * associated project
	 */
	protected $project = null;

	protected $project_id = null;

	/**
	 * reference to ranking class
	 * @var unknown_type
	 */
	protected $rankingtool = null;

	protected $_id = 0;

	/**
	 * caching for data
	 */
	protected $_data = null;

	/**
	 * Constructor
	 *
	 * @param   array  $config  An array of configuration options (name, state, dbo, table_path, ignore_request).
	 */
	public function __construct($config = array())
	{
		parent::__construct($config);

		$project = JFactory::getApplication()->input->getInt('p', 0);

		$this->setProjectId($project);
	}

	/**
	 * set id of object to display
	 *
	 * depends on view context...
	 *
	 * @param int $id
	 */
	public function setId($id)
	{
		$this->_id = (int) $id;
		$this->_data = null;
	}

	/**
	 * set id of project to display
	 *
	 * @param int $id
	 */
	public function setProjectId($id)
	{
		$this->project_id = (int) $id;
		$this->project = null;
	}


	/**
	 * returns project object
	 *
	 * @param int project_id
	 *
	 * @return object project
	 */
	public function getProject($project_id = 0)
	{
		if ($this->project && ($project_id == 0 || $project_id == $this->project->id))
		{
			return $this->project;
		}
		if ($project_id && $project_id != $this->project_id)
		{
			$this->setProjectId($project_id);
		}

		$query = ' SELECT p.*, s.name as season_name, c.name as competition_name, '
			. ' CASE WHEN CHAR_LENGTH( p.alias ) THEN CONCAT_WS( \':\', p.id, p.alias ) ELSE p.id END AS slug, '
			. ' CASE WHEN CHAR_LENGTH( s.alias ) THEN CONCAT_WS( \':\', s.id, s.alias ) ELSE s.id END AS season_slug, '
			. ' CASE WHEN CHAR_LENGTH( c.alias ) THEN CONCAT_WS( \':\', c.id, c.alias ) ELSE c.id END AS competition_slug '
			. ' FROM #__tracks_projects AS p '
			. ' INNER JOIN #__tracks_seasons AS s ON s.id = p.season_id '
			. ' INNER JOIN #__tracks_competitions AS c ON c.id = p.competition_id '
			. ' WHERE p.id = ' . $this->project_id;

		$this->_db->setQuery($query);

		if ($result = $this->_db->loadObjectList())
		{
			$this->project = $result[0];
			return $this->project;
		}
		else
		{
			return $result;
		}
	}

	public function getParams($project_id = 0, $xml = '')
	{
		$project = $this->getProject($project_id);

		if ($xml == '')
		{
			$params = new JRegistry;
			$params->loadString($project->params);
		}
		else
		{
			$query = ' SELECT settings '
				. ' FROM #__tracks_project_settings '
				. ' WHERE project_id = ' . $this->_db->Quote($this->project_id)
				. '   AND xml = ' . $this->_db->Quote($xml);
			$this->_db->setQuery($query);

			if ($settings = $this->_db->loadObject())
			{
				$params = new JRegistry;
				$params->loadString($settings);
			}
			else
			{
				$params = false;
			}
		}

		return $params;
	}

	protected function _reset()
	{
		$this->project_id = 0;
		$this->_id = 0;
		$this->project = null;
	}

	protected function _getRankingTool()
	{
		if (empty($this->rankingtool))
		{
			$project = TrackslibEntityProject::load($this->project_id);
			$project_type = $project->getProjectType();

			$path = JPATH_SITE . '/components/com_tracks/sports/' . $project_type . '/rankingtool.php';

			if (file_exists($path))
			{
				include_once $path;

				$className = 'TracksRankingTool' . ucfirst($project_type);

				$this->rankingtool = new $className($this->project_id);
			}
			else
			{
				include_once JPATH_SITE . '/components/com_tracks/sports/default/rankingtool.php';

				$this->rankingtool = new TracksRankingToolDefault($this->project_id);
			}
		}

		return $this->rankingtool;
	}
}
