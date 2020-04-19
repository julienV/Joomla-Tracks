<?php
/**
 * @package     Tracks
 * @subpackage  Admin
 * @copyright   Tracks (C) 2008-2015 Julien Vonthron. All rights reserved.
 * @license     GNU General Public License version 2 or later
 */

defined('_JEXEC') or die();

jimport('joomla.application.component.model');
jimport('joomla.filesystem.folder');
jimport('joomla.filesystem.file');

/**
 * Tracks Component Imageselect Model
 *
 * @package     Joomla
 * @subpackage  Tracks
 * @since       0.9
 */
class TracksModelImagehandler extends JModel
{
	/**
	 * Pagination object
	 *
	 * @var object
	 */
	var $_pagination = null;

	/**
	 * Constructor
	 *
	 * @param   array  $config  An array of configuration options (name, state, dbo, table_path, ignore_request).
	 */
	public function __construct($config = array())
	{
		parent::__construct();

		$mainframe = JFactory::getApplication();
		$option    = JRequest::getCmd('option');

		$limit      = $mainframe->getUserStateFromRequest('com_tracks.imageselect' . 'limit', 'limit', $mainframe->getCfg('list_limit'), 'int');
		$limitstart = $mainframe->getUserStateFromRequest('com_tracks.imageselect' . 'limitstart', 'limitstart', 0, 'int');
		$search     = $mainframe->getUserStateFromRequest('com_tracks.search', 'search', '', 'string');
		$search     = trim(JString::strtolower($search));

		$this->setState('limit', $limit);
		$this->setState('limitstart', $limitstart);
		$this->setState('search', $search);
	}

	/**
	 * Build imagelist
	 *
	 * @return array $list The imagefiles from a directory to display
	 *
	 * @since 0.9
	 */
	public function getImages()
	{
		$list = $this->getList();

		$listimg = array();

		$s = $this->getState('limitstart') + 1;

		for ($i = ($s - 1); $i < $s + $this->getState('limit'); $i++)
		{
			if ($i + 1 <= $this->getState('total'))
			{
				$list[$i]->size = $this->_parseSize(filesize($list[$i]->path));

				$info             = @getimagesize($list[$i]->path);
				$list[$i]->width  = @$info[0];
				$list[$i]->height = @$info[1];

				if (($info[0] > 60) || ($info[1] > 60))
				{
					$dimensions          = $this->_imageResize($info[0], $info[1], 60);
					$list[$i]->width_60  = $dimensions[0];
					$list[$i]->height_60 = $dimensions[1];
				}
				else
				{
					$list[$i]->width_60  = $list[$i]->width;
					$list[$i]->height_60 = $list[$i]->height;
				}

				$listimg[] = $list[$i];
			}
		}

		return $listimg;
	}

	/**
	 * Build imagelist
	 *
	 * @return array $list The imagefiles from a directory
	 *
	 * @since 0.9
	 */
	public function getList()
	{
		static $list;

		// Only process the list once per request
		if (is_array($list))
		{
			return $list;
		}

		// Get folder from request
		$folder = $this->getState('folder');
		$search = $this->getState('search');

		// Initialize variables
		$basePath = JPATH_SITE . '/' . 'media' . '/' . 'com_tracks' . '/' . 'images' . '/' . $folder;

		$images = array();

		// Get the list of files and folders from the given folder
		$fileList = JFolder::files($basePath);

		// Iterate over the files if they exist
		if ($fileList !== false)
		{
			foreach ($fileList as $file)
			{
				if (is_file($basePath . '/' . $file) && substr($file, 0, 1) != '.' && strtolower($file) !== 'index.html')
				{
					if ($search == '')
					{
						$tmp       = new JObject;
						$tmp->name = $file;
						$tmp->path = JPath::clean($basePath . '/' . $file);

						$images[] = $tmp;
					}
					elseif (stristr($file, $search))
					{
						$tmp       = new JObject;
						$tmp->name = $file;
						$tmp->path = JPath::clean($basePath . '/' . $file);

						$images[] = $tmp;
					}
				}
			}
		}

		$list = $images;

		$this->setState('total', count($list));

		return $list;
	}

	/**
	 * Method to get model state variables
	 *
	 * @param   string  $property  Optional parameter name
	 * @param   mixed   $default   Optional default value
	 *
	 * @return  object  The property where specified, the state object where omitted
	 */
	public function getState($property = null, $default = null)
	{
		static $set;

		if (!$set)
		{
			$folder = JRequest::getVar('folder');
			$this->setState('folder', $folder);

			$set = true;
		}

		return parent::getState($property);
	}

	/**
	 * Return human readable size info
	 *
	 * @param   int  $size  size
	 *
	 * @return string size of image
	 */
	private function _parseSize($size)
	{
		if ($size < 1024)
		{
			return $size . ' bytes';
		}
		else
		{
			if ($size >= 1024 && $size < 1024 * 1024)
			{
				return sprintf('%01.2f', $size / 1024.0) . ' Kb';
			}
			else
			{
				return sprintf('%01.2f', $size / (1024.0 * 1024)) . ' Mb';
			}
		}
	}

	/**
	 * Resize
	 *
	 * @param   int  $width   width
	 * @param   int  $height  height
	 * @param   int  $target  target size
	 *
	 * @return array
	 */
	private function _imageResize($width, $height, $target)
	{
		/* Takes the larger size of the width and height and applies the
		 Formula accordingly...this is so this script will work
		 Dynamically with any size image */
		if ($width > $height)
		{
			$percentage = ($target / $width);
		}
		else
		{
			$percentage = ($target / $height);
		}

		// Gets the new value and applies the percentage, then rounds the value
		$width  = round($width * $percentage);
		$height = round($height * $percentage);

		return array($width, $height);
	}

	/**
	 * Method to get a pagination object for the images
	 *
	 * @access public
	 * @return integer
	 */
	public function getPagination()
	{
		if (empty($this->_pagination))
		{
			jimport('joomla.html.pagination');
			$this->_pagination = new JPagination($this->getState('total'), $this->getState('limitstart'), $this->getState('limit'));
		}

		return $this->_pagination;
	}
}
