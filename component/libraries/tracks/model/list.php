<?php
/**
 * @package     Tracks
 * @subpackage  Library
 * @copyright   Tracks (C) 2008-2015 Julien Vonthron. All rights reserved.
 * @license     GNU General Public License version 2 or later
 */

use Joomla\String\Inflector;

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

	/**
	 * Search
	 *
	 * @param   array  $state  state
	 *
	 * @return array
	 */
	public function search($state)
	{
		foreach ($state as $key => $value)
		{
			$this->setState($key, $value);
		}

		return $this->getItems();
	}

	/**
	 * Search and return as entities
	 *
	 * @param   array  $state  state
	 *
	 * @return array
	 * @throws Exception
	 */
	public function searchEntities($state)
	{
		$items = $this->search($state);

		if (empty($items))
		{
			return $items;
		}

		$entities    = [];
		$inflector   = Inflector::getInstance();
		$entityClass = 'TrackslibEntity' . ucfirst($inflector->toSingular($this->getName()));

		foreach ($items as $item)
		{
			$entity = $entityClass::getInstance($item->id);
			$entity->bind($item);

			$entities[] = $entity;
		}

		return $entities;
	}
}
