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
class JFormFieldTracksParticipantteam extends JFormFieldList
{
	/**
	 * The form field type.
	 *
	 * @var  string
	 */
	public $type = 'tracksparticipantteam';

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
			$eventId = JFactory::getApplication()->input->getInt('event_id');

			$db = JFactory::getDbo();

			$query = $db->getQuery(true)
				->select(array($db->qn('t.id', 'value'), $db->qn('t.name', 'text')))
				->from($db->qn('#__tracks_teams', 't'))
				->join('INNER', $db->qn('#__tracks_participants', 'pa') . ' ON pa.team_id = t.id')
				->join('INNER', $db->qn('#__tracks_projects_rounds', 'pr') . ' ON pr.project_id = pa.project_id')
				->join('INNER', $db->qn('#__tracks_events', 'e') . ' ON e.projectround_id = pr.id')
				->where('e.id = ' . $eventId)
				->order('t.name ASC')
				->group('t.id');

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
