<?php
/**
 * @package     Tracks
 * @subpackage  Admin
 * @copyright   Tracks (C) 2008-2015 Julien Vonthron. All rights reserved.
 * @license     GNU General Public License version 2 or later
 */

defined('_JEXEC') or die();

/**
 * Tracks Component projects Model
 *
 * @package     Tracks
 * @subpackage  Admin
 * @since       3.0
 */
class TracksModelProjectrounds extends TrackslibModelList
{
	/**
	 * Name of the filter form to load
	 *
	 * @var  string
	 */
	protected $filterFormName = 'filter_projectrounds';

	/**
	 * Limitstart field used by the pagination
	 *
	 * @var  string
	 */
	protected $limitField = 'projectrounds_limit';

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
	 */
	public function __construct($config = array())
	{
		if (empty($config['filter_fields']))
		{
			$config['filter_fields'] = array(
				'name', 'r.name',
				'start_date', 'obj.start_date',
				'ordering', 'obj.ordering',
				// For filters
				'published',
			);
		}

		parent::__construct($config);
	}

	/**
	 * Return Breadcrumbs
	 *
	 * @return array
	 */
	public function getBreadcrumbs()
	{
		$query = $this->_db->getQuery(true);

		$query->select('id, name')
			->from('#__tracks_projects')
			->where('id = ' . $this->getState('project_id'));

		$this->_db->setQuery($query);
		$res = $this->_db->loadObject();

		return array($res->name => false);
	}

	/**
	 * Method to store item(s)
	 *
	 * @param   array  $cids        the project round ids to copy
	 * @param   int    $project_id  the destination project id
	 *
	 * @return  boolean True on success
	 *
	 * @since   1.5
	 */
	public function assign($cids, $project_id)
	{
		$row = $this->getTable();

		for ($i = 0, $n = count($cids); $i < $n; $i++)
		{
			$cid =& $cids[$i];

			$query = 'SELECT *'
				. ' FROM #__tracks_projects_rounds '
				. ' WHERE id = ' . intval($cid);
			$this->_db->setQuery($query);
			$round = $this->_db->loadObject();

			if (!$round)
			{
				JError::raise(500, 'Round not found. ' . $this->_db->getErrorMsg());
			}

			if (!$round)
			{
				break;
			}

			$row->reset();
			$row->bind($round);
			$row->id               = null;
			$row->project_id       = $project_id;
			$row->checked_out      = 0;
			$row->checked_out_time = null;

			// Store the item to the database
			if (!$row->store())
			{
				$this->setError($this->_db->getErrorMsg());
				JError::raise(500, 'Failed to copy round. ' . $this->_db->getErrorMsg());
			}

			// Now copy subrounds
			$query = ' SELECT * '
				. ' FROM #__tracks_projects_subrounds'
				. ' WHERE projectround_id = ' . $cid;
			$this->_db->setQuery($query);
			$subrounds = $this->_db->loadObjectList();

			if (is_array($subrounds))
			{
				$subround = $this->getTable('Subround');

				foreach ($subrounds AS $s)
				{
					$subround->reset();
					$subround->bind($s);
					$subround->id               = null;
					$subround->projectround_id  = $row->id;
					$subround->checked_out      = 0;
					$subround->checked_out_time = null;

					if (!$subround->store())
					{
						$this->setError($this->_db->getErrorMsg());
						JError::raise(500, 'Failed to copy subround. ' . $this->_db->getErrorMsg());
					}
				}
			}
		}

		return true;
	}

	/**
	 * Method to auto-populate the model state.
	 *
	 * @param   string  $ordering   An optional ordering field.
	 * @param   string  $direction  An optional direction (asc|desc).
	 *
	 * @return  void
	 */
	protected function populateState($ordering = 'ordering', $direction = 'asc')
	{
		$this->setState('project_id', TrackslibHelperTools::getCurrentProjectId());

		parent::populateState($ordering, $direction);
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
		$id .= ':' . $this->getState('filter.published');

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
		$db    = $this->_db;
		$query = $db->getQuery(true);

		// Select the required fields from the table.
		$query->select(
			$this->getState(
				'list.select',
				array(
					'obj.*',
					'r.name as name',
				)
			)
		);
		$query->from($db->qn('#__tracks_projects_rounds', 'obj'));
		$query->join('inner', '#__tracks_rounds AS r on r.id = obj.round_id');

		if ($this->getState('project_id'))
		{
			$query->where($db->qn('obj.project_id') . ' = ' . (int) $this->getState('project_id'));
		}

		if ($this->getState('filter.round_id'))
		{
			$query->where($db->qn('obj.round_id') . ' = ' . (int) $this->getState('filter.round_id'));
		}

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

				// Match on season and competition name too
				$or = array(
					'obj.name LIKE ' . $search,
					'r.name LIKE ' . $search,
				);
				$query->where('(' . implode(' OR ', $or) . ')');
			}
		}

		$state = $this->getState('filter.published');

		if (is_numeric($state))
		{
			$query->where($db->quoteName('obj.published') . ' = ' . $state);
		}

		// Add the list ordering clause.
		$query->order($db->escape($this->getState('list.ordering', 'obj.ordering')) . ' ' . $db->escape($this->getState('list.direction', 'ASC')));

		return $query;
	}
}
