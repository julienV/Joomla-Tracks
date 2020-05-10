<?php
/**
 * @package    JoomlaTracks
 * @subpackage ResultsModule
 * @copyright  Copyright (C) 2017 Julien Vonthron. All rights reserved.
 * @license    GNU/GPL, see LICENSE.php
 */
// no direct access
defined('_JEXEC') or die('Restricted access');

$document = JFactory::getDocument();

JHtml::stylesheet('mod_tracks_latest_results/style.css', array('relative' => true));

$date = $event->getDate('start_date', $params->get('date_format'))
	?: $projectRound->getDate('start_date', $params->get('date_format'));
?>
<div class="mod_tracks_latest_results">
	<div class="mod_tracks_latest_results__roundname">
		<span class="mod_tracks_latest_results__roundname__name"><?= $projectRound->getRound()->name ?></span>
		-
		<span class="mod_tracks_latest_results__roundname__date">
			<?= $date ?>
		</span>
	</div>

	<?php if ($event->isValid()): ?>
		<div class="mod_tracks_latest_results__eventtype">
			<?= $event->getEventtype()->name ?>
		</div>
	<?php endif; ?>

	<?php if (!empty($list)): ?>
		<table cellspacing="0" cellpadding="0" summary="">
			<thead>
			<tr>
				<th><?php echo JText::_('MOD_TRACKS_LATEST_RESULTS_Pos'); ?></th>
				<th><?php echo JText::_('MOD_TRACKS_LATEST_RESULTS_Individual'); ?></th>
				<?php if ($showteams) { ?>
					<th><?php echo JText::_('MOD_TRACKS_LATEST_RESULTS_Team'); ?></th><?php } ?>
				<?php if ($showpoints) { ?>
					<th><?php echo JText::_('MOD_TRACKS_LATEST_RESULTS_Points'); ?></th><?php } ?>
				<?php if ($showperformance) { ?>
					<th><?php echo JText::_('MOD_TRACKS_LATEST_RESULTS_PERFORMANCE'); ?></th><?php } ?>
			</tr>
			</thead>
			<tbody>
			<?php
			$rank  = 1;
			$count = 0;
			foreach ($list as $rows)
			{
				$link_ind  = JRoute::_(TrackslibHelperRoute::getIndividualRoute($rows->slug, $project->slug));
				$link_team = JRoute::_(TrackslibHelperRoute::getTeamRoute($rows->teamslug, $project->slug));
				?>
				<tr>
					<td><?php echo $rows->rank; ?></td>
					<td>
						<a href="<?php echo $link_ind; ?>"
						   title="<?php
						   echo trim($rows->first_name . ' ' . $rows->last_name) . ' - '
							   . $rows->performance; ?>"><?php echo $rows->last_name; ?>
						</a>
					</td>
					<?php if ($showteams): ?>
						<td>
							<a href="<?php echo $link_team; ?>"
							   title="<?php echo $rows->team_name . ' - ' . JText::_('MOD_TRACKS_LATEST_RESULTS_Click_for_details'); ?>">
								<?php echo $rows->team_acronym ?: $rows->team_name; ?>
							</a>
						</td>
					<?php endif; ?>

					<?php if ($showpoints): ?>
						<td><?php echo $rows->points + $rows->bonus_points; ?></td>
					<?php endif; ?>

					<?php if ($showperformance): ?>
						<td><?php echo $rows->performance; ?></td>
					<?php endif; ?>
				</tr>
				<?php
				if (++$count >= $limit)
				{
					break;
				}
			}
			?>
			</tbody>
		</table>
	<?php else: ?>
		<div class="alert alert-info no_res"><?php echo JText::_('MOD_TRACKS_LATEST_RESULTS_NO_RESULTS'); ?></div>
	<?php endif; ?>
	<?php
	$link = JRoute::_(TrackslibHelperRoute::getRoundResultRoute($projectRound->id));
	?>
	<a class="fulltablelink" href="<?php echo $link; ?>"
	   title="<?php echo JText::_('MOD_TRACKS_LATEST_RESULTS_View_full_table'); ?>">
		<?php echo JText::_('MOD_TRACKS_LATEST_RESULTS_View_full_table'); ?>
	</a>
</div>
