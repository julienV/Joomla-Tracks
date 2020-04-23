<?php
/**
 * @package     Tracks
 * @subpackage  Admin
 * @copyright   Tracks (C) 2008-2015 Julien Vonthron. All rights reserved.
 * @license     GNU General Public License version 2 or later
 */

use Joomla\CMS\Language\Text;

defined('_JEXEC') or die;

JHtml::_('rbootstrap.tooltip');
JHtml::_('rjquery.chosen', 'select');
JHtml::_('behavior.formvalidator');

/**
 * @var RForm $form
 */
$form            = $this->form;
$customFieldsets = $form->getFieldsets('com_fields');

Text::script('LIB_TRACKS_VALIDATION_URL_INVALID');
?>
<script type="text/javascript">
	Joomla.submitbutton = function (pressbutton) {
		if (pressbutton == 'team.cancel') {
			Joomla.submitform(pressbutton);
		}
		else {
			var f = document.adminForm;
			if (document.formvalidator.isValid(f)) {
				Joomla.submitform(pressbutton);
			}
		}
	}
	jQuery(document).ready(function(){
		document.formvalidator.setHandler('url', function(value, element) {
			var expression = /https?:\/\/(www\.)?[-a-zA-Z0-9@:%._\+~#=]{1,256}\.[a-zA-Z0-9()]{1,6}\b([-a-zA-Z0-9()@:%_\+.~#?&//=]*)/;
			var regex = new RegExp(expression);

			if (!regex.test(value)) {
				element.get(0).setCustomValidity(Joomla.JText._('LIB_TRACKS_VALIDATION_URL_INVALID'))
				return false;
			}

			element.get(0).setCustomValidity('');

			return true;
		});
	});
</script>
<form
	action="index.php?option=com_tracks&task=team.edit&id=<?php echo $this->item->id; ?>"
	method="post" name="adminForm" class="form-validate form-horizontal" id="adminForm">

	<ul class="nav nav-tabs" id="teamTab">
		<li class="active">
			<a href="#basic" data-toggle="tab">
				<strong><?php echo JText::_('COM_TRACKS_TEAM'); ?></strong>
			</a>
		</li>
		<li>
			<a href="#vehicle_group" data-toggle="tab">
				<strong><?php echo JText::_('COM_TRACKS_TEAM_GROUP_VEHICLE'); ?></strong>
			</a>
		</li>
		<li>
			<a href="#social" data-toggle="tab">
				<strong><?php echo JText::_('COM_TRACKS_TEAM_SOCIAL_LINKS'); ?></strong>
			</a>
		</li>
		<?php if (!empty($customFieldsets)): ?>
			<li>
				<a href="#custom" data-toggle="tab">
					<strong><?php echo JText::_('COM_TRACKS_CUSTOM_FIELDS'); ?></strong>
				</a>
			</li>
		<?php endif; ?>
	</ul>

	<div class="tab-content">
		<div class="tab-pane active" id="basic">
			<div class="row-fluid">
				<div class="control-group">
					<div class="control-label">
						<?php echo $this->form->getLabel('name'); ?>
					</div>
					<div class="controls">
						<?php echo $this->form->getInput('name'); ?>
					</div>
				</div>
				<div class="control-group">
					<div class="control-label">
						<?php echo $this->form->getLabel('alias'); ?>
					</div>
					<div class="controls">
						<?php echo $this->form->getInput('alias'); ?>
					</div>
				</div>
				<div class="control-group">
					<div class="control-label">
						<?php echo $this->form->getLabel('short_name'); ?>
					</div>
					<div class="controls">
						<?php echo $this->form->getInput('short_name'); ?>
					</div>
				</div>
				<div class="control-group">
					<div class="control-label">
						<?php echo $this->form->getLabel('acronym'); ?>
					</div>
					<div class="controls">
						<?php echo $this->form->getInput('acronym'); ?>
					</div>
				</div>
				<div class="control-group">
					<div class="control-label">
						<?php echo $this->form->getLabel('published'); ?>
					</div>
					<div class="controls">
						<?php echo $this->form->getInput('published'); ?>
					</div>
				</div>
				<div class="control-group">
					<div class="control-label">
						<?php echo $this->form->getLabel('picture'); ?>
					</div>
					<div class="controls">
						<?php echo $this->form->getInput('picture'); ?>
					</div>
				</div>
				<div class="control-group">
					<div class="control-label">
						<?php echo $this->form->getLabel('picture_small'); ?>
					</div>
					<div class="controls">
						<?php echo $this->form->getInput('picture_small'); ?>
					</div>
				</div>
				<div class="control-group">
					<div class="control-label">
						<?php echo $this->form->getLabel('country_code'); ?>
					</div>
					<div class="controls">
						<?php echo $this->form->getInput('country_code'); ?>
					</div>
				</div>
				<div class="control-group">
					<div class="control-label">
						<?php echo $this->form->getLabel('admin_id'); ?>
					</div>
					<div class="controls">
						<?php echo $this->form->getInput('admin_id'); ?>
					</div>
				</div>
				<div class="control-group">
					<div class="control-label">
						<?php echo $this->form->getLabel('description'); ?>
					</div>
					<div class="controls">
						<?php echo $this->form->getInput('description'); ?>
					</div>
				</div>
			</div>
		</div>

		<div class="tab-pane" id="vehicle_group">
			<div class="well fieldset-description"><?php echo JText::_('COM_TRACKS_TEAM_GROUP_VEHICLE_DESC'); ?></div>
			<div class="row-fluid">
				<div class="control-group">
					<div class="control-label">
						<?php echo $this->form->getLabel('vehicle_picture'); ?>
					</div>
					<div class="controls">
						<?php echo $this->form->getInput('vehicle_picture'); ?>
					</div>
				</div>
				<div class="control-group">
					<div class="control-label">
						<?php echo $this->form->getLabel('vehicle_description'); ?>
					</div>
					<div class="controls">
						<?php echo $this->form->getInput('vehicle_description'); ?>
					</div>
				</div>
			</div>
		</div>

		<div class="tab-pane" id="social">
			<div class="row-fluid">
				<?php foreach ($this->form->getFieldset('social') as $field): ?>
					<div class="control-group">
						<div class="control-label">
							<?php echo $field->label; ?>
						</div>
						<div class="controls">
							<?php echo $field->input; ?>
						</div>
					</div>
				<?php endforeach; ?>
			</div>
		</div>
		<div class="tab-pane" id="custom">
			<div class="row-fluid">
				<?php foreach ($customFieldsets as $fieldset): ?>
					<?php if ($fieldset->label != 'JGLOBAL_FIELDS'): ?>
						<div class="well fieldset-description">
							<div class="fieldset-description__name"><?= $fieldset->label ?></div>
							<div class="fieldset-description__content"><?=  $fieldset->description ?></div>
						</div>
					<?php endif; ?>
					<?php foreach ($form->getFieldset($fieldset->name) as $field): ?>
						<div class="control-group">
							<div class="control-label">
								<?php echo $field->label; ?>
							</div>
							<div class="controls">
								<?php echo $field->input; ?>
							</div>
						</div>
					<?php endforeach; ?>
				<?php endforeach; ?>
			</div>
		</div>
	</div>
	<?php echo $this->form->getInput('id'); ?>
	<input type="hidden" name="task" value="" />
	<?php echo JHtml::_('form.token'); ?>
</form>
