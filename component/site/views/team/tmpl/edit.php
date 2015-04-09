<?php
/**
 * @package     Tracks
 * @subpackage  Admin
 * @copyright   Tracks (C) 2008-2015 Julien Vonthron. All rights reserved.
 * @license     GNU General Public License version 2 or later
 */

defined('_JEXEC') or die;

JHtml::_('rbootstrap.tooltip');
JHtml::_('rjquery.chosen', 'select');
JHtml::_('behavior.formvalidation');
?>
<script type="text/javascript">
	jQuery(document).ready(function()
	{
		// Disable click function on btn-group
		jQuery(".btn-group").each(function(index){
			if (jQuery(this).hasClass('disabled'))
			{
				jQuery(this).find("label").off('click');
			}
		});

		jQuery('#team-save').click(function() {
			var form = jQuery(this).parents('form');
			form.find('input[name=task]').val('team.save');
			if (document.formvalidator.isValid(form)) {
				form.submit();
			}
		});

		jQuery('#team-cancel').click(function() {
			var form = jQuery(this).parents('form');
			form.find('input[name=task]').val('team.cancel');
			form.submit();
		});
	});
</script>
<form
	action="index.php?option=com_tracks&task=team.edit&id=<?php echo $this->item->id; ?>"
	method="post" name="adminForm" class="form-validate form-horizontal" id="adminForm" enctype="multipart/form-data">

	<div class="toolbar">
		<button type="button" class="btn btn-success" id="team-save"><?php echo JText::_('COM_TRACKS_SAVE'); ?></php></button>
		<button type="button" class="btn btn-danger" id="team-cancel"><?php echo JText::_('COM_TRACKS_CANCEL'); ?></php></button>
	</div>

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
						<span class="current-pic>"><?php echo ($this->item->picture) ? $this->item->picture : ''; ?></span>
					</div>
				</div>
				<div class="control-group">
					<div class="control-label">
						<?php echo $this->form->getLabel('picture_small'); ?>
					</div>
					<div class="controls">
						<?php echo $this->form->getInput('picture_small'); ?>
						<span class="current-pic>"><?php echo ($this->item->picture_small) ? $this->item->picture_small : ''; ?></span>
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
						<span class="current-pic>"><?php echo ($this->item->vehicle_picture) ? $this->item->vehicle_picture : ''; ?></span>
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
				<div class="control-group">
					<div class="control-label">
						<?php echo $this->form->getLabel('url'); ?>
					</div>
					<div class="controls">
						<?php echo $this->form->getInput('url'); ?>
					</div>
				</div>
				<div class="control-group">
					<div class="control-label">
						<?php echo $this->form->getLabel('facebook'); ?>
					</div>
					<div class="controls">
						<?php echo $this->form->getInput('facebook'); ?>
					</div>
				</div>
				<div class="control-group">
					<div class="control-label">
						<?php echo $this->form->getLabel('twitter'); ?>
					</div>
					<div class="controls">
						<?php echo $this->form->getInput('twitter'); ?>
					</div>
				</div>
				<div class="control-group">
					<div class="control-label">
						<?php echo $this->form->getLabel('facebook'); ?>
					</div>
					<div class="controls">
						<?php echo $this->form->getInput('facebook'); ?>
					</div>
				</div>
				<div class="control-group">
					<div class="control-label">
						<?php echo $this->form->getLabel('googleplus'); ?>
					</div>
					<div class="controls">
						<?php echo $this->form->getInput('googleplus'); ?>
					</div>
				</div>
				<div class="control-group">
					<div class="control-label">
						<?php echo $this->form->getLabel('youtube'); ?>
					</div>
					<div class="controls">
						<?php echo $this->form->getInput('youtube'); ?>
					</div>
				</div>
				<div class="control-group">
					<div class="control-label">
						<?php echo $this->form->getLabel('instagram'); ?>
					</div>
					<div class="controls">
						<?php echo $this->form->getInput('instagram'); ?>
					</div>
				</div>
				<div class="control-group">
					<div class="control-label">
						<?php echo $this->form->getLabel('pinterest'); ?>
					</div>
					<div class="controls">
						<?php echo $this->form->getInput('pinterest'); ?>
					</div>
				</div>
				<div class="control-group">
					<div class="control-label">
						<?php echo $this->form->getLabel('vimeo'); ?>
					</div>
					<div class="controls">
						<?php echo $this->form->getInput('vimeo'); ?>
					</div>
				</div>
			</div>
		</div>
	</div>
	<?php echo $this->form->getInput('id'); ?>
	<input type="hidden" name="task" value="" />
	<?php echo JHtml::_('form.token'); ?>
</form>
