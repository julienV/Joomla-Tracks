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
class TracksModelIndividualform extends RModelAdmin
{
	protected $context = 'com_tracks';

	protected $formName = 'individual';

	protected $event_after_save = "onAfterIndividualSave";

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

		if (PluginHelper::isEnabled('tracks', 'customfields'))
		{
			PluginHelper::importPlugin('tracks');
			$params = null;

			// Dummy add $text to prevent a warning in onContentPrepare modules
			$item->text = null;
			Factory::getApplication()->triggerEvent('onContentPrepare', ['com_tracks.individual', &$item, &$params]);
		}

		return $item;
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

		if (!JFactory::getUser()->authorise('core.edit', 'com_tracks'))
		{
			$validData['user_id'] = JFactory::getUser()->get('id');
		}
		elseif (isset($data['assign_me']))
		{
			$validData['user_id'] = JFactory::getUser()->get('id');
		}

		$validData = $this->getPicture($validData, $data, 'picture');
		$validData = $this->getPicture($validData, $data, 'picture_small');

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
	 */
	protected function getPicture($validData, $data, $field)
	{
		$params     = JComponentHelper::getParams('com_tracks');
		$files      = JFactory::getApplication()->input->files->get('jform', '', array());
		$targetpath = 'images/' . $params->get('default_individual_images_folder', 'tracks/individuals');

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
				$this->setError('IMAGE CHECK FAILED');

				return false;
			}

			// Sanitize the image filename
			$filename = TrackslibHelperImage::sanitize($base_Dir, $picture['name']);
			$filepath = $base_Dir . $filename;

			if (!JFile::upload($picture['tmp_name'], $filepath))
			{
				$this->setError(JText::_('COM_TRACKS_UPLOAD_FAILED'));

				return false;
			}
			else
			{
				$validData[$field] = $targetpath . '/' . $filename;
			}
		}

		return $validData;
	}

	/**
	 * Can current user edit individual
	 *
	 * @param   object  $item  individual
	 *
	 * @return bool
	 */
	public function canEdit($item)
	{
		$user = JFactory::getUser();

		if (!$item->id)
		{
			return $this->canCreate();
		}
		elseif (JFactory::getUser()->authorise('core.edit', 'com_tracks'))
		{
			return true;
		}
		else
		{
			return $item->user_id && $item->user_id == $user->get('id');
		}
	}

	/**
	 * Can the user create an individual
	 *
	 * @return bool
	 */
	public function canCreate()
	{
		return JComponentHelper::getParams('com_tracks')->get('user_registration')
			|| JFactory::getUser()->authorise('core.edit', 'com_tracks');
	}

	/**
	 * Method to store the individual data
	 *
	 * @param   array   $data           data
	 * @param   string  $picture        picture path
	 * @param   string  $picture_small  small picture path
	 *
	 * @return bool
	 */
	public function store($data, $picture, $picture_small)
	{
		// Require the base controller
		$table  = $this->getTable('individual');
		$params = JComponentHelper::getParams('com_tracks');

		$user     = JFactory::getUser();
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

			// Check the image
			$check = ImageSelect::check($picture);

			if ($check === false)
			{
				$this->setError('IMAGE CHECK FAILED');

				return false;
			}

			// Sanitize the image filename
			$filename = ImageSelect::sanitize($base_Dir, $picture['name']);
			$filepath = $base_Dir . $filename;

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
			// Keep image if edited and left blank
			unset($table->picture);
		}
		// End image upload if

		if (!empty($picture_small['name']))
		{
			jimport('joomla.filesystem.file');

			$base_Dir = JPATH_SITE . '/' . $targetpath . '/' . 'small' . '/';

			if (!JFolder::exists($base_Dir))
			{
				JFolder::create($base_Dir);
			}

			// Check the image
			$check = ImageSelect::check($picture_small);

			if ($check === false)
			{
				$this->setError('IMAGE CHECK FAILED');

				return false;
			}

			// Sanitize the image filename
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
			// Keep image if edited and left blank
			unset($table->picture_small);
		}
		// End image upload if

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
	 * Method to get a table object, load it if necessary.
	 *
	 * @param   string  $name     The table name. Optional.
	 * @param   string  $prefix   The class prefix. Optional.
	 * @param   array   $options  Configuration array for model. Optional.
	 *
	 * @return  \JTable  A \JTable object
	 *
	 * @since   3.0
	 * @throws  \Exception
	 */
	public function getTable($name = '', $prefix = 'TracksTable', $options = array())
	{
		if (empty($name))
		{
			$name = 'Individual';
		}

		if ($table = $this->_createTable($name, $prefix, $options))
		{
			return $table;
		}

		throw new \Exception(\JText::sprintf('JLIB_APPLICATION_ERROR_TABLE_NAME_NOT_SUPPORTED', $name), 0);
	}

	/**
	 * return individual id for user
	 *
	 * @return int
	 */
	public function getUserIndividual()
	{
		$user = JFactory::getUser();

		$db    = JFactory::getDbo();
		$query = $db->getQuery(true);

		$query->select('id');
		$query->from('#__tracks_individuals');
		$query->where('user_id = ' . (int) $user->id);

		$db->setQuery($query);
		$res = $db->loadResult();

		return $res;
	}
}
