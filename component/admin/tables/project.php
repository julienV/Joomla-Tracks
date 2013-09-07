<?php
/**
 * @version    $Id: project.php 67 2008-04-24 16:41:27Z julienv $
 * @package    JoomlaTracks
 * @copyright  Copyright (C) 2008 Julien Vonthron. All rights reserved.
 * @license    GNU/GPL, see LICENSE.php
 *             Joomla Tracks is free software. This version may have been modified pursuant
 *             to the GNU General Public License, and as distributed it includes or
 *             is derivative of works licensed under the GNU General Public License or
 *             other free or open source software licenses.
 *             See COPYRIGHT.php for copyright notices and details.
 */

// No direct access
defined('_JEXEC') or die('Restricted access');

// Include library dependencies
jimport('joomla.filter.input');

/**
 * Project Table class
 *
 * @package  Tracks
 * @since    0.1
 */
class TracksTableProject extends FOFTable
{
	/**
	 * Constructor
	 *
	 * @param object Database connector object
	 *
	 * @since 1.0
	 */
	public function __construct($table, $key, &$db)
	{
		parent::__construct('#__tracks_projects', 'id', $db);
	}

	function onAfterBind($src)
	{
		// If the source value is an object, get its accessible properties.
		if (is_object($src))
		{
			$src = get_object_vars($src);
		}

		if (array_key_exists('params', $src) && is_array($src['params']))
		{
			$registry = new JRegistry();
			$registry->loadArray($src['params']);
			$src['params'] = $registry->toString();
		}

		return true;
	}

	/**
	 * Overloaded check method to ensure data integrity
	 *
	 * @access public
	 * @return boolean True on success
	 * @since  1.0
	 */
	function check()
	{
		$result = true;
		if ($this->name == '')
		{
			$this->setError(JText::_('COM_TRACKS__Name_is_mandatory '));
			return false;
		}

		$alias = JFilterOutput::stringURLSafe($this->name);

		if (empty($this->alias) || $this->alias === $alias)
		{
			$this->alias = $alias;
		}

		if (!$this->season_id)
		{
			$this->setError(JText::_('COM_TRACKS__Season_is_mandatory '));
			return false;
		}

		if (!$this->competition_id)
		{
			$this->setError(JText::_('COM_TRACKS__Competition_is_mandatory '));
			return false;
		}
		return true;
	}


	/**
	 * Fires after loading a record, automatically unserialises the extra params
	 *
	 * @param   boolean  $result  was it loaded correctly ?
	 *
	 * @return bool
	 */
	protected function onAfterLoad($result)
	{
		parent::onAfterLoad($result);

		if (is_string($this->params))
		{
			$registry = new JRegistry();
			$registry->loadString($this->params);
			$this->params = $registry->toArray();
		}

		return $result;
	}

	/**
	 * The event which runs before storing (saving) data to the database
	 *
	 * @param   boolean  $updateNulls  Should nulls be saved as nulls (true) or just skipped over (false)?
	 *
	 * @return  boolean  True to allow saving
	 */
	protected function onBeforeStore($updateNulls)
	{
		$res = parent::onBeforeStore($updateNulls);

		if (!$res)
		{
			return $res;
		}

		if ($this->params && is_array($this->params))
		{
			$registry = new JRegistry();
			$registry->loadArray($this->params);
			$this->params = $registry->toString();
		}

		return true;
	}
}
