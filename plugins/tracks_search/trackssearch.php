<?php
/**
* @version    $Id$ 
* @package    JoomlaTracks
* @subpackage searchPlugin
* @copyright  Copyright (C) 2008 Julien Vonthron. All rights reserved.
* @license    GNU/GPL, see LICENSE.php
* Joomla Tracks is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );
jimport('joomla.plugin.plugin');

/**
 * Weblinks Search plugin
 *
 * @package		Joomla.Plugin
 * @subpackage	Search.Tracks
 * @since		1.7
 */
class plgSearchTrackssearch extends JPlugin
{
	/**
	 * Constructor
	 *
	 * @access      protected
	 * @param       object  $subject The object to observe
	 * @param       array   $config  An array that holds the plugin configuration
	 * @since       1.5
	 */
	public function __construct(& $subject, $config)
	{
		include_once (JPATH_SITE.DS.'components'.DS.'com_tracks'.DS.'lib'.DS.'JLVImageTool.php');
		parent::__construct($subject, $config);
		$this->loadLanguage();
	}

	/**
	 * @return array An array of search areas
	 */
	public function onContentSearchAreas() 
	{
		static $areas = array(
			'tracksindividuals' => 'PLG_SEARCH_TRACKS_INDIVIDUALS',
			'tracksteams' => 'PLG_SEARCH_TRACKS_TEAMS',
			'tracksprojects' => 'PLG_SEARCH_TRACKS_PROJECTS',
// 			'tracksrounds' => 'PLG_SEARCH_TRACKS_ROUNDS',
		);
		return $areas;
	}

	/**
	 * Weblink Search method
	 *
	 * The sql must return the following fields that are used in a common display
	 * routine: href, title, section, created, text, browsernav
	 * @param string Target search string
	 * @param string mathcing option, exact|any|all
	 * @param string ordering option, newest|oldest|popular|alpha|category
	 * @param mixed An array if the search it to be restricted to areas, null if search all
	 */
	public function onContentSearch($text, $phrase='', $ordering='', $areas=null)
	{
		$db		= JFactory::getDbo();
		$app	= JFactory::getApplication();
		$user	= JFactory::getUser();
		$groups	= implode(',', $user->getAuthorisedViewLevels());
		
		require_once JPATH_SITE.'/components/com_tracks/helpers/route.php';
		
		$searchText = $text;

		if (is_array($areas)) {
			if (!array_intersect($areas, array_keys($this->onContentSearchAreas()))) {
				return array();
			}
		}

		$limit			= $this->params->def('search_limit',		50);

		$text = trim($text);
		if ($text == '') {
			return array();
		}
		$section	= JText::_('PLG_SEARCH_TRACKS_TRACKS');

		$rows = array();
		if (!$areas || in_array('tracksindividuals', $areas))
		{
			$wheres = array();
			switch ($phrase) {
	
				//search exact
				case 'exact':
					$string        = $db->Quote( '%'.$db->getEscaped( $text, true ).'%', false );
					$wheres2       = array();
					$wheres2[]   = 'LOWER(i.first_name) LIKE '.$string;
	        $wheres2[]   = 'LOWER(i.last_name) LIKE '.$string;
	        $wheres2[]   = 'LOWER(i.nickname) LIKE '.$string;
					$where                 = '(' . implode( ') OR (', $wheres2 ) . ')';
					break;
	
					//search all or any
				case 'all':
				case 'any':
	
					//set default
				default:
					$words         = explode( ' ', $text );
					$wheres = array();
					foreach ($words as $word)
					{
						$word          = $db->Quote( '%'.$db->getEscaped( $word, true ).'%', false );
						$wheres2       = array();
		        $wheres2[]   = 'LOWER(i.first_name) LIKE '.$word;
		        $wheres2[]   = 'LOWER(i.last_name) LIKE '.$word;
		        $wheres2[]   = 'LOWER(i.nickname) LIKE '.$word;
						$wheres[]    = implode( ' OR ', $wheres2 );
					}
					$where = '(' . implode( ($phrase == 'all' ? ') AND (' : ') OR ('), $wheres ) . ')';
					break;
			}
	
			//ordering of the results
			switch ( $ordering ) {
	
				//alphabetic, ascending
				case 'alpha':
					$order = 'i.last_name ASC, i.first_name ASC';
					break;
	
					//oldest first
				case 'oldest':
	
					//popular first
				case 'popular':
	
					//newest first
				case 'newest':
	
					//default setting: alphabetic, ascending
				default:
	        $order = 'i.last_name ASC, i.first_name ASC';
			}
	
			//the database query; differs per situation! It will look something like this:
			$query = 'SELECT CONCAT_WS(", ", i.last_name, i.first_name) AS title, picture_small, '
			. ' CONCAT_WS( " / ", '. $db->Quote($section) .', '.$db->Quote(JText::_( 'PLG_SEARCH_TRACKS_INDIVIDUALS' )).' ) AS section,'
	    . ' CASE WHEN CHAR_LENGTH( i.alias ) THEN CONCAT_WS( \':\', i.id, i.alias ) ELSE i.id END AS slug, '
	    . ' NULL AS created, '
			. ' "2" AS browsernav'
			. ' FROM #__tracks_individuals AS i'
			. ' WHERE ( '. $where .' )'
			. ' ORDER BY '. $order
			;
	
			//Set query
			$db->setQuery( $query, 0, $limit );
// 			echo '<pre>';print_r($db->getQuery()); echo '</pre>';exit;
			$results = $db->loadObjectList();
	
			//The 'output' of the displayed link
			foreach($results as $key => $row) {
				$results[$key]->href  = TracksHelperRoute::getIndividualRoute($row->slug);
				$results[$key]->image = JLVImageTool::getThumbUrl(JPATH_SITE.DS.$row->picture_small, $this->params->get('image_size', 40));
			}
			$rows = array_merge($rows, $results);
		}
				
		if (!$areas || in_array('tracksteams', $areas))
		{
	    $wheres = array();
	    switch ($phrase) {
	
	      //search exact
	      case 'exact':
	        $string          = $db->Quote( '%'.$db->getEscaped( $text, true ).'%', false );
	        $wheres2       = array();
	        $wheres2[]   = 'LOWER(t.name) LIKE '.$string;
	        $where                 = '(' . implode( ') OR (', $wheres2 ) . ')';
	        break;
	
	        //search all or any
	      case 'all':
	      case 'any':
	
	        //set default
	      default:
	        $words         = explode( ' ', $text );
	        $wheres = array();
	        foreach ($words as $word)
	        {
	          $word          = $db->Quote( '%'.$db->getEscaped( $word, true ).'%', false );
	          $wheres2       = array();
	          $wheres2[]   = 'LOWER(t.name) LIKE '.$word;
	          $wheres[]    = implode( ' OR ', $wheres2 );
	        }
	        $where = '(' . implode( ($phrase == 'all' ? ') AND (' : ') OR ('), $wheres ) . ')';
	        break;
	    }
	
	    //ordering of the results
	    switch ( $ordering ) {
	
	      //alphabetic, ascending
	      case 'alpha':
	        $order = 't.name ASC';
	        break;
	
	        //oldest first
	      case 'oldest':
	
	        //popular first
	      case 'popular':
	
	        //newest first
	      case 'newest':
	
	        //default setting: alphabetic, ascending
	      default:
	        $order = 't.name ASC';
	    }
	
	    //the database query; differs per situation! It will look something like this:
	    $query = 'SELECT t.name AS title,'
	    . ' CONCAT_WS( " / ", '. $db->Quote($section) .', '.$db->Quote(JText::_( 'PLG_SEARCH_TRACKS_TEAMS' )).' ) AS section,'
	    . ' CASE WHEN CHAR_LENGTH( t.alias ) THEN CONCAT_WS( \':\', t.id, t.alias ) ELSE t.id END AS slug, '
	    . ' NULL AS created, '
	    . ' "2" AS browsernav'
	    . ' FROM #__tracks_teams AS t'
	    . ' WHERE ( '. $where .' )'
	    . ' ORDER BY '. $order
	    ;
	
	    //Set query
	    $db->setQuery( $query, 0, $limit );
	    $results = $db->loadObjectList();
	
	    //The 'output' of the displayed link
	    foreach($results as $key => $row) {
				$results[$key]->href  = TracksHelperRoute::getTeamRoute($row->slug);
	    }
	    $rows = array_merge($rows, $results);
	  }
	
	  if (!$areas || in_array('tracksprojects', $areas))
	  {
	    $wheres = array();
	    switch ($phrase) {
	
	      //search exact
	      case 'exact':
	        $string          = $db->Quote( '%'.$db->getEscaped( $text, true ).'%', false );
	        $wheres2       = array();
	        $wheres2[]   = 'LOWER(p.name) LIKE '.$string;
	        $where                 = '(' . implode( ') OR (', $wheres2 ) . ')';
	        break;
	
	        //search all or any
	      case 'all':
	      case 'any':
	
	        //set default
	      default:
	        $words         = explode( ' ', $text );
	        $wheres = array();
	        foreach ($words as $word)
	        {
	          $word          = $db->Quote( '%'.$db->getEscaped( $word, true ).'%', false );
	          $wheres2       = array();
	          $wheres2[]   = 'LOWER(p.name) LIKE '.$word;
	          $wheres[]    = implode( ' OR ', $wheres2 );
	        }
	        $where = '(' . implode( ($phrase == 'all' ? ') AND (' : ') OR ('), $wheres ) . ')';
	        break;
	    }
	
	    //ordering of the results
	    switch ( $ordering ) {
	
	      //alphabetic, ascending
	      case 'alpha':
	        $order = 'p.name ASC';
	        break;
	
	        //oldest first
	      case 'oldest':
	
	        //popular first
	      case 'popular':
	
	        //newest first
	      case 'newest':
	
	        //default setting: alphabetic, ascending
	      default:
	        $order = 'p.name ASC';
	    }
	
	    //the database query; differs per situation! It will look something like this:
	    $query = 'SELECT p.name AS title,'
	    . ' CONCAT_WS( " / ", '. $db->Quote($section) .', '.$db->Quote(JText::_( 'PLG_SEARCH_TRACKS_PROJECTS' )).' ) AS section,'
	    . ' CASE WHEN CHAR_LENGTH( p.alias ) THEN CONCAT_WS( \':\', p.id, p.alias ) ELSE p.id END AS slug, '
	    . ' NULL AS created, '
	    . ' "2" AS browsernav'
	    . ' FROM #__tracks_projects AS p'
	    . ' WHERE ( '. $where .' )'
	    . ' ORDER BY '. $order
	    ;
	
	    //Set query
	    $db->setQuery( $query, 0, $limit );
	    $results = $db->loadObjectList();
	
	    //The 'output' of the displayed link
	    foreach($results as $key => $row) {
				$results[$key]->href  = TracksHelperRoute::getProjectRoute($row->slug);
	    }
	    $rows = array_merge($rows, $results);
	  }		
	
// 	  if (!$areas || in_array('tracksrounds', $areas))
		if (0)
	  {
	    $wheres = array();
	    switch ($phrase) {
	
	      //search exact
	      case 'exact':
	        $string          = $db->Quote( '%'.$db->getEscaped( $text, true ).'%', false );
	        $wheres2       = array();
	        $wheres2[]   = 'LOWER(r.name) LIKE '.$string;
	        $where                 = '(' . implode( ') OR (', $wheres2 ) . ')';
	        break;
	
	        //search all or any
	      case 'all':
	      case 'any':
	
	        //set default
	      default:
	        $words         = explode( ' ', $text );
	        $wheres = array();
	        foreach ($words as $word)
	        {
	          $word          = $db->Quote( '%'.$db->getEscaped( $word, true ).'%', false );
	          $wheres2       = array();
	          $wheres2[]   = 'LOWER(r.name) LIKE '.$word;
	          $wheres[]    = implode( ' OR ', $wheres2 );
	        }
	        $where = '(' . implode( ($phrase == 'all' ? ') AND (' : ') OR ('), $wheres ) . ')';
	        break;
	    }
	
	    //ordering of the results
	    switch ( $ordering ) {
	
	      //alphabetic, ascending
	      case 'alpha':
	        $order = 'r.name ASC';
	        break;
	
	        //oldest first
	      case 'oldest':
	
	        //popular first
	      case 'popular':
	
	        //newest first
	      case 'newest':
	
	        //default setting: alphabetic, ascending
	      default:
	        $order = 'r.name ASC';
	    }
	
	    //the database query; differs per situation! It will look something like this:
	    $query = 'SELECT r.name AS title,'
	    . ' CONCAT_WS( " / ", '. $db->Quote($section) .', '.$db->Quote(JText::_( 'PLG_SEARCH_TRACKS_ROUNDS' )).' ) AS section,'
	    . ' CASE WHEN CHAR_LENGTH( r.alias ) THEN CONCAT_WS( \':\', r.id, r.alias ) ELSE r.id END AS slug, '
	    . ' NULL AS created, '
	    . ' "2" AS browsernav'
	    . ' FROM #__tracks_rounds AS r'
	    . ' WHERE ( '. $where .' )'
	    . ' ORDER BY '. $order
	    ;
	
	    //Set query
	    $db->setQuery( $query, 0, $limit );
	    $results = $db->loadObjectList();
	
	    //The 'output' of the displayed link
	    foreach($results as $key => $row) {
	      $results[$key]->href = 'index.php?option=com_tracks&view=round&r='.$row->slug;
	    }
	    $rows = array_merge($rows, $results);
	  }		
		
		return $rows;
	}
	
}