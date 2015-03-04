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
class TracksControllerQuickAdd extends FOFController
{
	protected $modelName = 'TracksModelQuickadd';

	public function search()
	{
		$model = $this->getThisModel();
		$query = $this->input->get("query", "", "", "string");
		$results = $model->getList($query);
		$response = array(
			"totalCount" => count($results),
			"rows" => array(),
		);

		foreach ($results as $row)
		{
			$response["rows"][] = array(
				"id" => $row->id,
				"name" => $row->first_name." ".$row->last_name,
				"nickname" => $row->nickname,
			);
		}

		echo json_encode($response);
		exit;
	}

	/**
	 * add a project individual
	 */
	public function addpi()
	{
		$mainframe = JFactory::getApplication();
		$option = $this->input->getCmd('option', 'com_tracks');
		$db = JFactory::getDBO();

		$individualid = $this->input->getInt("individualid", 0);
		$name = $this->input->get("quickadd", '', 'request', 'string');
		$pid = $this->input->getInt("pid", 0);
		$projectid = $mainframe->getUserState($option."project");

		// add the new individual as their name was sent through.
		if (!$individualid)
		{
			require_once(JPATH_COMPONENT. '/' .'models'. '/' .'individual.php');
			$model = new TracksModelIndividual();
			$name = explode(" ", $name);
			$firstname = ucfirst(array_shift($name));
			$lastname = ucfirst(implode(" ", $name));
			$data = array(
				"first_name" => $firstname,
				"last_name" => $lastname,
			);
			$individualid = $model->store($data);

			if (!$individualid)
			{
				$msg = Jtext::_('COM_TRACKS_Error_adding_individual').': '.$model->getError();
				$this->setRedirect("index.php?option=com_tracks&view=subroundresults&subround_id=".$srid, $msg, 'error');
			}
		}

		// check if indivual belongs to project
		$query = ' SELECT individual_id FROM #__tracks_projects_individuals '
		       . ' WHERE project_id = '. $db->Quote($projectid)
		       . '   AND individual_id = '. $db->Quote($individualid);
		$db->setQuery($query);
		$res = $db->loadResult();
		if (!$res)
		{
			$db->setQuery("INSERT INTO #__tracks_projects_individuals (individual_id, project_id) VALUES (".$individualid.", ".$projectid.")");
			$db->query();
		}

		$this->setRedirect("index.php?option=com_tracks&view=projectindividuals");
	}

	public function add()
	{
		$mainframe = JFactory::getApplication();
		$option = $this->input->getCmd('option', 'com_tracks');

		$db = JFactory::getDBO();

		$individualid = $this->input->getInt("individualid", 0);
		$name = $this->input->get("quickadd", '', 'request', 'string');
		$srid = $this->input->getInt("subround_id", 0);
		$projectid = $mainframe->getUserState($option."project");

		// add the new individual as their name was sent through.
		if (!$individualid)
		{
			$model = FOFModel::getAnInstance('Individuals', 'TracksModel');
			$name = explode(" ", $name);
			$firstname = ucfirst(array_shift($name));
			$lastname = ucfirst(implode(" ", $name));
			$data = array(
				"first_name" => $firstname,
				"last_name" => $lastname,
			);
			$res = $model->save($data);

			if (!$res)
			{
				$msg = Jtext::_('COM_TRACKS_Error_adding_individual').': '.$model->getError();
				$this->setRedirect("index.php?option=com_tracks&view=subroundresults&subround_id=" . $srid, $msg, 'error');
			}

			$individualid = $model->getId();
		}

		// check if indivual belongs to project
		$query = ' SELECT individual_id FROM #__tracks_projects_individuals '
		       . ' WHERE project_id = '. $db->Quote($projectid)
		       . '   AND individual_id = '. $db->Quote($individualid);
		$db->setQuery($query);
		$res = $db->loadResult();
		if (!$res)
		{
			$db->setQuery("INSERT INTO #__tracks_projects_individuals (individual_id, project_id) VALUES (".$individualid.", ".$projectid.")");
			$db->query();
		}

		// assign the individual to the subround.
		if ($individualid && $srid)
		{
			$db->setQuery("INSERT INTO #__tracks_rounds_results (individual_id, subround_id) VALUES (".$individualid.", ".$srid.")");
			$db->query();
		}

		$this->setRedirect("index.php?option=com_tracks&view=subroundresults&subround_id=" . $srid);
	}
}

?>
