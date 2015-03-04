<?php
/**
 * @version    $Id$
 * @package    JLVImgTool
 * @copyright	Copyright (C) 2012 Julien Vonthron. All rights reserved.
 * @license		GNU/GPL, see LICENSE.php
 * JLVImgTool is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * See COPYRIGHT.php for copyright notices and details.
 *
 * to be used in joomla !
 *
 * Based on image class from eventlist by Christoph Lukes from schlu.net
 */

defined('_JEXEC') or die('Restricted access');

jimport('joomla.filesystem.folder');

class JLVImageTool {

	/**
	 * Creates a Thumbnail of an image
	 *
	 * @author Christoph Lukes
	 * @since 0.9
	 *
	 * @param string $file The path to the file
	 * @param string $save The targetpath
	 * @param string $width The with of the image
	 * @param string $height The height of the image
	 * @return true when success
	 */
	public static function thumb($file, $save, $width, $height)
	{
		//GD-Lib > 2.0 only!
		@unlink($save);

		if (!file_exists(dirname($save))) {
			JFolder::create(dirname($save));
		}

		//get sizes else stop
		if (!$infos = @getimagesize($file)) {
			return false;
		}

		// keep proportions
		$iWidth = $infos[0];
		$iHeight = $infos[1];
		$iRatioW = $width / $iWidth;
		$iRatioH = $height / $iHeight;

		if ($iRatioW < $iRatioH) {
			$iNewW = $iWidth * $iRatioW;
			$iNewH = $iHeight * $iRatioW;
		} else {
			$iNewW = $iWidth * $iRatioH;
			$iNewH = $iHeight * $iRatioH;
		}

		//Don't resize images which are smaller than thumbs
		if ($infos[0] < $width && $infos[1] < $height) {
			$iNewW = $infos[0];
			$iNewH = $infos[1];
		}

		if($infos[2] == 1) {
			/*
				* Image is typ gif
			*/
			$imgA = imagecreatefromgif($file);
			$imgB = imagecreate($iNewW,$iNewH);

			//keep gif transparent color if possible
			if(function_exists('imagecolorsforindex') && function_exists('imagecolortransparent')) {
				$transcolorindex = imagecolortransparent($imgA);
				//transparent color exists
				if($transcolorindex >= 0 ) {
					$transcolor = imagecolorsforindex($imgA, $transcolorindex);
					$transcolorindex = imagecolorallocate($imgB, $transcolor['red'], $transcolor['green'], $transcolor['blue']);
					imagefill($imgB, 0, 0, $transcolorindex);
					imagecolortransparent($imgB, $transcolorindex);
					//fill white
				} else {
					$whitecolorindex = @imagecolorallocate($imgB, 255, 255, 255);
					imagefill($imgB, 0, 0, $whitecolorindex);
				}
				//fill white
			} else {
				$whitecolorindex = imagecolorallocate($imgB, 255, 255, 255);
				imagefill($imgB, 0, 0, $whitecolorindex);
			}
			imagecopyresampled($imgB, $imgA, 0, 0, 0, 0, $iNewW, $iNewH, $infos[0], $infos[1]);
			imagegif($imgB, $save);

		} elseif($infos[2] == 2) {
			/*
				* Image is typ jpg
			*/
			$imgA = imagecreatefromjpeg($file);
			$imgB = imagecreatetruecolor($iNewW,$iNewH);
			imagecopyresampled($imgB, $imgA, 0, 0, 0, 0, $iNewW, $iNewH, $infos[0], $infos[1]);
			imagejpeg($imgB, $save);

		} elseif($infos[2] == 3) {
			/*
				* Image is typ png
			*/
			$imgA = imagecreatefrompng($file);
			$imgB = imagecreatetruecolor($iNewW, $iNewH);
			imagealphablending($imgB, false);
			imagecopyresampled($imgB, $imgA, 0, 0, 0, 0, $iNewW, $iNewH, $infos[0], $infos[1]);
			imagesavealpha($imgB, true);
			imagepng($imgB, $save);
		} else {
			return false;
		}
		return true;
	}

	/**
	 * Determine the GD version
	 * Code from php.net
	 *
	 * @since 0.9
	 * @param int
	 *
	 * @return int
	 */
	public static function gdVersion($user_ver = 0)
	{
		if (! extension_loaded('gd')) {
			return;
		}
		static $gd_ver = 0;

		// Just accept the specified setting if it's 1.
		if ($user_ver == 1) {
			$gd_ver = 1;
			return 1;
		}
		// Use the static variable if function was called previously.
		if ($user_ver !=2 && $gd_ver > 0 ) {
			return $gd_ver;
		}
		// Use the gd_info() function if possible.
		if (function_exists('gd_info')) {
			$ver_info = gd_info();
			preg_match('/\d/', $ver_info['GD Version'], $match);
			$gd_ver = $match[0];
			return $match[0];
		}
		// If phpinfo() is disabled use a specified / fail-safe choice...
		if (preg_match('/phpinfo/', ini_get('disable_functions'))) {
			if ($user_ver == 2) {
				$gd_ver = 2;
				return 2;
			} else {
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
	 * returns the hml code for modal display of image
	 * If thumbnails exits, display the thumbnail with a modal link,
	 * otherwise, just display the full size picture
	 *
	 * @param string image_path full image path, or relative to joomla root
	 * @param string alt attribute
	 * @param string int maximum dimension in pixels
	 * @param array other attributes
	 */
	public static function modalimage($image_path, $alt, $maxdim, $attribs = array())
	{
		jimport('joomla.filesystem.file');
		$app = JFactory::getApplication();
		$base = $app->isAdmin() ? $app->getSiteURL() : JURI::base();

		if (empty($image_path) || !file_exists($image_path)) {
			return false;
		}

		// make path relative to joomla root
		$image_dir_rel = str_replace(JPATH_SITE, '', dirname($image_path));
		if ($image_dir_rel[0] == '/' || $image_dir_rel[0] == '\\') {
			$image_dir_rel = substr($image_dir_rel, 1);
		}
		$image_dir_rel_path = $base.str_replace("\\", "/", $image_dir_rel);

		$thumb_path = self::getThumbUrl($image_path, $maxdim);

		JHTML::_('behavior.modal', 'a.imodal');
		if (isset($attribs['class'])) {
			$attribs['class'] .= ' imodal';
		}
		else {
			$attribs['class'] = 'imodal';
		}

		$thumb = JHTML::image($thumb_path, $alt, $attribs);
		$html = JHTML::link($image_dir_rel_path.'/'.basename($image_path), $thumb, $attribs);

		return $html;
	}

	/**
	 * return full url to thumbnail
	 *
	 * @param string type, must be one of 'events', 'venues', etc...
	 * @param string image_path full image path, or relative to joomla root
	 * @return url or false if it doesn't exists
	 */
	public static function getThumbUrl($image_path, $maxdim)
	{
		jimport('joomla.filesystem.file');
		$app = JFactory::getApplication();
		$base = $app->isAdmin() ? $app->getSiteURL() : JURI::base();

		if (!JFile::exists($image_path)) {
			throw new Exception('Image not found');
			return false;
		}

		// make path relative to joomla root
		$image_dir_rel = str_replace(JPATH_SITE, '', dirname($image_path));
		if ($image_dir_rel[0] == '/' || $image_dir_rel[0] == '\\') {
			$image_dir_rel = substr($image_dir_rel, 1);
		}
		$image_dir_rel_path = $base.str_replace("\\", "/", $image_dir_rel);

		if ($maxdim)
		{
			$width  = $maxdim;
			$height = $maxdim;
		}
		else
		{
			return false;
		}
		$img_dir = dirname($image_path);
		$img_name = basename($image_path);


		$thumb_name = md5($img_name).$width.'_'.$height.'.png';
		$thumb_path = $img_dir. '/' .'small'. '/' .$thumb_name;

		//		echo JPATH_SITE. '/' .'images'. '/' .'redevent'. '/' .$folder. '/' .'small'. '/' .$image;
		if (JFile::exists($thumb_path))
		{
			return $image_dir_rel_path.'/small/'.$thumb_name;
		}
		else
		{
			//try to generate the thumb
			if (self::thumb($image_path, $thumb_path, $width, $height)) {
				return $image_dir_rel_path.'/small/'.$thumb_name;
			}
		}
		return false;
	}


	public static function check($file, $settings)
	{
		jimport('joomla.filesystem.file');

		$imagesize 	= $file['size'];

		//check if the upload is an image...getimagesize will return false if not
		if (!getimagesize($file['tmp_name'])) {
			JError::raiseWarning(100, 'NOT_AN_IMAGE'.': '.htmlspecialchars($file['name'], ENT_COMPAT, 'UTF-8'));
			return false;
		}

		//check if the imagefiletype is valid
		$fileext 	= strtolower(JFile::getExt($file['name']));

		$allowable 	= array ('gif', 'jpg', 'png');
		if (!in_array($fileext, $allowable)) {
			JError::raiseWarning(100, 'WRONG_IMAGE_FILE_TYPE'.': '.htmlspecialchars($file['name'], ENT_COMPAT, 'UTF-8'));
			return false;
		}

		//Check filesize
// 		if ($imagesize > $sizelimit) {
// 			JError::raiseWarning(100, 'IMAGE_FILE_SIZE'.': '.htmlspecialchars($file['name'], ENT_COMPAT, 'UTF-8'));
// 			return false;
// 		}

		//XSS check
		$xss_check =  JFile::read($file['tmp_name'],false,256);
		$html_tags = array('abbr','acronym','address','applet','area','audioscope','base','basefont','bdo','bgsound','big','blackface','blink','blockquote','body','bq','br','button','caption','center','cite','code','col','colgroup','comment','custom','dd','del','dfn','dir','div','dl','dt','em','embed','fieldset','fn','font','form','frame','frameset','h1','h2','h3','h4','h5','h6','head','hr','html','iframe','ilayer','img','input','ins','isindex','keygen','kbd','label','layer','legend','li','limittext','link','listing','map','marquee','menu','meta','multicol','nobr','noembed','noframes','noscript','nosmartquotes','object','ol','optgroup','option','param','plaintext','pre','rt','ruby','s','samp','script','select','server','shadow','sidebar','small','spacer','span','strike','strong','style','sub','sup','table','tbody','td','textarea','tfoot','th','thead','title','tr','tt','ul','var','wbr','xml','xmp','!DOCTYPE', '!--');
		foreach($html_tags as $tag) {
			// A tag is '<tagname ', so we need to add < and a space or '<tagname>'
			if(stristr($xss_check, '<'.$tag.' ') || stristr($xss_check, '<'.$tag.'>')) {
				RedeventError::raiseWarning(100, 'IE_XSS');
				return false;
			}
		}

		return true;
	}

}
