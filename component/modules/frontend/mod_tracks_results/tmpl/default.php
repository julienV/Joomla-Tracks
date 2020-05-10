<?php
/**
 * @version    $Id: default.php 139 2008-06-10 14:20:13Z julienv $
 * @package    JoomlaTracks
 * @subpackage ResultsModule
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

/**
 * @var TrackslibEntityProjectround $round
 * @var TrackslibEntityEventtype    $eventType
 */

JHtml::stylesheet('mod_tracks_results/style.css', array('relative' => true));
?>

<div class="mod_tracksresults">
	<div class="mod_tracksresults__roundname">
		<span class="mod_tracksresults__roundname__name"><?= $round->getRound()->name ?></span>
			-
		<span class="mod_tracksresults__roundname__date">
			<?= $round->getDate('start_date', $params->get('date_format')) ?>
		</span>
	</div>

	<?php if ($eventType->isValid()): ?>
	<div class="mod_tracksresults__eventtype">
		<?= $eventType->name ?>
	</div>
	<?php endif; ?>

	<?php if (!empty($list->results)):?>
		<table cellspacing="0" cellpadding="0" summary="">
			<thead>
			<tr>
				<th><?php echo JText::_('MOD_TRACKS_RESULTS_Pos'); ?></th>
				<th><?php echo JText::_('MOD_TRACKS_RESULTS_Individual'); ?></th>
				<?php if ($showteams) { ?>
					<th><?php echo JText::_('MOD_TRACKS_RESULTS_Team'); ?></th><?php } ?>
				<?php if ($showpoints) { ?>
					<th><?php echo JText::_('MOD_TRACKS_RESULTS_Points'); ?></th><?php } ?>
			</tr>
			</thead>
			<tbody>
			<?php
			$rank  = 1;
			$count = 0;
			foreach ($list->results as $rows)
			{
				$link_ind  = JRoute::_(TrackslibHelperRoute::getIndividualRoute($rows->slug, $project->slug));
				$link_team = JRoute::_(TrackslibHelperRoute::getTeamRoute($rows->teamslug, $project->slug));
				?>
				<tr>
					<td><?php echo $rows->rank; ?></td>
					<td>
						<a href="<?php echo $link_ind; ?>"
						   title="<?php
						   echo trim($rows->first_name . ' ' . $rows->last_name) . '::'
							   . $rows->performance; ?>" class="mod-result-tip"><?php echo $rows->last_name; ?>
						</a>
					</td>
					<?php if ($showteams) { ?>
						<td>
							<a href="<?php echo $link_team; ?>"
							   title="<?php echo $rows->team_name; ?>"
							   class="mod-result-tip">
								<?php echo $rows->team_acronym ?: $rows->team_name; ?>
							</a>
						</td>
					<?php } ?>
					<?php if ($showpoints) { ?>
						<td><?php echo $rows->points + $rows->bonus_points; ?></td>
					<?php } ?>
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
		<div class="no_res alert alert-info"><?php echo JText::_('MOD_TRACKS_RESULTS_NO_RESULTS'); ?></div>
	<?php endif; ?>
	<?php
	$link = JRoute::_(TrackslibHelperRoute::getRoundResultRoute($round->projectround_id));
	?>
	<a class="fulltablelink" href="<?php echo $link; ?>"
	   title="<?php echo JText::_('MOD_TRACKS_RESULTS_View_full_table'); ?>">
		<?php echo JText::_('MOD_TRACKS_RESULTS_View_full_table'); ?>
	</a>
</div>
