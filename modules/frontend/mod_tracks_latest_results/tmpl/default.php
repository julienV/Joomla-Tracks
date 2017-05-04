<?php
/**
 * @package    JoomlaTracks
 * @subpackage ResultsModule
 * @copyright  Copyright (C) 2017 Julien Vonthron. All rights reserved.
 * @license    GNU/GPL, see LICENSE.php
 */
// no direct access
defined('_JEXEC') or die('Restricted access');

JHTML::_('behavior.tooltip', '.mod-result-tip', array('className' => 'tip-mod-result'));
$document = JFactory::getDocument();
$document->addStyleSheet( JURI::base() . 'modules/mod_tracks_latest_results/mod_tracks_latest_results.css' );
$document->addScript( JURI::base() . 'modules/mod_tracks_latest_results/mod_tracks_latest_results.js' );
?>
	<div class="mod_tracksresults">
	<div id="roundname"><?php echo $projectRound->getRound()->name . ' - ' . $event->getEventtype()->name; ?></div>
	<?php if ($list): ?>
	<table cellspacing="0" cellpadding="0" summary="">
		<thead>
	    <tr>
	      <th><?php echo JText::_( 'MOD_TRACKS_LATEST_RESULTS_Pos' ); ?></th>
	      <th><?php echo JText::_( 'MOD_TRACKS_LATEST_RESULTS_Individual' ); ?></th>
	      <?php if ($showteams) { ?><th><?php echo JText::_( 'MOD_TRACKS_LATEST_RESULTS_Team' ); ?></th><?php } ?>
	      <?php if ($showpoints) { ?><th><?php echo JText::_( 'MOD_TRACKS_LATEST_RESULTS_Points' ); ?></th><?php } ?>
	    </tr>
		</thead>
	  <tbody>
	    <?php
	    $rank = 1;
	    $count = 0;
	    foreach( $list AS $rows )
	    {
	      $link_ind = JRoute::_( TrackslibHelperRoute::getIndividualRoute($rows->slug, $project->slug) );
	      $link_team = JRoute::_( TrackslibHelperRoute::getTeamRoute($rows->teamslug, $project->slug) );
	      ?>
	      <tr>
	        <td><?php echo $rows->rank; ?></td>
	        <td>
	          <a href="<?php echo $link_ind; ?>"
	             title="<?php
	              echo trim($rows->first_name . ' ' . $rows->last_name).'::'
	                  . $rows->performance; ?>" class="mod-result-tip"><?php echo $rows->last_name; ?>
	          </a>
	        </td>
	        <?php if ($showteams) { ?>
	        <td>
	          <a href="<?php echo $link_team; ?>"
	             title="<?php echo $rows->team_name . '::' . JText::_('MOD_TRACKS_LATEST_RESULTS_Click_for_details' ); ?>" class="mod-result-tip">
	            <?php echo $rows->team_acronym; ?>
	          </a>
	        </td>
	        <?php } ?>
	        <?php if ($showpoints) { ?>
	        <td><?php echo $rows->points + $rows->bonus_points; ?></td>
	        <?php } ?>
	      </tr>
	      <?php
	      if ( ++$count >= $limit ) {
	        break;
	      }
	    }
	    ?>
	  </tbody>
	</table>
	<?php else: ?>
	<span class="no_res"><?php echo JText::_('MOD_TRACKS_LATEST_RESULTS_NO_RESULTS'); ?></span>
	<?php endif;?>
	<?php
	$link = JRoute::_( TrackslibHelperRoute::getRoundResultRoute($projectRound->id) );
	?>
	<a class="fulltablelink" href="<?php echo $link; ?>"
	             title="<?php echo JText::_('MOD_TRACKS_LATEST_RESULTS_View_full_table' ); ?>">
	            <?php echo JText::_('MOD_TRACKS_LATEST_RESULTS_View_full_table' ); ?>
	</a>
</div>
