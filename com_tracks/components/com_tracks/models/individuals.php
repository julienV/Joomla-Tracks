<?php
/**
* @version    $Id: roundresult.php 61 2008-04-24 15:20:36Z julienv $
* @package    JoomlaTracks
* @copyright    Copyright (C) 2008 Julien Vonthron. All rights reserved.
* @license      GNU/GPL, see LICENSE.php
* Joomla Tracks is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();

jimport('joomla.application.component.model');
require_once( 'base.php' );
/**
 * Joomla Tracks Component Front page Model
 *
 * @package     Tracks
 * @since 0.1
 */
class TracksFrontModelIndividuals extends baseModel
{

	function __construct($config = array())
	{
		parent::__construct($config = array());
		$this->setState('filtering', JRequest::getCmd('filtering'));
		$this->setState('country', JRequest::getCmd('country'));
	}

	function getData()
	{
		$db		= $this->getDbo();
		$query	= $db->getQuery(true);

		$limit = 0;
		$limitstart = 0;

		$query->select( 'i.id, i.first_name, i.last_name, i.country_code, i.picture_small, '
		              . ' CASE WHEN CHAR_LENGTH( i.alias ) THEN CONCAT_WS( \':\', i.id, i.alias ) ELSE i.id END AS slug ');
		$query->from('#__tracks_individuals as i');

		$ordering = JFactory::getApplication()->getParams('com_tracks')->get('ordering', 0);
		$query->order($ordering ? 'i.first_name, i.last_name ASC ' : 'i.last_name ASC, i.first_name ASC ');

		if ($this->getState('country')) {
			$query->where('i.country_code = ' . $this->_db->Quote($this->getState('country')));
		}

		switch ($this->getState('filtering'))
		{
			case 'female':
				$query->where('i.gender = 2');
				break;
			case 'haspicture':
				$query->where('CHAR_LENGTH(i.picture_small) > 0 ');
				break;
			case 'standup':
				$query->innerJoin('#__tracks_rounds_results AS rr ON rr.individual_id = i.id');
				$query->innerJoin('#__tracks_projects_subrounds AS sr ON sr.id = rr.subround_id ');
				$query->innerJoin('#__tracks_projects_rounds AS pr ON pr.id = sr.projectround_id ');
				$query->innerJoin('#__tracks_projects AS p ON p.id = pr.project_id ');
				$query->where('p.competition_id = 1');
				$query->where('p.name NOT LIKE ("%freestyle%")');
				$query->where('p.name NOT LIKE ("%freeride%")');
				$query->group('i.id');
				break;
			case 'sitdown':
				$query->innerJoin('#__tracks_rounds_results AS rr ON rr.individual_id = i.id');
				$query->innerJoin('#__tracks_projects_subrounds AS sr ON sr.id = rr.subround_id ');
				$query->innerJoin('#__tracks_projects_rounds AS pr ON pr.id = sr.projectround_id ');
				$query->innerJoin('#__tracks_projects AS p ON p.id = pr.project_id ');
				$query->where('p.competition_id = 2');
				$query->where('p.name NOT LIKE ("%freestyle%")');
				$query->where('p.name NOT LIKE ("%freeride%")');
				$query->group('i.id');
				break;
			case 'sport':
				$query->innerJoin('#__tracks_rounds_results AS rr ON rr.individual_id = i.id');
				$query->innerJoin('#__tracks_projects_subrounds AS sr ON sr.id = rr.subround_id ');
				$query->innerJoin('#__tracks_projects_rounds AS pr ON pr.id = sr.projectround_id ');
				$query->innerJoin('#__tracks_projects AS p ON p.id = pr.project_id ');
				$query->where('p.competition_id = 3');
				$query->where('p.name NOT LIKE ("%freestyle%")');
				$query->where('p.name NOT LIKE ("%freeride%")');
				$query->group('i.id');
				break;
			case 'freestyle':
				$query->innerJoin('#__tracks_rounds_results AS rr ON rr.individual_id = i.id');
				$query->innerJoin('#__tracks_projects_subrounds AS sr ON sr.id = rr.subround_id ');
				$query->innerJoin('#__tracks_projects_rounds AS pr ON pr.id = sr.projectround_id ');
				$query->innerJoin('#__tracks_projects AS p ON p.id = pr.project_id ');
				$query->where('p.competition_id = 1');
				$query->where('p.name LIKE ("%freestyle%")');
				$query->group('i.id');
				break;
			case 'freeride':
				$query->innerJoin('#__tracks_rounds_results AS rr ON rr.individual_id = i.id');
				$query->innerJoin('#__tracks_projects_subrounds AS sr ON sr.id = rr.subround_id ');
				$query->innerJoin('#__tracks_projects_rounds AS pr ON pr.id = sr.projectround_id ');
				$query->innerJoin('#__tracks_projects AS p ON p.id = pr.project_id ');
				$query->where('p.competition_id = 1');
				$query->where('p.name LIKE ("%freeride%")');
				$query->group('i.id');
				break;
			case 'lastupdated':
				$query->clear('order');
				$query->order('i.modified DESC ');
				$limit = 15;
				break;
			case 'lastviewed':
				$query->clear('order');
				$query->order('i.last_hit DESC ');
				$limit = 20;
				break;
			case 'mostpopular':
				$query->clear('order');
				$query->order('i.hits DESC ');
				$limit = 15;
				break;
			case 'mostpopularstandup':
				$query->innerJoin('#__tracks_rounds_results AS rr ON rr.individual_id = i.id');
				$query->innerJoin('#__tracks_projects_subrounds AS sr ON sr.id = rr.subround_id ');
				$query->innerJoin('#__tracks_projects_rounds AS pr ON pr.id = sr.projectround_id ');
				$query->innerJoin('#__tracks_projects AS p ON p.id = pr.project_id ');
				$query->where('p.competition_id = 1');
				$query->where('p.name NOT LIKE ("%freestyle%")');
				$query->where('p.name NOT LIKE ("%freeride%")');
				$query->group('i.id');
				$query->clear('order');
				$query->order('i.hits DESC ');
				$limit = 15;
				break;
			case 'mostpopularsitdown':
				$query->innerJoin('#__tracks_rounds_results AS rr ON rr.individual_id = i.id');
				$query->innerJoin('#__tracks_projects_subrounds AS sr ON sr.id = rr.subround_id ');
				$query->innerJoin('#__tracks_projects_rounds AS pr ON pr.id = sr.projectround_id ');
				$query->innerJoin('#__tracks_projects AS p ON p.id = pr.project_id ');
				$query->where('p.competition_id = 2');
				$query->where('p.name NOT LIKE ("%freestyle%")');
				$query->where('p.name NOT LIKE ("%freeride%")');
				$query->group('i.id');
				$query->clear('order');
				$query->order('i.hits DESC ');
				$limit = 15;
				break;
			case 'mostpopularsport':
				$query->innerJoin('#__tracks_rounds_results AS rr ON rr.individual_id = i.id');
				$query->innerJoin('#__tracks_projects_subrounds AS sr ON sr.id = rr.subround_id ');
				$query->innerJoin('#__tracks_projects_rounds AS pr ON pr.id = sr.projectround_id ');
				$query->innerJoin('#__tracks_projects AS p ON p.id = pr.project_id ');
				$query->where('p.competition_id = 3');
				$query->where('p.name NOT LIKE ("%freestyle%")');
				$query->where('p.name NOT LIKE ("%freeride%")');
				$query->group('i.id');
				$query->clear('order');
				$query->order('i.hits DESC ');
				$limit = 15;
				break;
			case 'mostpopularfreestyle':
				$query->innerJoin('#__tracks_rounds_results AS rr ON rr.individual_id = i.id');
				$query->innerJoin('#__tracks_projects_subrounds AS sr ON sr.id = rr.subround_id ');
				$query->innerJoin('#__tracks_projects_rounds AS pr ON pr.id = sr.projectround_id ');
				$query->innerJoin('#__tracks_projects AS p ON p.id = pr.project_id ');
				$query->where('p.competition_id = 1');
				$query->where('p.name LIKE ("%freestyle%")');
				$query->group('i.id');
				$query->clear('order');
				$query->order('i.hits DESC ');
				$limit = 15;
				break;
			case 'mostpopularfreeride':
				$query->innerJoin('#__tracks_rounds_results AS rr ON rr.individual_id = i.id');
				$query->innerJoin('#__tracks_projects_subrounds AS sr ON sr.id = rr.subround_id ');
				$query->innerJoin('#__tracks_projects_rounds AS pr ON pr.id = sr.projectround_id ');
				$query->innerJoin('#__tracks_projects AS p ON p.id = pr.project_id ');
				$query->where('p.competition_id = 1');
				$query->where('p.name LIKE ("%freeride%")');
				$query->group('i.id');
				$query->clear('order');
				$query->order('i.hits DESC ');
				$limit = 10;
				break;
		}

		$this->_db->setQuery( $query, $limitstart, $limit );

		return $this->_db->loadObjectList();
	}

}
