<?php
/**
 * @package     Tracks
 * @subpackage  Admin
 * @copyright   Tracks (C) 2008-2015 Julien Vonthron. All rights reserved.
 * @license     GNU General Public License version 2 or later
 */

defined('_JEXEC') or die();

/**
 * Tracks Component eventtype Model
 *
 * @package     Tracks
 * @subpackage  Admin
 * @since       3.0
 */
class TracksModelEventtypes extends TrackslibModelList
{
	/**
	 * Name of the filter form to load
	 *
	 * @var  string
	 */
	protected $filterFormName = 'filter_eventtypes';

	/**
	 * Limitstart field used by the pagination
	 *
	 * @var  string
	 */
	protected $limitField = 'eventtypes_limit';

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
	 * Method to toggle enable_stats
	 *
	 * @param   array  $cid      ids to modify
	 * @param   int    $enabled  set state on or off
	 *
	 * @return    boolean    True on success
	 */
	public function setStatsState($cid = array(), $enabled = 1)
	{
		if (count($cid))
		{
			$cids = implode(',', $cid);

			$query = $this->_db->getQuery(true);

			$query->update('#__tracks_eventtypes')
				->set('enable_stats = ' . (int) $enabled)
				->where('id IN (' . $cids . ')');

			$this->_db->setQuery($query);
			$this->_db->execute();
		}

		return true;
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
			$this->getState('list.select',
				array(
					'obj.*',
				)
			)
		);
		$query->from($db->qn('#__tracks_eventtypes', 'obj'));

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
		$query->order($db->escape($this->getState('list.ordering', 'obj.name')) . ' ' . $db->escape($this->getState('list.direction', 'ASC')));

		return $query;
	}
}
