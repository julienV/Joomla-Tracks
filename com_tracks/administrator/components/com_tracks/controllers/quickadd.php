<?php

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();

jimport('joomla.application.component.controller');

/**
 * Joomla Tracks Component Controller
 *
 * @package	 Tracks
 * @since 0.1
 */
class TracksControllerQuickAdd extends BaseController
{

	function __construct()
	{
		parent::__construct();

		// Register Extra tasks
		$this->registerTask( 'search',  'search' );
		$this->registerTask( 'add', 'add' );
	}
  
	function search() 
	{
		require_once(JPATH_COMPONENT.DS.'models'.DS.'quickadd.php');
		$model = new TracksModelQuickAdd();
		$query = JRequest::getVar("query", "", "", "string");
		$results = $model->getList($query);
		$response = array(
			"totalCount" => count($results),
			"rows" => array(),
		);

		foreach ($results as $row) {
			$response["rows"][] = array(
				"id" => $row->id,
				"name" => $row->first_name." ".$row->last_name,
				"nickname" => $row->nickname,
			);
		}

		echo json_encode($response);
		exit;
	}

	function add()
	{
		global $mainframe, $option;
		$db = &JFactory::getDBO();
		$individual = JRequest::getVar("quick_add", "");
		$srid = JRequest::getVar("srid", 0, "", "int");
		$projectid = $mainframe->getUserState($option."project");

		// add the new individual as their name was sent through.
		if (!is_numeric($individual)) {
			require_once(JPATH_COMPONENT.DS.'models'.DS.'individual.php');
			$model = new TracksModelIndividual();
			$name = explode(" ", $individual);
			$firstname = ucfirst(array_shift($name));
			$lastname = ucfirst(implode(" ", $name));
			$data = array(
				"first_name" => $firstname,
				"last_name" => $lastname,
			);
			$model->store($data);
			$individual = mysql_insert_id();
			$db->setQuery("INSERT INTO #__tracks_projects_individuals (individual_id, project_id) VALUES (".$individual.", ".$projectid.")");
			$db->query();
		}

		// assign the individual to the subround.
		if (is_numeric($individual) && is_numeric($srid)) {
			$table = $this->_assignTable($srid);
			$db->setQuery("INSERT INTO #__tracks_rounds_results (individual_id, subround_id, performance) VALUES (".$individual.", ".$srid.", ".$table.")");
			$db->query();
		}

		$this->setRedirect("index.php?option=com_tracks&view=subroundresults&srid=".$srid);
	}

	function _assignTable($srid) 
	{
		$db = &JFactory::getDBO();
		$query = "SELECT performance, COUNT(performance) as counter ";
		$query .= "FROM #__tracks_rounds_results ";
		$query .= "WHERE subround_id = ".$srid." ";
		$query .= "GROUP BY performance ";
		$db->setQuery($query);
		$results = $db->loadObjectList();

		// fill the tables with the default 1-6 tables.
		$tables = array_fill(1, 4, 0);

		// grab the amount of people at each table.
		foreach ($results as $result) {
			if ($result->performance == "") continue;
			$tables[$result->performance] = $result->counter;
		}

		// grab the max table number in use so far.
		$max = max(array_keys($tables));

		// grab the tables that aren't full.
		$available = array();
		foreach ($tables as $key => $value) {
			if ($value < 8) {
				$available[] = $key;
			}
		}

		// if max is odd, add one on as we increment in pairs.
		if ($max & 1) {
			$max++;
			$available[] = $max;
		} else {
			if (!key_exists($max - 1, $tables)) {
				$available[] = $max -1;
			}
		}

		// if there are no empty tables, add 2 new ones.
		if (empty($available)) {
			$max += 2;
			$available = array($max - 1, $max);
		} elseif (count($available) == 1) {
			return $available[0];
		}

		$table = mt_rand(0, count($available) - 1);
		$table = $available[$table];
		return $table;
	}
}

?>
