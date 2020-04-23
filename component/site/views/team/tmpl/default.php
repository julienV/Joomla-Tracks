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

defined('_JEXEC') or die('Restricted access'); ?>

<div id="tracks" class="tracks-team">
	<!-- Title -->
	<h2><?php echo $this->data->name; ?></h2>
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

	<?php if (!empty($this->individuals)): ?>
	<section class="tracks-team__individuals">
		<h3 class="tracks-team__individuals__title">
			<?php echo JTExt::_('COM_TRACKS_VIEW_TEAM_INDIVIDUALS'); ?>
		</h3>
		<?php foreach ($this->individuals as $proj): ?>
		<div class="tracks-team__individuals__project">
			<div class="tracks-team__individuals__project__title">
				<?php echo current($proj)->project_name; ?>
			</div>
			<div class="tracks-team__individuals__project__individuals">
			<?php foreach ($proj as $i): ?>
				<?php $text = ($i->number ? $i->number.' ' : '').$i->first_name.' '.$i->last_name; ?>
				<div class="tracks-team__individuals__project__individuals__individual">
					<?php echo JHTML::link(TrackslibHelperRoute::getIndividualRoute($i->slug, $i->projectslug), $text); ?>
				</div>
			<?php endforeach; ?>
			</div>
		</div>
		<?php endforeach; ?>
	</section>
	<?php endif; ?>

	<?php echo $this->loadTemplate('social'); ?>

	<p class="copyright">
	  <?php echo TrackslibHelperTools::footer( ); ?>
	</p>
</div>
