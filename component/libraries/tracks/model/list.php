<?php
/**
 * @package     Tracks
 * @subpackage  Library
 * @copyright   Tracks (C) 2008-2015 Julien Vonthron. All rights reserved.
 * @license     GNU General Public License version 2 or later
 */

defined('_JEXEC') or die;

/**
 * Tools helper
 *
 * @package     Tracks
 * @subpackage  Library
 * @since       3.0
 */
abstract class TrackslibModelList extends RModelList
{
	/**
	 * Get the project switch form
	 *
	 * @param   array    $data      data
	 * @param   boolean  $loadData  load current data
	 *
	 * @return  JForm/false  the JForm object or false
	 */
	public function getProjectSwitchForm($data = array(), $loadData = true)
	{
		// Get the form.
		$form = $this->loadForm(
			'projectswitch',
			'projectswitch',
			array('control' => '', 'load_data' => $loadData)
		);

		$current = JFactory::getApplication()->getUserState('currentproject', 0);
		$form->bind(array('currentproject' => $current));

		return $form;
	}
}
