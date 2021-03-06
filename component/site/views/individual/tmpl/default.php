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
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\Registry\Registry;
use Tracks\Helper\Config;

defined('_JEXEC') or die('Restricted access');

$link = null;
$res = $this->dispatcher->trigger('getProfileLink', array($this->data->user_id, &$link));
$extra = new Registry($this->data->params);
?>
<div id="tracks" class="tracks-individual">
	<!-- Title -->
	<h2 class="tracks-title"><?php echo TrackslibHelperTools::formatIndividualName($this->data); ?></h2>

	<?php if ($this->show_edit_link): ?>
		<div id="editprofile"class="tracks-individual__editprofile">
			<a
				href="<?php echo JRoute::_(TrackslibHelperRoute::getEditIndividualRoute($this->data->id)); ?>"
				title="<?php echo Text::_('COM_TRACKS_Edit_profile') ?>">
				<?php echo Text::_('COM_TRACKS_Edit_profile'); ?>
			</a>
		</div>
	<?php endif; ?>

	<?php if (!empty($link)): ?>
		<div class="tracks-individual__profile-link">
			<?php echo $link->text; ?>
		</div>
	<?php endif; ?>

	<div class="tracks-individual__details">
		<div class="tracks-individual__details__picture">
			<?php if (empty($this->data->picture)): ?>
				<?= HTMLHelper::image('com_tracks/individuals/placeholder_individual.png', TrackslibHelperTools::formatIndividualName($this->data), null, true); ?>
			<?php else: ?>
				<img src="<?= $this->data->picture ?>" alt="<?php echo TrackslibHelperTools::formatIndividualName($this->data); ?>"/>
			<?php endif; ?>
		</div>

		<div class="tracks-individual__details__list">
			<?php if (!empty($this->data->projectdata->number)): ?>
				<div class="tracks-individual__details__list__item">
					<div class="tracks-individual__details__list__item__label">
							<?php echo Text::_('COM_TRACKS_INDIVIDUAL_NUMBER'); ?>:
					</div>
					<div class="tracks-individual__details__list__item__value">
						<?php echo $this->data->projectdata->number; ?>
					</div>
				</div>
			<?php endif; ?>
			<?php if (!empty($this->data->team_id)): ?>
				<?php $team = TrackslibEntityTeam::load($this->data->team_id); ?>
				<div class="tracks-individual__details__list__item">
					<div class="tracks-individual__details__list__item__label">
						<?php echo Text::_('COM_TRACKS_INDIVIDUAL_TEAM'); ?>:
					</div>
					<div class="tracks-individual__details__list__item__value">
							<?php echo JHTML::link(TrackslibHelperRoute::getTeamRoute($team->id), $team->name); ?>
					</div>
				</div>
			<?php endif; ?>
			<?php if (!empty($this->data->nickname)): ?>
				<div class="tracks-individual__details__list__item">
					<div class="tracks-individual__details__list__item__label">
						<?php echo Text::_('COM_TRACKS_Nickname'); ?>:
					</div>
					<div class="tracks-individual__details__list__item__value">
						<?php echo $this->data->nickname; ?>
					</div>
				</div>
			<?php endif; ?>
			<?php if ($this->data->country_code): ?>
				<div class="tracks-individual__details__list__item">
					<div class="tracks-individual__details__list__item__label">
						<?php echo Text::_('COM_TRACKS_Country'); ?>:
					</div>
					<div class="tracks-individual__details__list__item__value">
						<?php echo TrackslibHelperCountries::getCountryFlag($this->data->country_code); ?>
					</div>
				</div>
			<?php endif; ?>
			<?php if (TrackslibHelperTools::isValidDate($this->data->dob) && Config::get('show_dob', 1)): ?>
				<div class="tracks-individual__details__list__item">
					<div class="tracks-individual__details__list__item__label">
						<?php echo Text::_('COM_TRACKS_Date_of_birth'); ?>:
					</div>
					<div class="tracks-individual__details__list__item__value">
						<?php echo TrackslibHelperTools::formatDate($this->data->dob); ?>
					</div>
				</div>
			<?php endif; ?>
			<?php if (!empty($extra->get('gender')) && Config::get('show_gender', 1)): ?>
				<div class="tracks-individual__details__list__item">
					<div class="tracks-individual__details__list__item__label">
						<?php echo Text::_('LIB_TRACKS_GENDER'); ?>:
					</div>
					<div class="tracks-individual__details__list__item__value">
						<?php echo Text::_('LIB_TRACKS_GENDER_' . $extra->get('gender')); ?>
					</div>
				</div>
			<?php endif; ?>
			<?php if (!empty($this->data->height)): ?>
				<div class="tracks-individual__details__list__item">
					<div class="tracks-individual__details__list__item__label">
						<?php echo Text::_('COM_TRACKS_Height'); ?>:
					</div>
					<div class="tracks-individual__details__list__item__value">
							<?php echo $this->data->height; ?>
					</div>
				</div>
			<?php endif; ?>
			<?php if ($this->data->weight): ?>
			<div class="tracks-individual__details__list__item">
				<div class="tracks-individual__details__list__item__label">
					<?php echo Text::_('COM_TRACKS_Weight'); ?>:
				</div>
				<div class="tracks-individual__details__list__item__value">
					<?php echo $this->data->weight; ?>
				</div>
			</div>
			<?php endif; ?>
			<?php if ($this->data->hometown): ?>
				<div class="tracks-individual__details__list__item">
					<div class="tracks-individual__details__list__item__label">
						<?php echo Text::_('COM_TRACKS_Hometown'); ?>:
					</div>
					<div class="tracks-individual__details__list__item__value">
						<?php echo $this->data->hometown; ?>
					</div>
				</div>
			<?php endif; ?>
			<?php if ($this->data->address): ?>
				<div class="tracks-individual__details__list__item">
					<div class="tracks-individual__details__list__item__label">
						<?php echo Text::_('COM_TRACKS_Address'); ?>:
					</div>
					<div class="tracks-individual__details__list__item__value">
						<?php echo $this->data->address; ?>
					</div>
				</div>
			<?php endif; ?>
			<?php if ($this->data->postcode): ?>
				<div class="tracks-individual__details__list__item">
					<div class="tracks-individual__details__list__item__label">
						<?php echo Text::_('COM_TRACKS_Postcode'); ?>:
					</div>
					<div class="tracks-individual__details__list__item__value">
						<?php echo $this->data->postcode; ?>
					</div>
				</div>
			<?php endif; ?>
			<?php if ($this->data->city): ?>
				<div class="tracks-individual__details__list__item">
					<div class="tracks-individual__details__list__item__label">
						<?php echo Text::_('COM_TRACKS_City'); ?>:
					</div>
					<div class="tracks-individual__details__list__item__value">
						<?php echo $this->data->city; ?>
					</div>
				</div>
			<?php endif; ?>
			<?php if ($this->data->state): ?>
				<div class="tracks-individual__details__list__item">
					<div class="tracks-individual__details__list__item__label">
						<?php echo Text::_('COM_TRACKS_State'); ?>:
					</div>
					<div class="tracks-individual__details__list__item__value">
						<?php echo $this->data->state; ?>
					</div>
				</div>
			<?php endif; ?>
			<?php if ($this->data->country): ?>
				<div class="tracks-individual__details__list__item">
					<div class="tracks-individual__details__list__item__label">
						<?php echo Text::_('COM_TRACKS_Country'); ?>:
					</div>
					<div class="tracks-individual__details__list__item__value">
						<?php echo $this->data->country; ?>
					</div>
				</div>
			<?php endif; ?>

			<?php if (!empty($this->data->jcfields)): ?>
			<?php foreach ($this->data->jcfields as $customField): ?>
				<?php if (!empty($customField->rawvalue)): ?>
					<div class="tracks-individual__details__list__item">
						<div class="tracks-individual__details__list__item__label">
							<?php echo $customField->title; ?>:
						</div>
						<div class="tracks-individual__details__list__item__value">
							<?php echo $customField->value; ?>
						</div>
					</div>
				<?php endif; ?>
			<?php endforeach; ?>
			<?php endif; ?>

			<?php if ($this->data->description): ?>
				<div class="tracks-individual__details__list__item">
					<div class="tracks-individual__details__list__item__value">
						<?php echo $this->data->description; ?>
					</div>
				</div>
			<?php endif; ?>
		</div>
	</div>

	<?php if ($this->params->get('indview_show_stats', 1)): ?>
		<?php echo $this->loadTemplate('stats'); ?>
	<?php endif; ?>

	<?php if ($this->params->get('indview_show_ranks', 1)): ?>
		<?php echo $this->loadTemplate('rankings'); ?>
	<?php endif; ?>

	<?php if ($this->params->get('indview_showresults', 1)): ?>
		<?php echo $this->loadTemplate('results'); ?>
	<?php endif; ?>

	<p class="copyright">
		<?php echo TrackslibHelperTools::footer(); ?>
	</p>

</div><!-- end of tracks -->
