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
class TracksModelProfile extends TracksModelFrontbase
{
	/** individual id **/
	var $_id = 0;

	var $_userid;

	var $_data = null;

	/**
	 * Constructor
	 *
	 * @param   array  $config  An array of configuration options (name, state, dbo, table_path, ignore_request).
	 */
	public function __construct($config = array())
	{
		parent::__construct();

		$this->_id = JRequest::getInt('i');
	}

	/**
	 * Get data
	 *
	 * @return bool|mixed|null
	 */
	public function getData()
	{
		$user = JFactory::getUser();

		if ($this->_id)
		{
			$query = ' SELECT i.* '
				. ' FROM #__tracks_individuals as i '
				. ' WHERE i.id = ' . $this->_id;
		}
		elseif ($user->get('id'))
		{
			$query = ' SELECT i.* '
				. ' FROM #__tracks_individuals as i '
				. ' WHERE i.user_id = ' . $user->get('id');
		}
		else
		{
			JError::raiseError(403, JText::_('COM_TRACKS_ERROR_MUST_BE_LOGGED'));

			return false;
		}

		$this->_db->setQuery($query);

		if ($result = $this->_db->loadObject())
		{
			$this->_data      = $result;
			$attribs['class'] = "pic";

			if ($this->_data->picture != '')
			{
				$this->_data->picture = JHTML::image(JURI::root() . $this->_data->picture, $this->_data->first_name . ' ' . $this->_data->last_name, $attribs);
			}
			else
			{
				$this->_data->picture = JHTML::image(JURI::root() . 'media/com_tracks/images/misc/tnnophoto.jpg', $this->_data->first_name . ' ' . $this->_data->last_name, $attribs);
			}

			if ($this->_data->picture_small != '')
			{
				$this->_data->picture_small = JHTML::image(JURI::root() . $this->_data->picture_small, $this->_data->first_name . ' ' . $this->_data->last_name, $attribs);
			}
			else
			{
				$this->_data->picture_small = JHTML::image(JURI::root() . 'media/com_tracks/images/misc/tnnophoto.jpg', $this->_data->first_name . ' ' . $this->_data->last_name, $attribs);
			}
		}

		return $this->_data;
	}

	/**
	 * Init data
	 *
	 * @return bool
	 */
	public function _initData()
	{
		$object                   = new stdClass;
		$object->id               = 0;
		$object->last_name        = "";
		$object->first_name       = "";
		$object->nickname         = "";
		$object->dob              = null;
		$object->height           = null;
		$object->weight           = null;
		$object->hometown         = null;
		$object->country          = null;
		$object->user_id          = 0;
		$object->picture          = null;
		$object->picture_small    = null;
		$object->address          = null;
		$object->postcode         = null;
		$object->city             = null;
		$object->state            = null;
		$object->country_code     = null;
		$object->description      = null;
		$object->checked_out      = 0;
		$object->checked_out_time = 0;
		$this->_data              = $object;

		return (boolean) $this->_data;
	}
}
