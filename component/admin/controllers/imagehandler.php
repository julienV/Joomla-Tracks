<?php
/**
 * @package     Tracks
 * @subpackage  Admin
 * @copyright   Tracks (C) 2008-2015 Julien Vonthron. All rights reserved.
 * @license     GNU General Public License version 2 or later
 */

// No direct access
defined('_JEXEC');

jimport('joomla.application.component.controller');
jimport('joomla.filesystem.file');
require_once JPATH_COMPONENT . '/helpers/imageselect.php';

/**
 * Tracks Component Imagehandler Controller
 *
 * @package  Tracks
 * @since    0.9
 */
class TracksControllerImagehandler extends JController
{
	/**
	 * logic for uploading an image
	 *
	 * @return void
	 */
	public function upload()
	{
		$mainframe = JFactory::getApplication();
		$option    = JRequest::getCmd('option');

		// Check for request forgeries
		JRequest::checkToken() or die('Invalid Token');

		$file = JRequest::getVar('userfile', '', 'files', 'array');
		$type   = JRequest::getVar('type');
		$folder = ImageSelect::getfolder($type);

		// Set FTP credentials, if given
		jimport('joomla.client.helper');
		JClientHelper::setCredentialsFromRequest('ftp');

		// Set the target directory
		$base_Dir = JPATH_SITE . '/' . 'media' . '/' . 'com_tracks' . '/' . 'images' . '/' . $folder . '/';

		// Do we have an upload?
		if (empty($file['name']))
		{
			echo "<script> alert('" . JText::_('COM_TRACKS_IMAGE_EMPTY') . "'); window.history.go(-1); </script>\n";
			$mainframe->close();
		}

		// Check the image
		$check = ImageSelect::check($file);

		if ($check === false)
		{
			$mainframe->redirect($_SERVER['HTTP_REFERER']);
		}

		// Sanitize the image filename
		$filename = ImageSelect::sanitize($base_Dir, $file['name']);
		$filepath = $base_Dir . $filename;

		// Upload the image
		if (!JFile::upload($file['tmp_name'], $filepath))
		{
			echo "<script> alert('" . JText::_('COM_TRACKS_UPLOAD_FAILED') . "'); window.history.go(-1); </script>\n";
			$mainframe->close();
		}
		else
		{
			echo "<script> alert('" . JText::_('COM_TRACKS_UPLOAD_COMPLETE') . "'); window.history.go(-1); window.parent.elSelectImage('$filename', '$filename'); </script>\n";
			$mainframe->close();
		}
	}

	/**
	 * logic to mass delete images
	 *
	 * @return void
	 */
	public function delete()
	{
		$mainframe = JFactory::getApplication();
		$option    = JRequest::getCmd('option');

		// Set FTP credentials, if given
		jimport('joomla.client.helper');
		JClientHelper::setCredentialsFromRequest('ftp');

		// Get some data from the request
		$images = JRequest::getVar('rm', array(), '', 'array');
		$type   = JRequest::getVar('type');
		$folder = ImageSelect::getfolder($type);

		if (count($images))
		{
			foreach ($images as $image)
			{
				if ($image !== JFilterInput::clean($image, 'path'))
				{
					JError::raiseWarning(100, JText::_('COM_TRACKS_UNABLE_TO_DELETE') . ' ' . htmlspecialchars($image, ENT_COMPAT, 'UTF-8'));
					continue;
				}

				$fullPath      = JPath::clean(JPATH_SITE . '/' . 'media' . '/' . 'com_tracks' . '/' . 'images' . '/' . $folder . '/' . $image);
				$fullPaththumb = JPath::clean(JPATH_SITE . '/' . 'media' . '/' . 'com_tracks' . '/' . 'images' . '/' . $folder . '/' . 'small' . '/' . $image);

				if (is_file($fullPath))
				{
					JFile::delete($fullPath);

					if (JFile::exists($fullPaththumb))
					{
						JFile::delete($fullPaththumb);
					}
				}
			}
		}

		$mainframe->redirect('index.php?option=com_tracks&view=imagehandler&type=' . $type . '&tmpl=component');
	}
}
