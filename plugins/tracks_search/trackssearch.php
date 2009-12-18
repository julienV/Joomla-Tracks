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
 
//define the registerEvent and the language file. 
$mainframe->registerEvent( 'onSearch', 'plgSearchTracksSearch' );
$mainframe->registerEvent( 'onSearchAreas', 'plgSearchTracksSearchAreas' );
 
JPlugin::loadLanguage( 'plg_search_trackssearch' );
 
//define a function to return an array of search areas.
function &plgSearchTracksSearchAreas()
{
        static $areas = array(
                'tracksindividuals' => 'TRACKS INDIVIDUALS',
                'tracksteams' => 'TRACKS TEAMS',
                'tracksprojects' => 'TRACKS PROJECTS',
        );
        return $areas;
}
 
//Then the real function has to be created. The database connection should be made. 
function plgSearchTracksSearch( $text, $phrase='', $ordering='', $areas=null )
{
	$db            =& JFactory::getDBO();
	$user  =& JFactory::getUser();

	//If the array is not correct, return it:
	if (is_array( $areas )) {
		if (!array_intersect( $areas, array_keys( plgSearchTracksSearchAreas() ) )) {
			return array();
		}
	}

	//It is time to define the parameters! First get the right plugin; 'search' (the group), 'nameofplugin'.
	$plugin =& JPluginHelper::getPlugin('search', 'trackssearch');

	//Then load the parameters of the plugin..
	$pluginParams = new JParameter( $plugin->params );
	
  $limit = $pluginParams->def( 'search_limit', 50 );

	//Use the function trim to delete spaces in front of or at the back of the searching terms
	$text = trim( $text );

	//Return Array when nothing was filled in
	if ($text == '') {
		return array();
	}

	$rows = array();
	
	$searchTracks = $db->Quote(JText::_( 'TRACKS' ));

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
		$query = 'SELECT CONCAT_WS(", ", i.last_name, i.first_name) AS title,'
		. ' CONCAT_WS( " / ", '. $searchTracks .', '.$db->Quote(JText::_( 'INDIVIDUALS' )).' ) AS section,'
    . ' CASE WHEN CHAR_LENGTH( i.alias ) THEN CONCAT_WS( \':\', i.id, i.alias ) ELSE i.id END AS slug, '
    . ' NULL AS created, '
		. ' "2" AS browsernav'
		. ' FROM #__tracks_individuals AS i'
		. ' WHERE ( '. $where .' )'
		. ' ORDER BY '. $order
		;

		//Set query
		$db->setQuery( $query, 0, $limit );
		$results = $db->loadObjectList();

		//The 'output' of the displayed link
		foreach($results as $key => $row) {
			$results[$key]->href = 'index.php?option=com_tracks&view=individual&i='.$row->slug;
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
    . ' CONCAT_WS( " / ", '. $searchTracks .', '.$db->Quote(JText::_( 'TEAMS' )).' ) AS section,'
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
      $results[$key]->href = 'index.php?option=com_tracks&view=team&t='.$row->slug;
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
    . ' CONCAT_WS( " / ", '. $searchTracks .', '.$db->Quote(JText::_( 'PROJECTS' )).' ) AS section,'
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
      $results[$key]->href = 'index.php?option=com_tracks&view=project&p='.$row->slug;
    }
    $rows = array_merge($rows, $results);
  }
  
	//Return the search results in an array
	return $rows;
}

?>