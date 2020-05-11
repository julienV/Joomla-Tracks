<?php
/**
 * @version        $Id: default.php 135 2008-06-08 21:50:12Z julienv $
 * @package        JoomlaTracks
 * @copyright      Copyright (C) 2008 Julien Vonthron. All rights reserved.
 * @license        GNU/GPL, see LICENSE.php
 * Joomla Tracks is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * See COPYRIGHT.php for copyright notices and details.
 */
defined('_JEXEC') or die('Restricted access');
?>

<div id="tracks">

	<h2 class="tracks-title"><?php echo $this->title; ?></h2>

	<table class="raceResults" cellspacing="0" cellpadding="0" summary="">
		<thead>
		<tr>
			<th class="rank"><?php echo JText::_('COM_TRACKS_POSITION_SHORT'); ?></th>

			<?php if ($this->params->get('shownumber')): ?>
				<th class="number"><?php echo JText::_('COM_TRACKS_NUMBER_SHORT'); ?></th>
			<?php endif; ?>

			<?php if ($this->params->get('showflag')): ?>
				<th class="country"><?php echo JText::_('COM_TRACKS_COUNTRY_SHORT'); ?></th>
			<?php endif; ?>

			<th class="name"><?php echo JText::_('COM_TRACKS_Individual'); ?></th>

			<?php if ($this->params->get('shownickname')): ?>
				<th class="nickname"><?php echo JText::_('COM_TRACKS_nickname'); ?></th>
			<?php endif; ?>

			<?php if ($this->params->get('showteams')): ?>
				<th class="team"><?php echo JText::_('COM_TRACKS_Team'); ?></th>
			<?php endif; ?>

			<?php foreach ($this->rounds as $r): ?>
				<th colspan="<?php echo count($r->subrounds); ?>" class="hasTip result"
				    title="<?php echo $r->round_name; ?>"><?php echo substr($r->short_name, 0, 6); ?></th>
			<?php endforeach; ?>

			<th class="points"><?php echo JText::_('COM_TRACKS_Points'); ?></th>
		</tr>
		</thead>
		<tbody>
		<?php
		$i = 0;
		foreach ($this->rows AS $ranking)
		{
			$link_ind = JRoute::_(TrackslibHelperRoute::getIndividualRoute($ranking->slug, $this->project->slug));
			$link_team = JRoute::_(TrackslibHelperRoute::getTeamRoute($ranking->teamslug, $this->project->slug));
			?>
			<tr class="<?php echo($i ? 'd1' : 'd0'); ?>">
				<td class="rank"><?php echo $ranking->rank; ?></td>

				<?php if ($this->params->get('shownumber')): ?>
					<td class="number"><?php echo $ranking->number; ?></td>
				<?php endif; ?>

				<?php if ($this->params->get('showflag')): ?>
					<td class="country">
						<?php if ($ranking->country_code): ?>
							<?php echo TrackslibHelperCountries::getCountryFlag($ranking->country_code); ?>
						<?php endif; ?>
					</td>
				<?php endif; ?>

				<td class="name">
					<a href="<?php echo $link_ind; ?>"
					   title="<?php echo JText::_('COM_TRACKS_Details'); ?>">
						<?php echo $ranking->first_name . ' ' . $ranking->last_name; ?>
					</a>
				</td>
				<?php if ($this->params->get('shownickname')): ?>
					<td class="nickname">
						<?= $ranking->nickname; ?>
					</td>
				<?php endif; ?>
				<?php if ($this->params->get('showteams')): ?>
					<td class="team">
						<?php if ($ranking->team_id): ?>
							<a href="<?php echo $link_team; ?>"
							   title="<?php echo JText::_('COM_TRACKS_Details'); ?>">
								<?php echo $ranking->team_name; ?></a>
						<?php endif; ?>
					</td>
				<?php endif; ?>

				<?php $i = 1;?>
				<?php foreach ($this->rounds as $round): ?>
					<?php foreach ($round->subrounds as $subround): ?>
						<td class="result"><?php echo isset($ranking->results[$subround->event_id]) ? $ranking->results[$subround->event_id] : '-'; ?></td>
					<?php endforeach; ?>
				<?php endforeach; ?>

				<td class="points"><?php echo $ranking->points; ?></td>

			</tr>
			<?php
			$i = 1 - $i;
		}
		?>
		</tbody>
	</table>
	<p class="copyright">
		<?php echo TrackslibHelperTools::footer(); ?>
	</p>
</div>

