<?php
/**
* @version    $Id: default.php 120 2008-05-30 01:59:54Z julienv $ 
* @package    JoomlaTracks
* @subpackage TeamRankingModule
* @copyright  Copyright (C) 2008 Julien Vonthron. All rights reserved.
* @license    GNU/GPL, see LICENSE.php
* Joomla Tracks is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/
// no direct access
defined('_JEXEC') or die('Restricted access'); ?>
<div class="mod_tracksranking<?php echo $params->get('moduleclass_sfx'); ?>">

<table class="raceResults" cellspacing="0" cellpadding="0" summary="">
  <tbody>
    <tr>
      <th><?php echo JText::_( 'Pos' ); ?></th>
      <th><?php echo JText::_( 'Team' ); ?></th>
      <th><?php echo JText::_( 'Points' ); ?></th>
    </tr>
    <?php
    $rank = 1;
    $count = 0;
    foreach( $list AS $ranking )
    {
      $link_team = JRoute::_( TracksHelperRoute::getTeamRoute($ranking->team_id, $project->slug) ); 
      ?>
      <tr>
        <td><?php echo $rank++; ?></td>
        <td>
          <a href="<?php echo $link_team; ?>"
             title="<?php echo JText::_( 'Details' ); ?>"> 
            <?php echo $ranking->team_name; ?>&nbsp;
          </a>
        </td>
        <td><?php echo $ranking->points; ?>&nbsp;</td>
      </tr>
      <?php
      if ( ++$count >= $limit ) {
        break;
      }
    }
    ?>
  </tbody>
</table>
<?php 
$link = JRoute::_( TracksHelperRoute::getTeamRankingRoute($project->slug) );
?>
<a class="fulltablelink" href="<?php echo $link; ?>"
             title="<?php echo JText::_( 'View full table' ); ?>"> 
            <?php echo JText::_( 'View full table' ); ?>&nbsp;
</a>
</div>
