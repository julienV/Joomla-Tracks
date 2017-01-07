<?php
/**
 * @package     Tracks
 * @subpackage  Library
 * @copyright   Tracks (C) 2008-2015 Julien Vonthron. All rights reserved.
 * @license     GNU General Public License version 2 or later
 */

defined('_JEXEC') or die;

jimport('joomla.filesystem.folder');

/**
 * Class TrackslibHelperImage
 *
 * @package  Tracks
 * @since    1.0
 */
class TrackslibHelperImage
{
	/**
	 * Determine the GD version
	 * Code from php.net
	 *
	 * @param   int  $user_ver  user version
	 *
	 * @return int
	 */
	public static function gdVersion($user_ver = 0)
	{
		if (!extension_loaded('gd'))
		{
			return;
		}

		static $gd_ver = 0;

		// Just accept the specified setting if it's 1.
		if ($user_ver == 1)
		{
			$gd_ver = 1;

			return 1;
		}

		// Use the static variable if function was called previously.
		if ($user_ver != 2 && $gd_ver > 0)
		{
			return $gd_ver;
		}

		// Use the gd_info() function if possible.
		if (function_exists('gd_info'))
		{
			$ver_info = gd_info();
			preg_match('/\d/', $ver_info['GD Version'], $match);
			$gd_ver = $match[0];

			return $match[0];
		}

		// If phpinfo() is disabled use a specified / fail-safe choice...
		if (preg_match('/phpinfo/', ini_get('disable_functions')))
		{
			if ($user_ver == 2)
			{
				$gd_ver = 2;

				return 2;
			}
			else
			{
				$gd_ver = 1;

				return 1;
			}
		}

		// ...otherwise use phpinfo().
		ob_start();
		phpinfo(8);
		$info = ob_get_contents();
		ob_end_clean();
		$info = stristr($info, 'gd version');
		preg_match('/\d/', $info, $match);
		$gd_ver = $match[0];

		return $match[0];
	}

	/**
	 * Returns the hml code for modal display of image
	 * If thumbnails exits, display the thumbnail with a modal link,
	 * otherwise, just display the full size picture
	 *
	 * @param   string  $image_path  full image path, or relative to joomla root
	 * @param   string  $alt         attribute
	 * @param   string  $maxdim      maximum dimension in pixels
	 * @param   array   $attribs     attributes
	 *
	 * @return string
	 */
	public static function modalimage($image_path, $alt, $maxdim, $attribs = array())
	{
		jimport('joomla.filesystem.file');
		$app  = JFactory::getApplication();
		$base = $app->isAdmin() ? $app->getSiteURL() : JURI::base();

		if (empty($image_path) || !file_exists($image_path))
		{
			return false;
		}

		// Make path relative to joomla root
		$image_dir_rel = str_replace(JPATH_SITE, '', dirname($image_path));

		if ($image_dir_rel[0] == '/' || $image_dir_rel[0] == '\\')
		{
			$image_dir_rel = substr($image_dir_rel, 1);
		}

		$image_dir_rel_path = $base . str_replace("\\", "/", $image_dir_rel);

		$thumb_path = self::getThumbUrl($image_path, $maxdim);

		JHTML::_('behavior.modal', 'a.imodal');

		if (isset($attribs['class']))
		{
			$attribs['class'] .= ' imodal';
		}
		else
		{
			$attribs['class'] = 'imodal';
		}

		$thumb = JHTML::image($thumb_path, $alt, $attribs);
		$html  = JHTML::link($image_dir_rel_path . '/' . basename($image_path), $thumb, $attribs);

		return $html;
	}

	/**
	 * return full url to thumbnail
	 *
	 * @param   string  $image_path  image_path full image path, or relative to joomla root
	 * @param   string  $maxdim      max size
	 *
	 * @return url or false if it doesn't exists
	 *
	 * @throws Exception
	 */
	public static function getThumbUrl($image_path, $maxdim)
	{
		jimport('joomla.filesystem.file');
		$app  = JFactory::getApplication();
		$base = $app->isAdmin() ? $app->getSiteURL() : JURI::base();

		if (!JFile::exists($image_path))
		{
			throw new Exception('Image not found');

			return false;
		}

		// Make path relative to joomla root
		$image_dir_rel = str_replace(JPATH_SITE, '', dirname($image_path));

		if ($image_dir_rel[0] == '/' || $image_dir_rel[0] == '\\')
		{
			$image_dir_rel = substr($image_dir_rel, 1);
		}

		$image_dir_rel_path = $base . str_replace("\\", "/", $image_dir_rel);

		if ($maxdim)
		{
			$width  = $maxdim;
			$height = $maxdim;
		}
		else
		{
			return false;
		}

		$img_dir  = dirname($image_path);
		$img_name = basename($image_path);

		$thumb_name = md5($img_name) . $width . '_' . $height . '.png';
		$thumb_path = $img_dir . '/' . 'small' . '/' . $thumb_name;

		if (JFile::exists($thumb_path))
		{
			return $image_dir_rel_path . '/small/' . $thumb_name;
		}
		else
		{
			// Try to generate the thumb
			if (self::thumb($image_path, $thumb_path, $width, $height))
			{
				return $image_dir_rel_path . '/small/' . $thumb_name;
			}
		}

		return false;
	}

	/**
	 * Creates a Thumbnail of an image
	 *
	 * @param   string  $file    The path to the file
	 * @param   string  $save    The targetpath
	 * @param   string  $width   The with of the image
	 * @param   string  $height  The height of the image
	 *
	 * @return true when success
	 */
	public static function thumb($file, $save, $width, $height)
	{
		@unlink($save);

		if (!file_exists(dirname($save)))
		{
			JFolder::create(dirname($save));
		}

		// Get sizes else stop
		if (!$infos = @getimagesize($file))
		{
			return false;
		}

		// Keep proportions
		$iWidth  = $infos[0];
		$iHeight = $infos[1];
		$iRatioW = $width / $iWidth;
		$iRatioH = $height / $iHeight;

		if ($iRatioW < $iRatioH)
		{
			$iNewW = $iWidth * $iRatioW;
			$iNewH = $iHeight * $iRatioW;
		}
		else
		{
			$iNewW = $iWidth * $iRatioH;
			$iNewH = $iHeight * $iRatioH;
		}

		// Don't resize images which are smaller than thumbs
		if ($infos[0] < $width && $infos[1] < $height)
		{
			$iNewW = $infos[0];
			$iNewH = $infos[1];
		}

		if ($infos[2] == 1)
		{
			/*
				* Image is typ gif
			*/
			$imgA = imagecreatefromgif($file);
			$imgB = imagecreate($iNewW, $iNewH);

			// Keep gif transparent color if possible
			if (function_exists('imagecolorsforindex') && function_exists('imagecolortransparent'))
			{
				$transcolorindex = imagecolortransparent($imgA);

				// Transparent color exists
				if ($transcolorindex >= 0)
				{
					$transcolor      = imagecolorsforindex($imgA, $transcolorindex);
					$transcolorindex = imagecolorallocate($imgB, $transcolor['red'], $transcolor['green'], $transcolor['blue']);
					imagefill($imgB, 0, 0, $transcolorindex);
					imagecolortransparent($imgB, $transcolorindex);
				}
				else
				{
					$whitecolorindex = @imagecolorallocate($imgB, 255, 255, 255);
					imagefill($imgB, 0, 0, $whitecolorindex);
				}
			}
			else
			{
				$whitecolorindex = imagecolorallocate($imgB, 255, 255, 255);
				imagefill($imgB, 0, 0, $whitecolorindex);
			}

			imagecopyresampled($imgB, $imgA, 0, 0, 0, 0, $iNewW, $iNewH, $infos[0], $infos[1]);
			imagegif($imgB, $save);
		}
		elseif ($infos[2] == 2)
		{
			/*
				* Image is typ jpg
			*/
			$imgA = imagecreatefromjpeg($file);
			$imgB = imagecreatetruecolor($iNewW, $iNewH);
			imagecopyresampled($imgB, $imgA, 0, 0, 0, 0, $iNewW, $iNewH, $infos[0], $infos[1]);
			imagejpeg($imgB, $save);
		}
		elseif ($infos[2] == 3)
		{
			/*
				* Image is typ png
			*/
			$imgA = imagecreatefrompng($file);
			$imgB = imagecreatetruecolor($iNewW, $iNewH);
			imagealphablending($imgB, false);
			imagecopyresampled($imgB, $imgA, 0, 0, 0, 0, $iNewW, $iNewH, $infos[0], $infos[1]);
			imagesavealpha($imgB, true);
			imagepng($imgB, $save);
		}
		else
		{
			return false;
		}

		return true;
	}

	/**
	 * Check file
	 *
	 * @param   string  $file  file path
	 *
	 * @return bool
	 */
	public static function check($file)
	{
		jimport('joomla.filesystem.file');

		$imagesize = $file['size'];

		// Check if the upload is an image...getimagesize will return false if not
		if (!getimagesize($file['tmp_name']))
		{
			JError::raiseWarning(100, 'NOT_AN_IMAGE' . ': ' . htmlspecialchars($file['name'], ENT_COMPAT, 'UTF-8'));

			return false;
		}

		// Check if the imagefiletype is valid
		$fileext = strtolower(JFile::getExt($file['name']));

		$allowable = array('gif', 'jpg', 'png');

		if (!in_array($fileext, $allowable))
		{
			JError::raiseWarning(100, 'WRONG_IMAGE_FILE_TYPE' . ': ' . htmlspecialchars($file['name'], ENT_COMPAT, 'UTF-8'));

			return false;
		}

		// XSS check
		$xss_check = JFile::read($file['tmp_name'], false, 256);
		$html_tags = array('abbr', 'acronym', 'address', 'applet', 'area', 'audioscope', 'base', 'basefont', 'bdo', 'bgsound', 'big', 'blackface', 'blink', 'blockquote', 'body', 'bq', 'br', 'button', 'caption', 'center', 'cite', 'code', 'col', 'colgroup', 'comment', 'custom', 'dd', 'del', 'dfn', 'dir', 'div', 'dl', 'dt', 'em', 'embed', 'fieldset', 'fn', 'font', 'form', 'frame', 'frameset', 'h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'head', 'hr', 'html', 'iframe', 'ilayer', 'img', 'input', 'ins', 'isindex', 'keygen', 'kbd', 'label', 'layer', 'legend', 'li', 'limittext', 'link', 'listing', 'map', 'marquee', 'menu', 'meta', 'multicol', 'nobr', 'noembed', 'noframes', 'noscript', 'nosmartquotes', 'object', 'ol', 'optgroup', 'option', 'param', 'plaintext', 'pre', 'rt', 'ruby', 's', 'samp', 'script', 'select', 'server', 'shadow', 'sidebar', 'small', 'spacer', 'span', 'strike', 'strong', 'style', 'sub', 'sup', 'table', 'tbody', 'td', 'textarea', 'tfoot', 'th', 'thead', 'title', 'tr', 'tt', 'ul', 'var', 'wbr', 'xml', 'xmp', '!DOCTYPE', '!--');

		foreach ($html_tags as $tag)
		{
			// A tag is '<tagname ', so we need to add < and a space or '<tagname>'
			if (stristr($xss_check, '<' . $tag . ' ') || stristr($xss_check, '<' . $tag . '>'))
			{
				JError::raiseWarning(100, 'IE_XSS');

				return false;
			}
		}

		return true;
	}

	/**
	 * Sanitize the image file name and return an unique string
	 *
	 * @param   string  $base_Dir  the target directory
	 * @param   string  $filename  the unsanitized imagefile name
	 *
	 * @return string $filename the sanitized and unique image file name
	 */
	public static function sanitize($base_Dir, $filename)
	{
		jimport('joomla.filesystem.file');

		// Check for any leading/trailing dots and remove them (trailing shouldn't be possible cause of the getEXT check)
		$filename = preg_replace("/^[.]*/", '', $filename);

		// Shouldn't be necessary, see above
		$filename = preg_replace("/[.]*$/", '', $filename);

		// We need to save the last dot position cause preg_replace will also replace dots
		$lastdotpos = strrpos($filename, '.');

		// Replace invalid characters
		$chars    = '[^0-9a-zA-Z()_-]';
		$filename = strtolower(preg_replace("/$chars/", '_', $filename));

		// Get the parts before and after the dot (assuming we have an extension...check was done before)
		$beforedot = substr($filename, 0, $lastdotpos);
		$afterdot  = substr($filename, $lastdotpos + 1);

		// Make a unique filename for the image and check it is not already taken
		// If it is already taken keep trying till success
		$now = time();

		while (JFile::exists($base_Dir . $beforedot . '_' . $now . '.' . $afterdot))
		{
			$now++;
		}

		// Create out of the seperated parts the new filename
		$filename = $beforedot . '_' . $now . '.' . $afterdot;

		return $filename;
	}
}
