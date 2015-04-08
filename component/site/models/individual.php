<?php
/**
 * @version      $Id: roundresult.php 61 2008-04-24 15:20:36Z julienv $
 * @package      JoomlaTracks
 * @copyright    Copyright (C) 2008 Julien Vonthron. All rights reserved.
 * @license      GNU/GPL, see LICENSE.php
 * Joomla Tracks is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * See COPYRIGHT.php for copyright notices and details.
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();

/**
 * Joomla Tracks Component Front page Model
 *
 * @package     Tracks
 * @since       0.1
 */
class TracksModelIndividual extends RModelAdmin
{
	private $project_id = null;

	public function __construct($config = array())
	{
		parent::__construct($config);

		$this->project_id = JFactory::getApplication()->input->getInt('p', 0);
	}


	/**
	 * Method to get a single record.
	 *
	 * @param   integer  $pk  The id of the primary key.
	 *
	 * @return  mixed    Object on success, false on failure.
	 */
	public function getItem($pk = null)
	{
		$item = parent::getItem($pk);

		if ($item)
		{
			$attribs['class'] = "pic";

			if ($item->picture != '')
			{
				$item->picture = JHTML::image(JURI::root() . $item->picture, $item->first_name . ' ' . $item->last_name, $attribs);
			}
			else
			{
				$item->picture = JHTML::image(JURI::root() . 'media/com_tracks/images/misc/tnnophoto.jpg', $item->first_name . ' ' . $item->last_name, $attribs);
			}

			if ($item->picture_small != '')
			{
				$item->picture_small = JHTML::image(JURI::root() . $item->picture_small, $item->first_name . ' ' . $item->last_name, $attribs);
			}
			else
			{
				$item->picture_small = JHTML::image(JURI::root() . 'media/com_tracks/images/misc/tnnophoto.jpg', $item->first_name . ' ' . $item->last_name, $attribs);
			}

			$item = $this->loadProjectDetails($item);
		}

		return $item;
	}

	/**
	 * add info relevant to the individual in the current project, if a project is set
	 *
	 * @param   object  $item  item
	 *
	 * @return  mixed    Object on success, false on failure.
	 */
	protected function loadProjectDetails($item)
	{
		if ($this->project_id && $item)
		{
			$query = ' SELECT t.name as team_name, pi.number, '
				. ' CASE WHEN CHAR_LENGTH( t.alias ) THEN CONCAT_WS( \':\', t.id, t.alias ) ELSE t.id END AS teamslug, '
				. ' CASE WHEN CHAR_LENGTH( p.alias ) THEN CONCAT_WS( \':\', p.id, p.alias ) ELSE t.id END AS projectslug '
				. ' FROM #__tracks_participants AS pi '
				. ' LEFT JOIN #__tracks_teams AS t ON t.id = pi.team_id '
				. ' LEFT JOIN #__tracks_projects AS p ON p.id = pi.project_id '
				. ' WHERE pi.project_id = ' . $this->_db->Quote($this->project_id)
				. '   AND pi.individual_id = ' . $item->id;
			$this->_db->setQuery($query);
			$res = $this->_db->loadObject();
			$item->projectdata = $res;
		}
		else
		{
			$item->projectdata = null;
		}

		return $item;
	}

	public function getRaceResults($pk = null)
	{
		$pk = (!empty($pk)) ? $pk : (int) $this->getState($this->getName() . '.id');
		$app = JFactory::getApplication();
		$params = $app->getParams('com_tracks');

		if (!$pk)
		{
			return null;
		}

		$query = $this->_db->getQuery(true);

		$query->select('rr.rank, rr.performance, rr.bonus_points, pr.project_id')
			->select('r.name AS roundname, r.id as pr')
			->select('srt.name AS subroundname')
			->select('srt.points_attribution, srt.count_points')
			->select('p.name AS projectname')
			->select('c.name AS competitionname')
			->select('s.name AS seasonname')
			->select('t.name AS teamname')
			->select('CASE WHEN CHAR_LENGTH( r.alias ) THEN CONCAT_WS( \':\', pr.id, r.alias ) ELSE pr.id END AS prslug')
			->select('CASE WHEN CHAR_LENGTH( r.alias ) THEN CONCAT_WS( \':\', r.id, r.alias ) ELSE r.id END AS rslug')
			->from('#__tracks_events_results AS rr')
			->join('INNER', '#__tracks_events AS psr ON psr.id = rr.event_id')
			->join('INNER', '#__tracks_eventtypes AS srt ON srt.id = psr.type')
			->join('INNER', '#__tracks_projects_rounds AS pr ON pr.id = psr.projectround_id')
			->join('INNER', '#__tracks_rounds AS r ON r.id = pr.round_id')
			->join('INNER', '#__tracks_projects AS p ON p.id = pr.project_id')
			->join('INNER', '#__tracks_seasons AS s ON s.id = p.season_id')
			->join('INNER', '#__tracks_competitions AS c ON c.id = p.competition_id')
			->join('LEFT', '#__tracks_teams AS t ON t.id = rr.team_id')
			->where('rr.individual_id = ' . $this->_db->Quote($pk))
			->where('p.published')
			->where('pr.published')
			->where('psr.published');


		if ($params->get('indview_results_onlypointssubrounds', 1))
		{
			$query->where('srt.count_points = 1');
		}

		if ($params->get('indview_results_ordering', 1) == 0)
		{
			$query->order('s.ordering DESC, c.ordering DESC, p.ordering DESC, pr.ordering ASC, psr.ordering ASC');
		}
		else
		{
			$query->order('s.ordering ASC, c.ordering ASC, p.ordering ASC, pr.ordering ASC, psr.ordering ASC');
		}

		$this->_db->setQuery($query);
		$res = $this->_db->loadObjectList();

		return $res;
	}


	/**
	 * Method to store the individual data
	 *
	 * @access  public
	 * @return  boolean True on success
	 * @since   1.5
	 */
	function store($data, $picture, $picture_small)
	{
		// Require the base controller
		require_once(JPATH_COMPONENT_ADMINISTRATOR . '/' . 'tables' . '/' . 'individual.php');

		$table = $this->getTable('individual');
		$params = JComponentHelper::getParams('com_tracks');

		$user = JFactory::getUser();
		$username = $user->get('username');

		if ($data['id'])
		{
			$table->load($data['id']);

			if ($table->user_id != $user->get('id') && !$user->authorise('core.manage', 'com_tracks'))
			{
				JError::raiseError(403, JText::_('COM_TRACKS_ACCESS_NOT_ALLOWED'));
			}
		}

		// Bind the form fields to the user table
		if (!$table->bind($data))
		{
			$this->setError($this->_db->getErrorMsg());
			return false;
		}

		$targetpath = 'images' . '/' . $params->get('default_individual_images_folder', 'tracks/individuals');

		jimport('joomla.filesystem.file');
		jimport('joomla.filesystem.folder');

		if (!empty($picture['name']))
		{

			$base_Dir = JPATH_SITE . '/' . $targetpath . '/';
			if (!JFolder::exists($base_Dir))
			{
				JFolder::create($base_Dir);
			}

			//check the image
			$check = ImageSelect::check($picture);

			if ($check === false)
			{
				$this->setError('IMAGE CHECK FAILED');
				return false;
			}

			//sanitize the image filename
			$filename = ImageSelect::sanitize($base_Dir, $picture['name']);
			$filepath = $base_Dir . $filename;
			//dump($filepath);

			if (!JFile::upload($picture['tmp_name'], $filepath))
			{
				$this->setError(JText::_('COM_TRACKS_UPLOAD_FAILED'));
				return false;
			}
			else
			{
				$table->picture = $targetpath . '/' . $filename;
			}
		}
		else
		{
			//keep image if edited and left blank
			unset($table->picture);
		}//end image upload if

		if (!empty($picture_small['name']))
		{

			jimport('joomla.filesystem.file');

			$base_Dir = JPATH_SITE . '/' . $targetpath . '/' . 'small' . '/';
			if (!JFolder::exists($base_Dir))
			{
				JFolder::create($base_Dir);
			}

			//check the image
			$check = ImageSelect::check($picture_small);

			if ($check === false)
			{
				$this->setError('IMAGE CHECK FAILED');
				return false;
			}

			//sanitize the image filename
			$filename = ImageSelect::sanitize($base_Dir, $picture_small['name']);
			$filepath = $base_Dir . $filename;

			if (!JFile::upload($picture_small['tmp_name'], $filepath))
			{
				$this->setError(JText::_('COM_TRACKS_UPLOAD_FAILED'));
				return false;
			}
			else
			{
				$table->picture_small = $targetpath . '/' . 'small' . '/' . $filename;
			}
		}
		else
		{
			//keep image if edited and left blank
			unset($table->picture_small);
		}//end image upload if

		// Store the individual to the database
		if (!$table->save($data))
		{
			$this->setError($user->getError());
			return false;
		}
		$this->id = $table->id;

		return $this->id;
	}

	/**
	 * returns individual id if stored.
	 *
	 * @return int
	 */
	public function getId()
	{
		return $this->getState($this->getName() . '.id');
	}
}
