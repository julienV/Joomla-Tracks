<?php
/**
 * @package     Tracks
 * @subpackage  Admin
 * @copyright   Tracks (C) 2008-2015 Julien Vonthron. All rights reserved.
 * @license     GNU General Public License version 2 or later
 */

defined('_JEXEC') or die();

/**
 * Tracks Component Event Results Model
 *
 * @package     Tracks
 * @subpackage  Admin
 * @since       3.0
 */
class TracksModelEventResults extends RModelList
{
	/**
	 * Name of the filter form to load
	 *
	 * @var  string
	 */
	protected $filterFormName = 'filter_eventresults';

	/**
	 * Limitstart field used by the pagination
	 *
	 * @var  string
	 */
	protected $limitField = 'eventresults_limit';

	/**
	 * Limitstart field used by the pagination
	 *
	 * @var  string
	 */
	protected $limitstartField = 'auto';

	/**
	 * @var int
	 */
	private $project_id;

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
				'number', 'participant',
				'team', 'rank',
				'performance', 'bonus_points',
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

		$query->select('p.id as project_id, p.name AS project_name')
			->select('pr.id AS projectround_id, r.name AS projectround_name')
			->select('e.id AS event_id, et.name AS event_name')
			->from('#__tracks_events AS e')
			->join('INNER', '#__tracks_eventtypes AS et ON et.id = e.type')
			->join('INNER', '#__tracks_projects_rounds AS pr ON pr.id = e.projectround_id')
			->join('INNER', '#__tracks_rounds AS r ON r.id = pr.round_id')
			->join('INNER', '#__tracks_projects AS p ON p.id = pr.project_id')
			->where('e.id = ' . $this->getState('event_id'));

		$this->_db->setQuery($query);
		$res = $this->_db->loadObject();

		return array(
			$res->project_name => JRoute::_('index.php?option=com_tracks&view=projectrounds'),
			$res->projectround_name => JRoute::_('index.php?option=com_tracks&view=events&projectround_id=' . $res->projectround_id),
			$res->event_name => false
		);
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
		$db = $this->_db;

		// Current
		$event_id = $this->getState('event_id');

		$query = $db->getQuery(true);

		$query->select('rr.id, rr.individual_id, rr.team_id, rr.event_id, rr.rank');
		$query->select('rr.performance, rr.bonus_points, rr.comment');
		$query->select('CASE WHEN CHAR_LENGTH(rr.number) THEN rr.number ELSE pi.number END AS number');
		$query->select('pi.id AS piid');
		$query->select('i.first_name, i.last_name');
		$query->select('t.name AS team');

		$query->from('#__tracks_events_results AS rr');
		$query->join('inner', '#__tracks_events AS sr ON sr.id = rr.event_id');
		$query->join('inner', '#__tracks_projects_rounds AS pr ON pr.id = sr.projectround_id');
		$query->join('inner', '#__tracks_individuals AS i ON i.id = rr.individual_id');
		$query->join('inner', '#__tracks_participants AS pi ON (pi.individual_id = rr.individual_id AND pr.project_id = pi.project_id )');
		$query->join('left', '#__tracks_teams AS t ON t.id = pi.team_id');
		$query->join('left', '#__users AS u ON u.id = rr.checked_out');

		$query->where('rr.event_id = ' . (int) $event_id);

		$order = $this->getState('list.ordering', 'rank');
		$dir = $this->getState('list.direction', 'ASC');

		$alpha = $db->qn('i.last_name') . ' ASC, ' . $db->qn('i.first_name') . ' ASC';

		switch ($order)
		{
			case 'number':
				$col = $db->qn('number');
				$query->order($col . ' ' . $dir . ', ' . $alpha);
				break;

			case 'participant':
				$order = $db->qn('i.last_name') . ' ' . $dir . ', ' . $db->qn('i.first_name') . ' ' . $dir;
				$query->order($order);
				break;

			case 'team':
				$col = $db->qn('t.name');
				$query->order($col . ' ' . $dir . ', ' . $alpha);
				break;

			case 'performance':
				$order = $db->qn('rr.performance');
				$query->order($order . ' ' . $dir . ', ' . $alpha);
				break;

			case 'bonus_points':
				$order = $db->qn('rr.bonus_points');
				$query->order($order . ' ' . $dir . ', ' . $alpha);
				break;

			case 'rank':
			default:
				$query->order($db->qn('rr.rank') . ' = 0 ' . $dir . ', ' . $db->qn('rr.rank') . ' ' . $dir . ', ' . $alpha);
				break;
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
				$query->where('obj.name LIKE ' . $search);
			}
		}

		return $query;
	}

	/**
	 * Get event info
	 *
	 * @return object
	 */
	public function getEventInfo()
	{
		$db = $this->_db;
		$query = $db->getQuery(true);

		$query->select('sr.projectround_id, sr.id AS event_id');
		$query->select('r.name AS roundname');
		$query->select('p.name AS projectname');
		$query->select('srt.name AS eventname');
		$query->from('#__tracks_projects_rounds AS pr');
		$query->join('INNER', '#__tracks_rounds AS r ON r.id = pr.round_id');
		$query->join('INNER', '#__tracks_projects_events AS sr ON sr.projectround_id = pr.id');
		$query->join('INNER', '#__tracks_eventtypes AS srt ON sr.type = srt.id');
		$query->join('INNER', '#__tracks_projects AS p ON p.id = pr.project_id');
		$query->where('sr.id = ' . $this->getState('event_id'));

		$db->setQuery($query);
		$res = $db->loadObject();

		return $res;
	}

	/**
	 * Method to save ranks
	 *
	 * @access    public
	 *
	 * @return    boolean    True on success
	 *
	 * @since     1.5
	 */
	public function saveranks($cid = array(), $rank, $bonus_points, $performance, $individual, $team, $event_id)
	{
		$row = $this->getTable('eventresult');

		// Update ordering values
		for ($i = 0; $i < count($cid); $i++)
		{
			if (!$row->load((int) $cid[$i]))
			{
				// No entry yet for this player/round/project, create it
				$row->reset();
				$row->individual_id = $individual[$i];
				$row->team_id = $team[$i];
				$row->event_id = $event_id;
				$row->rank = $rank[$i];
				$row->bonus_points = $bonus_points[$i];
				$row->performance = $performance[$i];

				if (!$row->store())
				{
					$this->setError($this->_db->getErrorMsg());

					return false;
				}
			}
			else
			{
				if ($row->rank != $rank[$i]
					|| $row->bonus_points != $bonus_points[$i]
					|| $row->performance != $performance[$i])
				{
					$row->rank = $rank[$i];
					$row->bonus_points = $bonus_points[$i];
					$row->performance = $performance[$i];
					$row->team_id = $team[$i];

					if (!$row->store())
					{
						$this->setError($this->_db->getErrorMsg());

						return false;
					}
				}
			}
		}

		return true;
	}

	/**
	 * Get project id
	 *
	 * @return mixed
	 */
	protected function getProjectId()
	{
		if (!$this->project_id)
		{
			$this->project_id = TrackslibHelperTools::getCurrentProjectId();
		}

		return $this->project_id;
	}

	/**
	 * Add individuals from project to sub round results.
	 *
	 * @return boolean
	 */
	public function addAll()
	{
		$project_id = $this->getProjectId();

		if ($project_id)
		{
			$query = ' INSERT IGNORE INTO #__tracks_events_results (individual_id, team_id, event_id) '
				. ' SELECT pi.individual_id, pi.team_id, ' . $this->getState('event_id')
				. ' FROM #__tracks_participants AS pi '
				. ' WHERE pi.project_id = ' . $project_id;
			$this->_db->setQuery($query);

			$this->_db->execute();

			if (!$this->_db->execute())
			{
				$this->setError($this->_db->getErrorMsg());

				return false;
			}
		}

		return true;
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
		$event_id = JFactory::getApplication()->getUserStateFromRequest($this->context . '.event_id', 'event_id', 0, 'int');
		$event_id = $event_id ? $event_id : JFactory::getApplication()->input->getInt('event_id', 0);

		if (!$event_id)
		{
			throw new RuntimeException('event_id is required');
		}

		$this->setState('event_id', $event_id);

		return parent::populateState($ordering, $direction);
	}
}
