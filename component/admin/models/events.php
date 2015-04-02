<?php
/**
 * @package     Tracks
 * @subpackage  Admin
 * @copyright   Tracks (C) 2008-2015 Julien Vonthron. All rights reserved.
 * @license     GNU General Public License version 2 or later
 */

defined('_JEXEC') or die();

/**
 * Tracks Component events Model
 *
 * @package     Tracks
 * @subpackage  Admin
 * @since       3.0
 */
class TracksModelEvents extends TrackslibModelList
{
	/**
	 * Name of the filter form to load
	 *
	 * @var  string
	 */
	protected $filterFormName = 'filter_events';

	/**
	 * Limitstart field used by the pagination
	 *
	 * @var  string
	 */
	protected $limitField = 'events_limit';

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
			);
		}

		parent::__construct($config);
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
	 * Method to auto-populate the model state.
	 *
	 * This method should only be called once per instantiation and is designed
	 * to be called on the first call to the getState() method unless the model
	 * configuration flag to ignore the request is set.
	 *
	 * Note. Calling getState in this method will result in recursion.
	 *
	 * @param   string  $ordering   An optional ordering field.
	 * @param   string  $direction  An optional direction (asc|desc).
	 *
	 * @return  void
	 *
	 * @throws RuntimeException
	 */
	protected function populateState($ordering = null, $direction = null)
	{
		$prid = JFactory::getApplication()->getUserStateFromRequest($this->context . '.projectround_id', 'projectround_id', 0, 'int');

		if (!$prid)
		{
			throw new RuntimeException('projectround id is required');
		}

		$this->setState('projectround_id', $prid);

		return parent::populateState($ordering, $direction);
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
					'e.name',
				)
			)
		);
		$query->from($db->qn('#__tracks_events', 'obj'));
		$query->join('INNER', $db->qn('#__tracks_eventtypes', 'e') . ' ON e.id = obj.type');
		$query->where($db->qn('projectround_id') . ' = ' . $this->getState('projectround_id'));

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
				$query->where('obj.name LIKE ' . $search);
			}
		}

		// Add the list ordering clause.
		$query->order($db->escape($this->getState('list.ordering', 'obj.ordering')) . ' ' . $db->escape($this->getState('list.direction', 'ASC')));

		return $query;
	}
}
