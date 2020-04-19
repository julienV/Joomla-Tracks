<?php
/**
 * @package     Tracks
 * @subpackage  Library
 * @copyright   Tracks (C) 2008-2015 Julien Vonthron. All rights reserved.
 * @license     GNU General Public License version 2 or later
 */

defined('_JEXEC') or die;

require_once JPATH_COMPONENT . '/' . 'helpers' . '/' . 'iCalcreator.class.php';

/**
 * HTML View class for the Tracks component
 *
 * @package  Tracks
 * @since    0.1
 */
class TracksViewProject extends JViewLegacy
{
	/**
	 * Execute and display a template script.
	 *
	 * @param   string  $tpl  The name of the template file to parse; automatically searches through the template paths.
	 *
	 * @return  mixed  A string if successful, otherwise a Error object.
	 */
	public function display($tpl = null)
	{
		$mainframe = JFactory::getApplication();
		$option    = JRequest::getCmd('option');

		$model   = $this->getModel();
		$results = $model->getResults(JRequest::getVar('p', 0, '', 'int'));
		$project = $model->getProject(JRequest::getVar('p', 0, '', 'int'));

		$document = JFactory::getDocument();

		// Initiate new CALENDAR
		$v = new vcalendar;

		// Config with site domain
		$v->setConfig('project' . $project->id, $mainframe->getCfg('live_site'));

		// Set some X-properties, name, content...
		$v->setProperty('X-WR-CALNAME', $project->name);
		$v->setProperty('X-WR-CALDESC', JText::_('COM_TRACKS_Project_calendar'));
		$v->setConfig("filename", 'project_' . $project->id . '.ics');

		foreach ((array) $results AS $result)
		{
			if (!ereg('([0-9]{4})-([0-9]{1,2})-([0-9]{1,2}) ([0-9]{1,2}):([0-9]{1,2}):([0-9]{1,2})', $result->start_date, $start_date))
			{
				continue;
			}

			// Initiate a new EVENT
			$e = new vevent;

			// Categorize
			$e->setProperty('categories', $project->name);
			$e->setProperty('dtstart', $start_date[1], $start_date[2], $start_date[3], $start_date[4], $start_date[5], $start_date[6]);

			if (ereg('([0-9]{4})-([0-9]{1,2})-([0-9]{1,2}) ([0-9]{1,2}):([0-9]{1,2}):([0-9]{1,2})', $result->end_date, $end_date))
			{
				$e->setProperty('dtend', $end_date[1], $end_date[2], $end_date[3], $end_date[4], $end_date[5], $end_date[6]);
			}

			$description   = array();
			$description[] = $result->round_name;

			if ($result->winner && $result->winner->last_name)
			{
				$winner = JText::_('COM_TRACKS_Winner') . $result->winner->first_name . ' ' . $result->winner->last_name;

				if ($result->winner->team_name)
				{
					$winner .= ' (' . $result->winner->team_name . ')';
				}

				$description[] = $winner;
			}

			$description = implode('\\n', $description);
			$e->setProperty('description', $description);
			$e->setProperty('location', $result->round_name);

			// Add component to calendar
			$v->addComponent($e);
		}

		$str = $v->createCalendar();
		$v->returnCalendar();
	}
}
