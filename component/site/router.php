<?php
/**
 * @package    Tracks.Site
 * @copyright  Tracks (C) 2008-2020 Julien Vonthron. All rights reserved.
 * @license    GNU General Public License version 2 or later
 */

use Joomla\CMS\Factory;
use Joomla\CMS\Table\Table;

defined('_JEXEC') or die;

jimport('tracks.bootstrap');

/**
 * Routing class from com_tracks
 *
 * @since  1.0
 */
class TracksRouter extends JComponentRouterView
{
	protected $noIDs = false;

	const SEGMENT_RANKING            = 'standings';
	const SEGMENT_TEAMRANKING        = 'team-standings';
	const SEGMENT_PROJECTINDIVIDUALS = 'participants';
	const SEGMENT_PROJECTRESULTS     = 'results';
	const SEGMENT_ROUNDRESULT        = 'result';

	/**
	 * Search Component router constructor
	 *
	 * @param   JApplicationCms  $app   The application object
	 * @param   JMenu            $menu  The menu object to work with
	 */
	public function __construct($app = null, $menu = null)
	{
		$params      = JComponentHelper::getParams('com_tracks');

		$individuals = new JComponentRouterViewconfiguration('individuals');
		$this->registerView($individuals);

		$individual = new JComponentRouterViewconfiguration('individual');
		$individual->setKey('id')->setParent($individuals);
		$this->registerView($individual);

		$this->registerView(new JComponentRouterViewconfiguration('profile'));

		$projects = new JComponentRouterViewconfiguration('projects');
		$this->registerView($projects);

		$project = new JComponentRouterViewconfiguration('project');
		$project->setKey('p')->setParent($projects);
		$this->registerView($project);

		$projectindividuals = new JComponentRouterViewconfiguration('projectindividuals');
		$projectindividuals->setKey('p')->setParent($project, 'p');
		$this->registerView($projectindividuals);

		$projectresults = new JComponentRouterViewconfiguration('projectresults');
		$projectresults->setKey('p')->setParent($project, 'p');
		$this->registerView($projectresults);

		$ranking = new JComponentRouterViewconfiguration('ranking');
		$ranking->setKey('p')->setParent($project, 'p');
		$this->registerView($ranking);

		$rounds = new JComponentRouterViewconfiguration('rounds');
		$this->registerView($rounds);

		$round = new JComponentRouterViewconfiguration('round');
		$round->setKey('id')->setParent($rounds);
		$this->registerView($round);

		$roundresult = new JComponentRouterViewconfiguration('roundresult');
		$roundresult->setKey('id')->setParent($project, 'p');
		$this->registerView($roundresult);

		$teams = new JComponentRouterViewconfiguration('teams');
		$this->registerView($teams);

		$team = new JComponentRouterViewconfiguration('team');
		$team->setKey('id')->setParent($teams);
		$this->registerView($team);

		$teamranking = new JComponentRouterViewconfiguration('teamranking');
		$teamranking->setKey('p')->setParent($project, 'p');
		$this->registerView($teamranking);

		parent::__construct($app, $menu);

		$this->attachRule(new JComponentRouterRulesMenu($this));
		$this->attachRule(new JComponentRouterRulesStandard($this));
		$this->attachRule(new JComponentRouterRulesNomenu($this));
	}

	/**
	 * Method to get the segment(s)
	 *
	 * @param   string  $id     ID of the object to retrieve the segments for
	 * @param   array   $query  The request that is built right now
	 *
	 * @return  array|string  The segments of this item
	 */
	public function getIndividualSegment($id, $query)
	{
		$entity = TrackslibEntityIndividual::load((int) $id);

		if ($entity->isValid())
		{
			return [(int) $id => $entity->alias];
		}

		return [];
	}

	/**
	 * Method to get the segment(s)
	 *
	 * @param   string  $id     ID of the object to retrieve the segments for
	 * @param   array   $query  The request that is built right now
	 *
	 * @return  array|string  The segments of this item
	 */
	public function getProjectSegment($id, $query)
	{
		$entity = TrackslibEntityProject::load($id);

		if ($entity->isValid())
		{
			return [(int) $id => $entity->alias];
		}

		return [];
	}

	/**
	 * Method to get the segment(s)
	 *
	 * @param   string  $id     ID of the object to retrieve the segments for
	 * @param   array   $query  The request that is built right now
	 *
	 * @return  array|string  The segments of this item
	 */
	public function getProjectindividualsSegment($id, $query)
	{
		return [(int) $id => self::SEGMENT_PROJECTINDIVIDUALS];
	}

	/**
	 * Method to get the segment(s)
	 *
	 * @param   string  $id     ID of the object to retrieve the segments for
	 * @param   array   $query  The request that is built right now
	 *
	 * @return  array|string  The segments of this item
	 */
	public function getProjectresultsSegment($id, $query)
	{
		return [(int) $id => self::SEGMENT_PROJECTRESULTS];
	}

	/**
	 * Method to get the segment(s)
	 *
	 * @param   string  $id     ID of the object to retrieve the segments for
	 * @param   array   $query  The request that is built right now
	 *
	 * @return  array|string  The segments of this item
	 */
	public function getRankingSegment($id, $query)
	{
		return [(int) $id => self::SEGMENT_RANKING];
	}

	/**
	 * Method to get the segment(s)
	 *
	 * @param   string  $id     ID of the object to retrieve the segments for
	 * @param   array   $query  The request that is built right now
	 *
	 * @return  array|string  The segments of this item
	 */
	public function getRoundSegment($id, $query)
	{
		$entity = TrackslibEntityRound::load($id);

		if ($entity->isValid())
		{
			return [(int) $id => $entity->alias];
		}

		return [];
	}

	/**
	 * Method to get the segment(s)
	 *
	 * @param   string  $id     ID of the object to retrieve the segments for
	 * @param   array   $query  The request that is built right now
	 *
	 * @return  array|string  The segments of this item
	 */
	public function getRoundResultSegment($id, $query)
	{
		$entity = TrackslibEntityProjectround::load((int) $id);

		if ($entity->isValid())
		{
			return [(int) $id => self::SEGMENT_ROUNDRESULT . '-' . (int) $id . '-' . $entity->getRound()->alias];
		}

		return [];
	}

	/**
	 * Method to get the segment(s)
	 *
	 * @param   string  $id     ID of the object to retrieve the segments for
	 * @param   array   $query  The request that is built right now
	 *
	 * @return  array|string  The segments of this item
	 */
	public function getTeamSegment($id, $query)
	{
		$entity = TrackslibEntityTeam::load($id);

		if ($entity->isValid())
		{
			return [(int) $id => $entity->alias];
		}

		return [];
	}

	/**
	 * Method to get the segment(s)
	 *
	 * @param   string  $id     ID of the object to retrieve the segments for
	 * @param   array   $query  The request that is built right now
	 *
	 * @return  array|string  The segments of this item
	 */
	public function getTeamrankingSegment($id, $query)
	{
		return [(int) $id => self::SEGMENT_TEAMRANKING];
	}

	/**
	 * Method to get the id for a view
	 *
	 * @param   string  $segment  Segment to retrieve the ID for
	 * @param   array   $query    The request that is parsed right now
	 *
	 * @return  mixed   The id of this item or false
	 */
	public function getIndividualId($segment, $query)
	{
		$table = Table::getInstance('Individual', 'TracksTable');
		$table->load(['alias' => $segment]);

		return $table->id;
	}

	/**
	 * Method to get the id for a view
	 *
	 * @param   string  $segment  Segment to retrieve the ID for
	 * @param   array   $query    The request that is parsed right now
	 *
	 * @return  mixed   The id of this item or false
	 */
	public function getProjectId($segment, $query)
	{
		$table = Table::getInstance('Project', 'TracksTable');
		$table->load(['alias' => $segment]);

		return $table->id;
	}

	/**
	 * Method to get the id for a view
	 *
	 * @param   string  $segment  Segment to retrieve the ID for
	 * @param   array   $query    The request that is parsed right now
	 *
	 * @return  mixed   The id of this item or false
	 */
	public function getRankingId($segment, $query)
	{
		if ($segment == self::SEGMENT_RANKING)
		{
			return $query['p'];
		}

		return false;
	}

	/**
	 * Method to get the id for a view
	 *
	 * @param   string  $segment  Segment to retrieve the ID for
	 * @param   array   $query    The request that is parsed right now
	 *
	 * @return  mixed   The id of this item or false
	 */
	public function getProjectindividualsId($segment, $query)
	{
		if ($segment == self::SEGMENT_PROJECTINDIVIDUALS)
		{
			return $query['p'];
		}
	}

	/**
	 * Method to get the id for a view
	 *
	 * @param   string  $segment  Segment to retrieve the ID for
	 * @param   array   $query    The request that is parsed right now
	 *
	 * @return  mixed   The id of this item or false
	 */
	public function getProjectresultsId($segment, $query)
	{
		if ($segment == self::SEGMENT_PROJECTRESULTS)
		{
			return $query['p'];
		}
	}

	/**
	 * Method to get the id for a view
	 *
	 * @param   string  $segment  Segment to retrieve the ID for
	 * @param   array   $query    The request that is parsed right now
	 *
	 * @return  mixed   The id of this item or false
	 */
	public function getRoundId($segment, $query)
	{
		$table = Table::getInstance('Round', 'TracksTable');
		$table->load(['alias' => $segment]);

		return $table->id;
	}

	/**
	 * Method to get the id for a view
	 *
	 * @param   string  $segment  Segment to retrieve the ID for
	 * @param   array   $query    The request that is parsed right now
	 *
	 * @return  mixed   The id of this item or false
	 */
	public function getRoundresultId($segment, $query)
	{
		if (strpos($segment, self::SEGMENT_ROUNDRESULT . '-') !== 0)
		{
			return false;
		}

		return (int) substr($segment, strlen(self::SEGMENT_ROUNDRESULT) + 1);
	}

	/**
	 * Method to get the id for a view
	 *
	 * @param   string  $segment  Segment to retrieve the ID for
	 * @param   array   $query    The request that is parsed right now
	 *
	 * @return  mixed   The id of this item or false
	 */
	public function getTeamId($segment, $query)
	{
		$table = Table::getInstance('Team', 'TracksTable');
		$table->load(['alias' => $segment]);

		return $table->id;
	}

	/**
	 * Method to get the id for a view
	 *
	 * @param   string  $segment  Segment to retrieve the ID for
	 * @param   array   $query    The request that is parsed right now
	 *
	 * @return  mixed   The id of this item or false
	 */
	public function getTeamrankingId($segment, $query)
	{
		if ($segment == self::SEGMENT_TEAMRANKING)
		{
			return $query['p'];
		}

		return false;
	}
}

/**
 * Contact router functions
 *
 * These functions are proxys for the new router interface
 * for old SEF extensions.
 *
 * @param   array  &$query  An array of URL arguments
 *
 * @return  array  The URL arguments to use to assemble the subsequent URL.
 *
 * @deprecated  4.0  Use Class based routers instead
 */
function TracksBuildRoute(&$query)
{
	$app    = Factory::getApplication();
	$router = new TracksRouter($app, $app->getMenu());

	return $router->build($query);
}

/**
 * Tracks router functions
 *
 * These functions are proxys for the new router interface
 * for old SEF extensions.
 *
 * @param   array  $segments  The segments of the URL to parse.
 *
 * @return  array  The URL attributes to be used by the application.
 *
 * @deprecated  4.0  Use Class based routers instead
 */
function TracksParseRoute($segments)
{
	$app    = Factory::getApplication();
	$router = new TracksRouter($app, $app->getMenu());

	return $router->parse($segments);
}
