<?php
/**
* @version    $Id: tracks.php 109 2008-05-24 11:05:07Z julienv $ 
* @package    JoomlaTracks
* @subpackage RankingModule
* @copyright  Copyright (C) 2008 Julien Vonthron. All rights reserved.
* @license    GNU/GPL, see LICENSE.php
* Joomla Tracks is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/
// no direct access
defined('_JEXEC') or die('Restricted access'); 

JHTML::_('behavior.tooltip', '.mod-ranking-tip', array('className' => 'tip-mod-ranking'));

$img_dir = JPATH_SITE.DS.'media'.DS.'com_tracks'.DS.'images'.DS.'individuals'.DS.'small'.DS;
?>
<div class="mod_tracksranking">

<table cellspacing="0" cellpadding="0" summary="">
	<thead>
    <tr>
      <th><?php echo JText::_( 'MOD_TRACKS_RANKING_Pos' ); ?></th>
      <th><?php echo JText::_( 'MOD_TRACKS_RANKING_Individual' ); ?></th>
      <?php if ($showteams) { ?><th><?php echo JText::_( 'MOD_TRACKS_RANKING_Team' ); ?></th><?php } ?>
      <th><?php echo JText::_( 'MOD_TRACKS_RANKING_Points' ); ?></th>
    </tr>
	</thead>
  <tbody>
    <?php
    $rank = 1;
    $count = 0;
    foreach( $list AS $ranking )
    {
      $link_ind = JRoute::_( TracksHelperRoute::getIndividualRoute($ranking->slug, $project->slug) ); 
      $link_team = JRoute::_( TracksHelperRoute::getTeamRoute($ranking->teamslug, $project->slug) ); 
      ?>
      <tr>
        <td><?php echo $rank++; ?></td>
        <td>
        	<?php if ($params->get('showpicture', 1) && $ranking->picture_small): ?>
        	<?php echo JLVImageTool::modalimage($img_dir.$ranking->picture_small, $ranking->first_name, 20); ?>
        	<?php endif; ?>
          <a href="<?php echo $link_ind; ?>"
             title="<?php echo $ranking->last_name.($ranking->first_name ? ', '.$ranking->first_name : ''); ?>::" class="mod-ranking-tip">
          <?php echo $ranking->last_name; ?>
          </a>
        </td>
        <?php if ($showteams) { ?>
        <td>
          <a href="<?php echo $link_team; ?>"
             title="<?php echo JText::_( 'MOD_TRACKS_RANKING_Details' ); ?>"> 
            <?php echo $ranking->team_acronym; ?>
          </a>
        </td>
        <?php } ?>
        <td><?php echo $ranking->points; ?></td>
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
$link = JRoute::_( TracksHelperRoute::getRankingRoute($project->slug) );
?>
<a class="fulltablelink" href="<?php echo $link; ?>"
             title="<?php echo JText::_('MOD_TRACKS_RANKING_View_full_table' ); ?>"> 
            <?php echo JText::_('MOD_TRACKS_RANKING_View_full_table' ); ?>
</a>
</div>
