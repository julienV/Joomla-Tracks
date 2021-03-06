<?php
/**
 * @package     Tracks
 * @subpackage  Admin
 * @copyright   Tracks (C) 2008-2015 Julien Vonthron. All rights reserved.
 * @license     GNU General Public License version 2 or later
 */

defined('_JEXEC') or die();

/**
 * Tracks Component participants Model
 *
 * @package     Tracks
 * @subpackage  Admin
 * @since       3.0
 */
class TracksModelParticipants extends TrackslibModelList
{
	/**
	 * Name of the filter form to load
	 *
	 * @var  string
	 */
	protected $filterFormName = 'filter_participants';

	/**
	 * Limitstart field used by the pagination
	 *
	 * @var  string
	 */
	protected $limitField = 'participants_limit';

	/**
	 * Limitstart field used by the pagination
	 *
	 * @var  string
	 */
	protected $limitstartField = 'auto';

	/**
	 * Constructor.
	 *
	 * @param   array  $config  Configs
	 *
	 * @see     JController
	 */
	public function __construct($config = array())
	{
		if (empty($config['filter_fields']))
		{
			$config['filter_fields'] = array(
				'name', 'obj.name',
				'id', 'obj.id',
				'ordering', 'obj.ordering',
				'i.last_name', 'i.first_name', 'i.nickname',
				'obj.number', 't.name',
			);
		}

		parent::__construct($config);
	}

	/**
	 * Add a participant to all rounds
	 *
	 * @param   int  $participantId  participant id
	 *
	 * @return void
	 */
	public function addToAllRounds($participantId)
	{
		$db    = JFactory::getDbo();
		$query = $db->getQuery(true)
			->select('DISTINCT e.id AS event_id, p.individual_id, p.team_id, p.number')
			->from('#__tracks_participants AS p')
			->innerJoin('#__tracks_projects_rounds AS pr ON pr.project_id = p.project_id')
			->innerJoin('#__tracks_events AS e ON e.projectround_id = pr.id')
			->leftJoin('#__tracks_events_results AS rr ON rr.event_id = e.id AND rr.individual_id = p.individual_id AND rr.team_id = p.team_id')
			->where('p.id = ' . (int) $participantId)
			->where('rr.id IS NULL');

		$db->setQuery($query);

		if (!$res = $db->loadObjectList())
		{
			return;
		}

		foreach ($res as $row)
		{
			$table = $this->getTable('Eventresult', 'TracksTable');
			$table->individual_id = $row->individual_id;
			$table->team_id = $row->team_id;
			$table->number = $row->number;
			$table->event_id = $row->event_id;

			$table->store();
		}
	}

	/**
	 * Method to get a store id based on model configuration state.
	 *
	 * @param   string  $id  A prefix for the store id.
	 *
	 * @return  string       A store id.
	 */
	protected function getStoreId($id = '')
	{
		// Compile the store id.
		$id .= ':' . $this->getState('filter.search');

		return parent::getStoreId($id);
	}

	/**
	 * Build an SQL query to load the list data.
	 *
	 * @return  object  Query object
	 */
	protected function getListQuery()
	{
		// Create a new query object.
		$db    = $this->getDbo();
		$query = $db->getQuery(true);

		// Select the required fields from the table.
		$query->select(
			$this->getState(
				'list.select',
				array(
					'obj.*',
					'i.last_name, i.first_name', 'i.nickname',
					't.name AS team'
				)
			)
		);
		$query->from($db->qn('#__tracks_participants', 'obj'));
		$query->join('INNER', $db->qn('#__tracks_individuals', 'i') . ' ON ' . $db->qn('obj.individual_id') . ' = ' . $db->qn('i.id'));
		$query->join('LEFT', $db->qn('#__tracks_teams', 't') . ' ON ' . $db->qn('obj.team_id') . ' = ' . $db->qn('t.id'));
		$query->where($db->qn('obj.project_id') . ' = ' . TrackslibHelperTools::getCurrentProjectId());

		// Filter: like / search
		$search = $this->getState('filter.search', '');

		if (!empty($search))
		{
			if (stripos($search, 'id:') === 0)
			{
				$query->where('obj.id = ' . (int) substr($search, 3));
			}
			else
			{
				$search = $db->Quote('%' . $db->escape($search, true) . '%');
				$query->where('CONCAT(i.last_name, i.first_name) LIKE ' . $search);
			}
		}

		// Add the list ordering clause.
		$query->order($db->escape($this->getState('list.ordering', 'i.last_name')) . ' ' . $db->escape($this->getState('list.direction', 'ASC')));

		return $query;
	}

	/**
	 * Method to auto-populate the model state.
	 *
	 * @param   string  $ordering   An optional ordering field.
	 * @param   string  $direction  An optional direction (asc|desc).
	 *
	 * @return  void
	 *
	 * @since   1.0.0
	 */
	protected function populateState($ordering = null, $direction = null)
	{
		return parent::populateState($ordering ?: 'i.last_name', $direction ?: 'ASC');
	}
}
