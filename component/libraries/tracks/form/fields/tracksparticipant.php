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
 * Tracks participant Field
 *
 * @package     Tracks
 * @subpackage  Library
 * @since       3.0
 */
class JFormFieldTracksParticipant extends JFormFieldList
{
	/**
	 * The form field type.
	 *
	 * @var  string
	 */
	public $type = 'tracksparticipant';

	/**
	 * A static cache.
	 *
	 * @var  array
	 */
	protected $cache = array();

	/**
	 * Method to get the field options.
	 *
	 * @return  array  The field option objects.
	 */
	protected function getOptions()
	{
		$options = array();

		// Filter by state
		$state = $this->element['state'] ? (int) $this->element['state'] : null;

		// Get the forms
		$items = $this->getFields($state);

		// Build the field options
		if (!empty($items))
		{
			foreach ($items as $item)
			{
				$options[] = JHtml::_('select.option', $item->value, $item->text);
			}
		}

		return array_merge(parent::getOptions(), $options);
	}

	/**
	 * Method to get the list of forms.
	 *
	 * @param   integer  $state  The companies state
	 *
	 * @return  array  An array of company names.
	 */
	protected function getFields($state = null)
	{
		if (empty($this->cache))
		{
			$event_id = JFactory::getApplication()->input->get('event_id');

			$db = JFactory::getDbo();

			$query = $db->getQuery(true)
				->select(array($db->qn('i.id', 'value'), 'CONCAT_WS(", ", ' . $db->qn('i.last_name') . ', ' . $db->qn('i.first_name') . ') AS text'))
				->from($db->qn('#__tracks_participants', 'pa'))
				->join('INNER', $db->qn('#__tracks_individuals', 'i') . ' ON i.id = pa.individual_id')
				->order('i.last_name ASC, i.first_name ASC');

			$db->setQuery($query);

			$result = $db->loadObjectList();

			if (is_array($result))
			{
				$this->cache = $result;
			}
		}

		return $this->cache;
	}
}
