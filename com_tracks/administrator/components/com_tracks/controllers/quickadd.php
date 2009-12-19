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
			$db->setQuery("INSERT INTO #__tracks_rounds_results (individual_id, subround_id) VALUES (".$individual.", ".$srid.")");
			$db->query();
		}

		$this->setRedirect("index.php?option=com_tracks&view=subroundresults&srid=".$srid);
	}
}

?>
