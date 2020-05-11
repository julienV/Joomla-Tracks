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
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Router\Route;

defined('_JEXEC') or die('Restricted access');

$team = TrackslibEntityTeam::load($this->data->id);
?>

<div id="tracks" class="tracks-team">
	<!-- Title -->
	<h2 class="tracks-title"><?php echo $this->data->name; ?></h2>
		<?php if ($this->canEdit): ?>
			<?php
				$url = TrackslibHelperRoute::getTeamEditRoute($this->data->id);
				$link = JHtml::link($url, JText::_('COM_TRACKS_EDIT'));
			?>
			<div class="tracks-edit-link"><?php echo $link; ?></div>
		<?php endif; ?>

	<section class="tracks-team__details">
		<div class="tracks-team__details__picture">
			<?php if (empty($this->data->picture)): ?>
				<?= HTMLHelper::image('com_tracks/teams/placeholder_team.png', $this->data->name, null, true); ?>
			<?php else: ?>
				<img src="<?= $this->data->picture ?>" alt="<?php echo $this->data->name; ?>"/>
			<?php endif; ?>
		</div>

		<div class="tracks-team__details__list">
			<?php if ($this->data->country_code): ?>
				<div class="tracks-team__details__list__item">
					<div class="tracks-team__details__list__item__label">
						<?php echo JText::_('COM_TRACKS_Country'); ?>:
					</div>
					<div class="tracks-team__details__list__item__value">
						<?php echo TrackslibHelperCountries::getCountryFlag($this->data->country_code); ?>
					</div>
				</div>
			<?php endif; ?>

			<?php if (!empty($this->data->jcfields)): ?>
			<?php foreach ($this->data->jcfields as $customField): ?>
				<?php if (!empty($customField->rawvalue)): ?>
					<div class="tracks-team__details__list__item">
						<div class="tracks-team__details__list__item__label">
							<?php echo $customField->title; ?>:
						</div>
						<div class="tracks-team__details__list__item__value">
							<?php echo $customField->value; ?>
						</div>
					</div>
				<?php endif; ?>
			<?php endforeach; ?>
			<?php endif; ?>
			
			<?php if ($this->data->description): ?>
				<div class="tracks-team__details__list__item">
					<div class="tracks-team__details__list__item__value">
						<?php echo $this->data->description; ?>
					</div>
				</div>
			<?php endif; ?>
		</div>
	</section>

	<?php echo $this->loadTemplate('vehicle'); ?>

	<?php if (!empty($team->getProjects())): ?>
	<section class="tracks-team__individuals">
		<h3 class="tracks-team__individuals__title">
			<?= Text::_('COM_TRACKS_VIEW_TEAM_HISTORY'); ?>
		</h3>
		<?php foreach ($team->getProjects() as $project): ?>
		<?php $ranking = $project->getRankingTool()->getTeamRanking($team->id) ?>
		<div class="tracks-team__individuals__project">
			<div class="tracks-team__individuals__project__header">
				<div class="tracks-team__individuals__project__header__title">
					<a href="<?= Route::_(TrackslibHelperRoute::getProjectRoute($project->id)) ?>">
						<?php echo $project->name; ?>
					</a>
				</div>
				<div class="tracks-team__individuals__project__header__stats">
					<?= Text::_('COM_TRACKS_RANK'); ?>: <?= $ranking->rank ?> |
					<?= Text::_('COM_TRACKS_POINTS'); ?>: <?= $ranking->points ?> |
					<?= Text::_('COM_TRACKS_WINS'); ?>: <?= TrackslibHelperTools::getCustomTop($ranking, 1) ?> |
					<?= Text::_('COM_TRACKS_TABLE_HEADER_TOP3'); ?>: <?= TrackslibHelperTools::getCustomTop($ranking, 3) ?>
				</div>
			</div>
			<table class="tracks-team__individuals__project__individuals">
				<thead>
					<tr>
						<th class="tracks-team__individuals__project__individuals__name">
						</th>
						<th class="tracks-team__individuals__project__individuals__rank">
							<?= Text::_('COM_TRACKS_RANK'); ?>
						</th>
						<th class="tracks-team__individuals__project__individuals__points">
							<?= Text::_('COM_TRACKS_POINTS'); ?>
						</th>
						<th class="tracks-team__individuals__project__individuals__wins">
							<?= Text::_('COM_TRACKS_WINS'); ?>
						</th>
						<th class="tracks-team__individuals__project__individuals__podiums">
							<?= Text::_('COM_TRACKS_TABLE_HEADER_TOP3'); ?>
						</th>
					</tr>
				</thead>
				<tbody>
				<?php foreach ($team->getProjectParticipants($project->id) as $participant): ?>
					<tr>
						<td class="tracks-team__individuals__project__individuals__name">
							<a href="<?= Route::_(TrackslibHelperRoute::getIndividualRoute($participant->individual_id)) ?>">
								<?php if ($participant->number): ?>
								<span class="tracks-team__individuals__project__individuals__name__number"><?= $participant->number ?></span>
								<?php endif; ?><?= $participant->getFullName() ?>
							</a>
						</td>
						<td class="tracks-team__individuals__project__individuals__rank">
							<?= $participant->getRank() ?>
						</td>
						<td class="tracks-team__individuals__project__individuals__points">
							<?= $participant->getPoints() ?>
						</td>
						<td class="tracks-team__individuals__project__individuals__wins">
							<?= $participant->getTop(1) ?>
						</td>
						<td class="tracks-team__individuals__project__individuals__podiums">
							<?= $participant->getTop(3) ?>
						</td>
					</tr>
				<?php endforeach; ?>
				</tbody>
			</table>
		</div>
		<?php endforeach; ?>
	</section>
	<?php endif; ?>

	<?php echo $this->loadTemplate('social'); ?>

	<p class="copyright">
	  <?php echo TrackslibHelperTools::footer( ); ?>
	</p>
</div>
