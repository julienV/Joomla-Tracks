<?php
/**
 * @package     Tracks
 * @subpackage  Library
 * @copyright   Tracks (C) 2008-2015 Julien Vonthron. All rights reserved.
 * @license     GNU General Public License version 2 or later
 */

defined('_JEXEC') or die;

/**
 * Class TrackslibHelperImageSelect
 *
 * @package  Tracks
 * @since    1.0
 */
abstract class TrackslibHelperImageSelect
{
	/**
	 * Get selector
	 *
	 * @param   string  $fieldname          field name
	 * @param   string  $fieldpreview_name  field preview name
	 * @param   string  $type               type
	 * @param   string  $value              value
	 *
	 * @return string
	 */
	public static function getSelector($fieldname, $fieldpreview_name, $type, $value)
	{
		$document = JFactory::getDocument();

		JHTML::_('behavior.modal');

		$baseFolder = JUri::root() . 'media/com_tracks/images/' . self::getfolder($type);

		// Build the image select functionality
		$js = "
    function selectImage(image, imagename, field) {
      $('a_' + field).value = image;
      $('a_' + field + '_name').value = imagename;
      $('a_' + field + '_name').fireEvent('change');
      $('sbox-window').close();
    }

    window.addEvent('domready', function() {
	    $('a_" . $fieldname . "_name').addEvent('change', function() {
        if ($('a_" . $fieldname . "_name').value!='') {
          $('" . $fieldpreview_name . "').src='" . $baseFolder . "/' + $('a_" . $fieldname . "_name').value;
        }
        else {
          $('" . $fieldpreview_name . "').src='../images/blank.png';
        }
      });
      $('a_" . $fieldname . "_name').fireEvent('change');
    });
    ";

		$link  = 'index.php?option=com_tracks&amp;view=imagehandler&amp;layout=upload&amp;type=' . $type . '&amp;field=' . $fieldname . '&amp;tmpl=component';
		$link2 = 'index.php?option=com_tracks&amp;view=imagehandler&amp;type=' . $type . '&amp;field=' . $fieldname . '&amp;tmpl=component';
		$document->addScriptDeclaration($js);

		JHTML::_('behavior.modal', 'a.modal');

		$imageselect = "\n<input style=\"background: #ffffff;\" type=\"text\" id=\"a_" . $fieldname . "_name\" value=\"$value\" disabled=\"disabled\" />";
		$imageselect .= "<div class=\"button2-left\"><div class=\"blank\"><a class=\"modal\" title=\"" . JText::_('COM_TRACKS_Upload') . "\" href=\"$link\" rel=\"{handler: 'iframe', size: {x: 650, y: 375}}\">" . JText::_('COM_TRACKS_Upload') . "</a></div></div>\n";
		$imageselect .= "<div class=\"button2-left\"><div class=\"blank\"><a class=\"modal\" title=\"" . JText::_('COM_TRACKS_SELECTIMAGE') . "\" href=\"$link2\" rel=\"{handler: 'iframe', size: {x: 650, y: 375}}\">" . JText::_('COM_TRACKS_SELECTIMAGE') . "</a></div></div>\n";
		$imageselect .= "<div class=\"button2-left\"><div class=\"blank\"><a title=\"" . JText::_('COM_TRACKS_SELECTIMAGE') . "\" href=\"#\" onclick=\"selectImage('', '" . JText::_('COM_TRACKS_SELECTIMAGE') . "', '" . $fieldname . "' );\">" . JText::_('COM_TRACKS_RESET') . "</a></div></div>\n";
		$imageselect .= "\n<input type=\"hidden\" id=\"a_" . $fieldname . "\" name=\"" . $fieldname . "\" value=\"$value\" />";

		return $imageselect;
	}

	/**
	 * Get folder
	 *
	 * @param   string  $type  type
	 *
	 * @return string
	 */
	private static function getfolder($type)
	{
		switch ($type)
		{
			case "individuals":
				return "individuals";
				break;
			case "individuals_small":
				return "individuals" . '/' . "small";
				break;
			case "teams":
				return "teams";
				break;
			default:
				return "";
		}
	}

	/**
	 * Check
	 *
	 * @param   string  $file  file path
	 *
	 * @return bool
	 */
	public static function check($file)
	{
		jimport('joomla.filesystem.file');

		$params = JComponentHelper::getParams('com_tracks');

		$sizelimit = $params->get('image_max_size', 120) * 1024;
		$imagesize = $file['size'];

		// Check if the upload is an image...getimagesize will return false if not
		if (!getimagesize($file['tmp_name']))
		{
			JError::raiseWarning(100, JText::_('COM_TRACKS_UPLOAD_FAILED_NOT_AN_IMAGE') . ': ' . htmlspecialchars($file['name'], ENT_COMPAT, 'UTF-8'));

			return false;
		}

		// Check if the imagefiletype is valid
		$fileext = strtolower(JFile::getExt($file['name']));

		$allowable = array('gif', 'jpg', 'png');

		if (!in_array($fileext, $allowable))
		{
			JError::raiseWarning(100, JText::_('COM_TRACKS_WRONG_IMAGE_FILE_TYPE') . ': ' . htmlspecialchars($file['name'], ENT_COMPAT, 'UTF-8'));

			return false;
		}

		// Check filesize
		if ($imagesize > $sizelimit)
		{
			JError::raiseWarning(100, JText::_('COM_TRACKS_IMAGE_FILE_SIZE') . ': ' . htmlspecialchars($file['name'], ENT_COMPAT, 'UTF-8'));

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
				JError::raiseWarning(100, JText::_('COM_TRACKS_WARN_IE_XSS'));

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
	private static function sanitize($base_Dir, $filename)
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
