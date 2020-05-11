<?php
/**
 * @package    Tracks.Library
 * @copyright  Tracks (C) 2008-2020 Julien Vonthron. All rights reserved.
 * @license    GNU General Public License version 2 or later
 */

namespace Tracks\Layout;

defined('JPATH_PLATFORM') or die;

use Joomla\CMS\Application\ApplicationHelper;
use Joomla\CMS\Component\ComponentHelper;

/**
 * Base class for rendering a display layout
 * loaded from from a layout file
 *
 * @link   https://docs.joomla.org/Special:MyLanguage/Sharing_layouts_across_views_or_extensions_with_JLayout
 * @since  3.0
 */
class FileLayout extends \Joomla\CMS\Layout\FileLayout
{
	/**
	 * Get the default array of include paths
	 *
	 * @return  array
	 *
	 * @since   3.5
	 */
	public function getDefaultIncludePaths()
	{
		// Reset includePaths
		$paths = array();

		// (1 - highest priority) Received a custom high priority path
		if ($this->basePath !== null)
		{
			$paths[] = rtrim($this->basePath, DIRECTORY_SEPARATOR);
		}

		// Component layouts & overrides if exist
		$component = 'com_tracks';

		// (2) Component template overrides path
		$paths[] = JPATH_THEMES . '/' . \JFactory::getApplication()->getTemplate() . '/html/layouts/' . $component;

		// (3) Component path
		if ($this->options->get('client') == 0)
		{
			$paths[] = JPATH_SITE . '/components/' . $component . '/layouts';
		}
		else
		{
			$paths[] = JPATH_ADMINISTRATOR . '/components/' . $component . '/layouts';
		}

		// (5) Tracks library
		$paths[] = JPATH_SITE . '/libraries/tracks/layouts';

		// (6) Standard Joomla! layouts overriden
		$paths[] = JPATH_THEMES . '/' . \JFactory::getApplication()->getTemplate() . '/html/layouts';

		// (7 - lower priority) Frontend base layouts
		$paths[] = JPATH_ROOT . '/layouts';

		return $paths;
	}
}
