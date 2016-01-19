<?php
/**
 * @package     Tracks
 * @subpackage  Library
 * @copyright   Tracks (C) 2008-2015 Julien Vonthron. All rights reserved.
 * @license     GNU General Public License version 2 or later
 */

defined('_JEXEC') or die;

JFormHelper::loadFieldClass('list');

/**
 * Tracks sport Field
 *
 * @package     Tracks
 * @subpackage  Library
 * @since       3.0
 */
class TracksFormFieldSport extends JFormFieldList
{
	/**
	 * The form field type.
	 *
	 * @var  string
	 */
	public $type = 'sport';

	/**
	 * Method to get the field options.
	 *
	 * @return  array  The field option objects.
	 */
	protected function getOptions()
	{
		$options = array();

		$folders = JFolder::folders(JPATH_SITE . '/components/com_tracks/sports');


		foreach ($folders as $folder)
		{
			$options[] = array('value' => $folder, 'text' => $folder);
		}

		return array_merge(parent::getOptions(), $options);
	}
}
