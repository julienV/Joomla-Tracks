<?php
/**
 * @package    Tracks.Site
 * @copyright  Tracks (C) 2008-2015 Julien Vonthron. All rights reserved.
 * @license    GNU General Public License version 2 or later
 */

// No direct access
use Joomla\CMS\Factory;
use Joomla\CMS\Plugin\PluginHelper;

defined('_JEXEC') or die('Restricted access');

/**
 * Joomla Tracks Component Front page Model
 *
 * @package  Tracks
 * @since    0.1
 */
class TracksModelTeam extends RModelAdmin
{
	protected $_id;

	protected $project_id;

	protected $_individuals;

	/**
	 * TracksModelTeam constructor.
	 *
	 * @param   array  $config  config
	 */
	public function __construct($config = array())
	{
		parent::__construct($config);

		$id      = JRequest::getInt('id');
		$project = JRequest::getInt('p');

		$this->setId($id);
		$this->project_id = $project;
	}

	/**
	 * Set id
	 *
	 * @param   int  $id  id
	 *
	 * @return void
	 */
	public function setId($id)
	{
		$this->_id          = (int) $id;
		$this->_data        = null;
		$this->_individuals = null;
	}

	/**
	 * Method to get a single record.
	 *
	 * @param   integer  $pk  The id of the primary key.
	 *
	 * @return  \JObject|boolean  Object on success, false on failure.
	 *
	 * @since   1.6
	 */
	public function getItem($pk = null)
	{
		$pk = $pk ?: $this->_id;

		if (empty($this->_data))
		{
			$query = ' SELECT t.* '
				. ' FROM #__tracks_teams as t '
				. ' WHERE t.id = ' . (int) $pk;

			$this->_db->setQuery($query);

			if ($result = $this->_db->loadObject())
			{
				$this->_data = $result;
			}

			if (PluginHelper::isEnabled('tracks', 'customfields'))
			{
				PluginHelper::importPlugin('tracks');
				$params = null;
				Factory::getApplication()->triggerEvent('onContentPrepare',
					['com_tracks.team', &$this->_data, &$params]);
			}
		}

		return $this->_data;
	}

	/**
	 * get individuals
	 *
	 * @return array
	 */
	public function getIndividuals()
	{
		if (empty($this->_individuals))
		{
			$query = ' SELECT p.name as project_name, i.*, pi.number, pi.project_id, s.name, '
				. ' CASE WHEN CHAR_LENGTH( i.alias ) THEN CONCAT_WS( \':\', i.id, i.alias ) ELSE i.id END AS slug, '
				. ' CASE WHEN CHAR_LENGTH( p.alias ) THEN CONCAT_WS( \':\', p.id, p.alias ) ELSE p.id END AS projectslug '
				. ' FROM #__tracks_individuals AS i '
				. ' INNER JOIN #__tracks_participants AS pi ON pi.individual_id = i.id '
				. ' INNER JOIN #__tracks_projects AS p ON pi.project_id = p.id '
				. ' INNER JOIN #__tracks_seasons AS s ON s.id = p.season_id '
				. ' WHERE pi.team_id = ' . $this->_db->Quote($this->_id)
				. ($this->project_id ? ' AND p.id = ' . $this->_db->Quote($this->project_id) : '')
				. ' ORDER BY p.ordering, i.last_name ASC ';
			$this->_db->setQuery($query);
			$res = $this->_db->loadObjectList();

			// Sort by projects
			$proj = array();

			foreach ((array) $res as $i)
			{
				if (!isset($proj[$i->project_id]))
				{
					$proj[$i->project_id] = array();
				}

				$proj[$i->project_id][] = $i;
			}

			$this->_individuals = $proj;
		}

		return $this->_individuals;
	}

	/**
	 * Method to validate the form data.
	 * Each field error is stored in session and can be retrieved with getFieldError().
	 * Once getFieldError() is called, the error is deleted from the session.
	 *
	 * @param   JForm   $form   The form to validate against.
	 * @param   array   $data   The data to validate.
	 * @param   string  $group  The name of the field group to validate.
	 *
	 * @return  mixed  Array of filtered data if valid, false otherwise.
	 */
	public function validate($form, $data, $group = null)
	{
		$validData = parent::validate($form, $data, $group);

		$validData = $this->getPicture($validData, $data, 'picture');
		$validData = $this->getPicture($validData, $data, 'picture_small');
		$validData = $this->getPicture($validData, $data, 'vehicle_picture');

		return $validData;
	}

	/**
	 * Manage upload of picture
	 *
	 * @param   array   $validData  valid data
	 * @param   array   $data       post data
	 * @param   string  $field      field name
	 *
	 * @return array valid data
	 *
	 * @throws Exception
	 */
	protected function getPicture($validData, $data, $field)
	{
		$params     = JComponentHelper::getParams('com_tracks');
		$files      = JFactory::getApplication()->input->files->get('jform', '', array());
		$targetpath = 'images/' . $params->get('default_team_images_folder', 'tracks/teams');

		if (!isset($files[$field]) || !$picture = $files[$field])
		{
			return false;
		}

		if (!empty($picture['name']))
		{
			$base_Dir = JPATH_SITE . '/' . $targetpath . '/';

			if (!JFolder::exists($base_Dir))
			{
				JFolder::create($base_Dir);
			}

			// Check the image
			$check = TrackslibHelperImage::check($picture);

			if ($check === false)
			{
				throw new Exception('IMAGE CHECK FAILED', 500);
			}

			// Sanitize the image filename
			$filename = TrackslibHelperImage::sanitize($base_Dir, $picture['name']);
			$filepath = $base_Dir . $filename;

			if (!JFile::upload($picture['tmp_name'], $filepath))
			{
				throw new Exception(JText::_('COM_TRACKS_UPLOAD_FAILED'), 500);
			}
			else
			{
				$validData[$field] = $targetpath . '/' . $filename;
			}
		}

		return $validData;
	}
}
