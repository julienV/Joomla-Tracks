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
defined('_JEXEC') or die('Restricted access');

JHtml::stylesheet('mod_tracks_teamranking/style.css', array('relative' => true));
?>
<div class="mod_tracks_teamranking">
	<div class="project-name"><?php echo $project->name; ?></div>

	<table cellspacing="0" cellpadding="0" summary="">
	  <tbody>
		<tr>
		  <th><?php echo JText::_( 'MOD_TRACKS_TEAM_RANKING_Pos' ); ?></th>
		  <th><?php echo JText::_( 'MOD_TRACKS_TEAM_RANKING_Team' ); ?></th>
		  <th><?php echo JText::_( 'MOD_TRACKS_TEAM_RANKING_Points' ); ?></th>
		</tr>
		<?php
		$rank = 1;
		$count = 0;
		foreach( $list AS $ranking )
		{
		  $link_team = JRoute::_( TrackslibHelperRoute::getTeamRoute($ranking->slug, $project->slug) );
		  ?>
		  <tr>
			<td><?php echo $rank++; ?></td>
			<td>
			  <a href="<?php echo $link_team; ?>"
				 title="<?php echo $ranking->team_name; ?>">
				<?php echo $ranking->team_name; ?>
			  </a>
			</td>
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

	<a class="fulltablelink" href="<?= JRoute::_(TrackslibHelperRoute::getTeamRankingRoute($project->slug)) ?>"
				 title="<?php echo JText::_('MOD_TRACKS_TEAM_RANKING_View_full_table' ); ?>">
				<?php echo JText::_('MOD_TRACKS_TEAM_RANKING_View_full_table' ); ?>
	</a>
</div>
