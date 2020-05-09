<?php
/**
 * @version        $Id: default.php 101 2008-05-22 08:32:12Z julienv $
 * @package        JoomlaTracks
 * @copyright      Copyright (C) 2008 Julien Vonthron. All rights reserved.
 * @license        GNU/GPL, see LICENSE.php
 * Joomla Tracks is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * See COPYRIGHT.php for copyright notices and details.
 */
// no direct access
defined('_JEXEC') or die('Restricted access');
?>

<div id="tracks">

	<h2><?php echo $this->project->season_name . ' ' . $this->project->name . ' ' . JText::_('COM_TRACKS_Season_Summary'); ?></h2>

	<table class="raceResults">
		<tbody>
		<tr>
			<th class="raceResults__round"><?php echo JText::_('COM_TRACKS_Round'); ?></th>
			<th class="raceResults__date"><?php echo JText::_('COM_TRACKS_Date'); ?></th>
			<th class="raceResults__winner"><?php echo JText::_('COM_TRACKS_Winner'); ?></th>
			<?php if ($this->params->get('showteams', 1)): ?>
				<th class="raceResults__team"><?php echo JText::_('COM_TRACKS_TEAM'); ?></th>
			<?php endif; ?>
		</tr>
		<?php
		$k = 0;
		foreach ($this->results AS $result)
		{
			$link_round = JRoute::_(TrackslibHelperRoute::getRoundResultRoute($result->slug));
			?>
			<tr class="<?php echo($k++ % 2 ? 'd1' : 'd0'); ?>">
				<td class="raceResults__round">
					<a href="<?php echo $link_round; ?>" title="<?php echo JText::_('COM_TRACKS_Display') ?>">
						<?php
						echo $result->round_name;
						?>
					</a>
				</td>
				<td class="raceResults__date"><?php echo Tracks\Helper\Helper::formatRoundStartEnd($result); ?></td>
				<td class="raceResults__winner">
					<?php if ($result->winner): ?>
						<?php foreach ($result->winner as $winner): ?>
							<div class="winner">
								<a href="<?= TrackslibHelperRoute::getIndividualRoute($winner->id) ?>">
									<span class="winner__name">
									<?= $winner->first_name . ' ' . $winner->last_name ?>
									</span>
									<?php if ($this->params->get('shownickname', 0) && !empty($winner->nickname)): ?>
										<span class="winner__nickname">
										(<?= $winner->nickname ?>)
										</span>
									<?php endif; ?>
								</a>
							</div>
						<?php endforeach; ?>
					<?php endif; ?>
				</td>
				<?php if ($this->params->get('showteams', 1)): ?>
					<td class="raceResults__team">
						<a href="<?= TrackslibHelperRoute::getTeamRoute($winner->team_id) ?>">
							<span class="winner__team">
							<?= $winner->team_name ?>
							</span>
						</a>
					</td>
				<?php endif; ?>
			</tr>
		<?php
		}
		?>
		</tbody>
	</table>
	<div class="icalbutton">
		<a href="<?php echo JRoute::_(TrackslibHelperRoute::getProjectRoute($this->project->slug) . '&format=ical') ?>"
		   title="<?php echo JText::_('COM_TRACKS_ICAL_EXPORT'); ?>">
			<img src="<?php echo JURI::root() . '/media/com_tracks/images/ical.gif'; ?>"
			     alt="<?php echo JText::_('COM_TRACKS_ICAL_EXPORT'); ?>"/>
		</a>
	</div>

	<p class="copyright">
		<?php echo TrackslibHelperTools::footer(); ?>
	</p>
</div>
