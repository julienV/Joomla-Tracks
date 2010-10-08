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
      echo JTEXT::_('table #__tracks_individuals succesfully updated<br />');
    }
    else {
      echo JTEXT::_('Error while updating table #__tracks_individuals<br />') . $database->getErrorMsg();
    }
    
    $database->setQuery( 'ALTER TABLE `#__tracks_projects_individuals` '
                       . ' ADD `number` int(11) NOT NULL AFTER `team_id` ;');
    $database->query(); 
    if ( !$database->getErrorNum() ) {
      echo JTEXT::_('table #__tracks_projects_individuals succesfully updated<br />');
    }
    else {
      echo JTEXT::_('Error while updating table #__tracks_projects_individuals<br />') . $database->getErrorMsg();
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
      echo JTEXT::_('table #__tracks_rounds_results succesfully updated<br />');
    }
    else {
      echo JTEXT::_('Error while updating table #__tracks_rounds_results<br />') . $database->getErrorMsg();
    }   
    
    /* change field name in table rounds_results */
    $database->setQuery( 
      ' INSERT INTO `#__tracks_subroundtypes` 
        (`name`, `countpoints`) VALUES ("race", 1);' );
    $database->query();
    /* create a default subround type */
    if ( !$database->getErrorNum() ) {
      echo JTEXT::_('a default subround type was added to  #__tracks_subroundtypes<br />');
    }
    else {
      echo JTEXT::_('Error while adding default subround type to table #__tracks_subroundtypes<br />') .   $database->getErrorMsg();
    }
    
    /* create corresponding subrounds */
    $database->setQuery( 
      ' INSERT INTO `#__tracks_projects_subrounds`
        (`id`,`projectround_id`, `type`, `ordering`,  `start_date`, `end_date`, `description`, `comment`) '
      . ' SELECT `id`,`round_id`, 1, 1,  `start_date`, `end_date`, `description`, `comment` 
          FROM `#__tracks_projects_rounds`;' );
    $database->query();
    if ( !$database->getErrorNum() ) {
      echo JTEXT::_('table #__tracks_projects_subrounds succesfully updated<br />');
    }
    else {
      echo JTEXT::_('Error while updating table #__tracks_projects_subrounds<br />') . $database->getErrorMsg();
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
	      echo JTEXT::_('table '.$t.' succesfully updated<br />');
	    }
	    else {
	      echo JTEXT::_('Error while updating table '.$t.'<br />') . $database->getErrorMsg();
	    }   
    } 
    
    $database->setQuery( 'ALTER TABLE `#__tracks_teams` '
                       . ' ADD `picture` varchar(100) NULL AFTER `acronym`, '
                       . ' ADD `picture_small` varchar(100) NULL AFTER `picture`;');
    $database->query(); 
    if ( !$database->getErrorNum() ) {
      echo JTEXT::_('table #__tracks_teams succesfully updated<br />');
    }
    else {
      echo JTEXT::_('Error while updating table #__tracks_teams<br />') . $database->getErrorMsg();
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
  		echo JTEXT::_('bonus points changed to type float<br />');
  	}
  	else {
  		echo JTEXT::_('Error while trying to convert bonus_points to float<br />') . $database->getErrorMsg();
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
			echo JTEXT::_('converted individual number to type varchar<br />');
		}
		else {
			echo JTEXT::_('Error while trying to convert number to varchar<br />') . $database->getErrorMsg();
		}
	}
	 
	// RESULTS TABLE
	if ( !array_key_exists('number', $tablefields[ '#__tracks_rounds_results' ]) )
	{
		$database->setQuery( 'ALTER TABLE `#__tracks_rounds_results` '
		. ' ADD `number` varchar(8) NULL AFTER `team_id`;');
		$database->query();
		if ( !$database->getErrorNum() ) {
			echo JTEXT::_('Added field number to individual results<br />');
		}
		else {
			echo JTEXT::_('Error while adding field number to individual results<br />') . $database->getErrorMsg();
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
			echo JTEXT::_('Added fields note and points_attribution to subround types<br />');
		}
		else {
			echo JTEXT::_('Error while adding fields note and points_attribution to subround types<br />') . $database->getErrorMsg();
		}
		
		$database->setQuery( 'ALTER TABLE `#__tracks_subroundtypes` DROP `countpoints`;');
    $database->query();
    if ( !$database->getErrorNum() ) {
      echo JTEXT::_('Removed field countpoints for subround types<br />');
    }
    else {
      echo JTEXT::_('Error while removing field countpoints for subround types<br />') . $database->getErrorMsg();
    }
	}
}
?>
