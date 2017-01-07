<?php
/**
 * @package     Tracks
 * @subpackage  Admin
 * @copyright   Tracks (C) 2008-2015 Julien Vonthron. All rights reserved.
 * @license     GNU General Public License version 2 or later
 */

defined('_JEXEC') or die();

// Include library dependencies
jimport('joomla.filter.input');

/**
 * Competitions Table class
 *
 * @package  Tracks
 * @since    0.1
 */
class TableProjectsetting extends TrackslibTable
{
	/**
	 * The name of the table
	 *
	 * @var string
	 */
	protected $_tableName = 'tracks_project_settings';

	/**
	 * Method to bind an associative array or object to the JTable instance.This
	 * method only binds properties that are publicly accessible and optionally
	 * takes an array of properties to ignore when binding.
	 *
	 * @param   mixed  $src     An associative array or object to bind to the JTable instance.
	 * @param   mixed  $ignore  An optional array or space separated list of properties to ignore while binding.
	 *
	 * @return  boolean  True on success.
	 *
	 * @throws  InvalidArgumentException
	 */
	public function bind($src, $ignore = array())
	{
		if (key_exists('settings', $src) && is_array($src['settings']))
		{
			$registry = new JRegistry;
			$registry->loadArray($src['settings']);
			$array['settings'] = $registry->toString();
		}

		return parent::bind($src, $ignore);
	}
}
