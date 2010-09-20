<?php
/**
* @version    $Id: default.php 132 2008-06-08 08:44:01Z julienv $ 
* @package    JoomlaTracks
* @copyright	Copyright (C) 2008 Julien Vonthron. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* Joomla Tracks is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/ 
 // no direct access
defined('_JEXEC') or die('Restricted access'); ?>

<div id="tracks">
<h1><?php echo JText::_( 'Tracks project list' ) ?></h1>

<table>
  <tr>
    <th><?php echo JText::_( 'Name' ) ?></th>
    <th><?php echo JText::_( 'Competition' ) ?></th>
    <th><?php echo JText::_( 'Season' ) ?></th>
    <th><?php echo JText::_( '' ) ?></th>
    <th><?php echo JText::_( '' ) ?></th>
  </tr>
  <?php
  if ( count($this->projects) )
  {
    foreach ( $this->projects as $project )
    {
      $link_project = JRoute::_( TracksHelperRoute::getProjectRoute($project->slug) );
      $link_ranking = JRoute::_( TracksHelperRoute::getRankingRoute($project->slug) );	
      $link_teams_ranking = JRoute::_( TracksHelperRoute::getTeamRankingRoute($project->slug) );			     
     ?>
      <tr>
        <td>
        	<a href="<?php echo $link_project; ?>" title ="<?php echo JText::_( 'Display' ); ?>">
        	  <?php echo $project->name; ?>
        	</a>
       	</td>
        <td><?php echo $project->competition_name; ?></td>
        <td><?php echo $project->season_name; ?></td>
        <td>
        	<a href="<?php echo $link_ranking; ?>" title ="<?php echo JText::_( 'Rankings' ); ?>">
        	  <?php echo JText::_( 'Rankings' ); ?>
        	</a>
       	</td>
        <td>
        	<a href="<?php echo $link_teams_ranking; ?>" title ="<?php echo JText::_( 'Team Rankings' ); ?>">
        	  <?php echo JText::_( 'Team Rankings' ); ?>
        	</a>
       	</td>
      </tr>
    	<?php
    }
  }
  ?>
</table>
<p class="copyright">
  <?php echo HTMLtracks::footer( ); ?>
</p>
</div>