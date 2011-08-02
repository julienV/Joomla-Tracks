<?php
/**
* @version    0.x
* @package    JoomlaTracks
* @copyright  Copyright (C) 2008,2009,2010 Julien Vonthron. See full notice in copyright.txt
* @license    GNU/GPL, see license.txt
*/

// no direct access
defined('_JEXEC') or die('Restricted access');

/**
 * Executes additional installation processes
 *
 * @since 0.2
 */
function com_install() 
{
//  update01to02();
//  update02to03();
  update03to04();
}

function update01to02()
{
  $database =& JFactory::getDBO();
  
  // update of 0.1 to 0.2
  $tables = array('#__tracks_individuals');
  $tablefields = $database->getTableFields($tables);
  if ( !array_key_exists('nickname', $tablefields[ $tables[0] ]) )
  {
    $database->setQuery( 'ALTER TABLE `#__tracks_individuals` '
                       . ' ADD `nickname` varchar(20) NOT NULL AFTER `first_name`, '
                       . ' ADD `height` varchar(10) NULL AFTER `nickname`, '
                       . ' ADD `weight` varchar(10) NULL AFTER `height`, '
                       . ' ADD `hometown` varchar(50) NULL AFTER `dob` ;');
    $database->query();
    if ( !$database->getErrorNum() ) {
      echo JText::_('COM_TRACKS_table_#__tracks_individuals_succesfully_updated').'<br/>';
    }
    else {
      echo JText::_('COM_TRACKS_Error_while_updating_table_#__tracks_individuals') . $database->getErrorMsg().'<br/>';
    }
    
    $database->setQuery( 'ALTER TABLE `#__tracks_projects_individuals` '
                       . ' ADD `number` int(11) NOT NULL AFTER `team_id` ;');
    $database->query(); 
    if ( !$database->getErrorNum() ) {
      echo JText::_('COM_TRACKS_table_#__tracks_projects_individuals_succesfully_updated');
    }
    else {
      echo JText::_('COM_TRACKS_Error_while_updating_table_#__tracks_projects_individuals') . $database->getErrorMsg().'<br/>';
    }   
  }
  
  /* convert to subround structure */
  /* we must create subrounds, and at least a subroundtype */
  $tables = array('#__tracks_rounds_results');
  $tablefields = $database->getTableFields($tables);
  if ( !array_key_exists('subround_id', $tablefields[ $tables[0] ]) )
  {
    /* change field name in table rounds_results */
    $database->setQuery( ' ALTER TABLE `#__tracks_rounds_results` '
                       . ' CHANGE `projectround_id` `subround_id` int(11) NOT NULL;');
    $database->query();
    if ( !$database->getErrorNum() ) {
      echo JText::_('COM_TRACKS_table_#__tracks_rounds_results_succesfully_updated').'<br/>';
    }
    else {
      echo JText::_('COM_TRACKS_Error_while_updating_table_#__tracks_rounds_results') . $database->getErrorMsg().'<br/>';
    }   
    
    /* change field name in table rounds_results */
    $database->setQuery( 
      ' INSERT INTO `#__tracks_subroundtypes` 
        (`name`, `countpoints`) VALUES ("race", 1);' );
    $database->query();
    /* create a default subround type */
    if ( !$database->getErrorNum() ) {
      echo JText::_('COM_TRACKS_a_default_subround_type_was_added_to_#__tracks_subroundtypes').'<br/>';
    }
    else {
      echo JText::_('COM_TRACKS_Error_while_adding_default_subround_type_to_table_#__tracks_subroundtypes') .   $database->getErrorMsg().'<br/>';
    }
    
    /* create corresponding subrounds */
    $database->setQuery( 
      ' INSERT INTO `#__tracks_projects_subrounds`
        (`id`,`projectround_id`, `type`, `ordering`,  `start_date`, `end_date`, `description`, `comment`) '
      . ' SELECT `id`,`round_id`, 1, 1,  `start_date`, `end_date`, `description`, `comment` 
          FROM `#__tracks_projects_rounds`;' );
    $database->query();
    if ( !$database->getErrorNum() ) {
      echo JText::_('COM_TRACKS_table_#__tracks_projects_subrounds_succesfully_updated').'<br/>';
    }
    else {
      echo JText::_('COM_TRACKS_Error_while_updating_table_#__tracks_projects_subrounds') . $database->getErrorMsg().'<br/>';
    }
  }
}

function update02to03()
{
  $database =& JFactory::getDBO();
  
  // update of 0.1 to 0.2
  $tables = array('#__tracks_projects');
  $tablefields = $database->getTableFields($tables);
  if ( !array_key_exists('alias', $tablefields[ $tables[0] ]) )
  {
  	//adding aliases
  	$toupdate = array( '#__tracks_competitions', 
  	                   '#__tracks_individuals', 
  	                   '#__tracks_projects', 
  	                   '#__tracks_rounds', 
  	                   '#__tracks_seasons',
  	                   '#__tracks_subroundtypes', 
  	                   '#__tracks_teams' );
    foreach ($toupdate as $t)
    {
	  	$database->setQuery( 'ALTER TABLE '.$t.' '
	                       . ' ADD `alias` varchar(100) NOT NULL AFTER `id`;');
	    $database->query();
	    if ( !$database->getErrorNum() ) {
	      echo JText::_('COM_TRACKS_table '.$t.' succesfully updated').'<br/>';
	    }
	    else {
	      echo JText::_('COM_TRACKS_Error_while_updating_table '.$t) . $database->getErrorMsg().'<br/>';
	    }   
    } 
    
    $database->setQuery( 'ALTER TABLE `#__tracks_teams` '
                       . ' ADD `picture` varchar(100) NULL AFTER `acronym`, '
                       . ' ADD `picture_small` varchar(100) NULL AFTER `picture`;');
    $database->query(); 
    if ( !$database->getErrorNum() ) {
      echo JText::_('COM_TRACKS_table_#__tracks_teams_succesfully_updated').'<br/>';
    }
    else {
      echo JText::_('COM_TRACKS_Error_while_updating_table_#__tracks_teams') . $database->getErrorMsg().'<br/>';
    }   
      
  }
  
  $database->setQuery( ' SHOW COLUMNS FROM `#__tracks_rounds_results` LIKE "bonus_points";');
  $bonus_field = $database->loadObject();
  if (strtolower($bonus_field->Type) != 'float')
  {
  	$database->setQuery( ' ALTER TABLE `#__tracks_rounds_results` '
  	. ' CHANGE `bonus_points` `bonus_points` float NOT NULL;');
  	$database->query();
  	if ( !$database->getErrorNum() ) {
  		echo JText::_('COM_TRACKS_bonus_points_changed_to_type_float').'<br/>';
  	}
  	else {
  		echo JText::_('COM_TRACKS_Error_while_trying_to_convert_bonus_points_to_float') . $database->getErrorMsg().'<br/>';
  	}
  }
}
  
function update03to04()
{
	$database =& JFactory::getDBO();
	
	// get current structure
  $tables = array('#__tracks_rounds_results', '#__tracks_subroundtypes');
  $tablefields = $database->getTableFields($tables);

	// allow string for participant 'number'
	$database->setQuery( ' SHOW COLUMNS FROM `#__tracks_projects_individuals` LIKE "number";');
	$number_field = $database->loadObject();	
	if (strtolower($number_field->Type) != 'varchar(8)')
	{
		$database->setQuery( ' ALTER TABLE `#__tracks_projects_individuals` '
		. ' CHANGE `number` `number` VARCHAR(8) NULL;');
		$database->query();
		if ( !$database->getErrorNum() ) {
			echo JText::_('COM_TRACKS_converted_individual_number_to_type_varchar');
		}
		else {
			echo JText::_('COM_TRACKS_Error_while_trying_to_convert_number_to_varchar') . $database->getErrorMsg().'<br/>';
		}
	}
	 
	// RESULTS TABLE
	if ( !array_key_exists('number', $tablefields[ '#__tracks_rounds_results' ]) )
	{
		$database->setQuery( 'ALTER TABLE `#__tracks_rounds_results` '
		. ' ADD `number` varchar(8) NULL AFTER `team_id`;');
		$database->query();
		if ( !$database->getErrorNum() ) {
			echo JText::_('COM_TRACKS_Added_field_number_to_individual_results').'<br/>';
		}
		else {
			echo JText::_('COM_TRACKS_Error_while_adding_field_number_to_individual_results') . $database->getErrorMsg().'<br/>';
		}
	}
	 

	// SUBROUND TYPE TABLE
	if ( !array_key_exists('points_attribution', $tablefields[ '#__tracks_subroundtypes' ]) )
	{
		$database->setQuery( 'ALTER TABLE `#__tracks_subroundtypes` '
		. ' ADD `note` varchar(100) NOT NULL AFTER `alias`, '
		. ' ADD `points_attribution` varchar(250) NOT NULL AFTER `note`;');
		$database->query();
		if ( !$database->getErrorNum() ) {
			echo JText::_('COM_TRACKS_Added_fields_note_and_points_attribution_to_subround_types').'<br/>';
		}
		else {
			echo JText::_('COM_TRACKS_Error_while_adding_fields_note_and_points_attribution_to_subround_types') . $database->getErrorMsg().'<br/>';
		}
		
		$database->setQuery( 'ALTER TABLE `#__tracks_subroundtypes` DROP `countpoints`;');
    $database->query();
    if ( !$database->getErrorNum() ) {
      echo JText::_('COM_TRACKS_Removed_field_countpoints_for_subround_types').'<br/>';
    }
    else {
      echo JText::_('COM_TRACKS_Error_while_removing_field_countpoints_for_subround_types') . $database->getErrorMsg().'<br/>';
    }
	}
}
?>
