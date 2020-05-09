<?php
/**
* @version    $Id: default.php 101 2008-05-22 08:32:12Z julienv $
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
use Joomla\CMS\Language\Text;
use Tracks\Helper\Config;

defined('_JEXEC') or die('Restricted access');

$entity        = TrackslibEntityRound::load($this->round->id);
$projectRounds = $entity->getProjectrounds(
	[
		'filter.published' => 1,
		'list.ordering'    => 'obj.start_date',
		'list.direction'   => 'desc',
	]
);
?>
<div id="tracks<?php echo $this->params->get('pageclass_sfx'); ?>" class="tracks-round">
	<?php if ($this->params->get('show_page_heading', 1)) : ?>
	<h1>
		<?php echo $this->escape($this->params->get('page_heading')); ?>
	</h1>
	<?php endif; ?>

	<h2><?php echo $this->round->name; ?></h2>

	<!-- Content -->
	<div class="round-description">
	<?php if ($img = TrackslibHelperImage::modalimage($this->round->picture, $this->round->name, 400, array('class' => 'round-image'))): ?>
		<?php echo $img; ?>
	<?php endif;?>
		<div class="round-description__field">
			<span class="round-description__field__label"><?php echo JText::_('COM_TRACKS_Country'); ?>:</span> <?php echo TrackslibHelperCountries::getCountryFlag($this->round->country); ?>
		</div>
	<?php echo $this->round->description; ?>
	</div>

	<?php if (!empty($projectRounds)): ?>
	<section class="tracks-round__events">
		<h3><?= Text::_('COM_TRACKS_ROUND_EVENTS') ?></h3>

		<table class="tracks-round__events__list">
			<thead>
				<tr>
					<th class="tracks-round__events__list__event__project"><?= Text::_('COM_TRACKS_COMPETITION') ?></th>
					<th class="tracks-round__events__list__event__date"><?= Text::_('COM_TRACKS_DATE') ?></th>
					<th class="tracks-round__events__list__event__winners"><?= Text::_('COM_TRACKS_WINNER') ?></th>
				</tr>
			</thead>
			<tbody>
			<?php foreach ($projectRounds as $projectRound): ?>
				<tr class="tracks-round__events__list__event">
					<td class="tracks-round__events__list__event__project">
						<a href="<?= $projectRound->getLink() ?>">
							<?= $projectRound->getProject()->name; ?>
						</a>
					</td>
					<td class="tracks-round__events__list__event__date">
						<?= $projectRound->getDate('start_date', Config::get('date_format', 'Y-m-d')) ?>
					</td>
					<td class="tracks-round__events__list__event__winners">
						<?php $winners = $projectRound->getWinner(); ?>
						<?php if (!empty($winners)): ?>
							<?php foreach ($winners as $winner): ?>
							<div class="tracks-round__events__list__event__winners__winner">
								<a href="<?= TrackslibHelperRoute::getIndividualRoute($winner->id) ?>">
										<span class="tracks-round__events__list__event__winners__winner__name">
										<?= $winner->first_name . ' ' . $winner->last_name ?>
										</span>
									<?php if ($this->params->get('shownickname', 0) && !empty($winner->nickname)): ?>
										<span class="tracks-round__events__list__event__winners__winner__nickname">
											(<?= $winner->nickname ?>)
										</span>
									<?php endif; ?>
								</a>
								<?php if ($projectRound->getProject()->getParam('showteams', 1)): ?>
								- <a href="<?= TrackslibHelperRoute::getTeamRoute($winner->team_id) ?>">
									<span class="winner__team">
									<?= $winner->team_name ?>
									</span>
									</a>
								<?php endif; ?>
							</div>
						<?php endforeach; ?>
						<?php endif; ?>
					</td>
				</tr>
			<?php endforeach; ?>
			</tbody>
		</table>
	</section>
	<?php endif; ?>

	<p class="copyright">
	  <?php echo TrackslibHelperTools::footer( ); ?>
	</p>
</div>
