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

JHtml::_('rbootstrap.tooltip');
JHtml::_('rjquery.chosen', 'select');
JHtml::_('behavior.formvalidation');
?>

<div id="tracks">
	<!-- Title -->
	<h2><?php echo JText::_('COM_TRACKS_PAGETITLE_EDIT_INDIVIDUAL'); ?></h2>

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

			jQuery('#individual-save').click(function() {
				var form = jQuery(this).parents('form');
				form.find('input[name=task]').val('profile.save');
				if (document.formvalidator.isValid(form)) {
					form.submit();
				}
			});

			jQuery('#individual-cancel').click(function() {
				var form = jQuery(this).parents('form');
				form.find('input[name=task]').val('profile.cancel');
				form.submit();
			});
		});
	</script>
	<form
		action="index.php?option=com_tracks&task=profile.edit&id=<?php echo $this->item->id; ?>"
		method="post" name="adminForm" class="form-validate form-horizontal" id="adminForm" enctype="multipart/form-data">

		<div class="toolbar">
			<button type="button" class="btn btn-success" id="individual-save"><?php echo JText::_('COM_TRACKS_SAVE'); ?></php></button>
			<button type="button" class="btn btn-danger" id="individual-cancel"><?php echo JText::_('COM_TRACKS_CANCEL'); ?></php></button>
		</div>
		<ul class="nav nav-tabs" id="individualTab">
			<li class="active">
				<a href="#basic" data-toggle="tab">
					<strong><?php echo JText::_('COM_TRACKS_INDIVIDUAL'); ?></strong>
				</a>
			</li>
			<li>
				<a href="#address" data-toggle="tab">
					<strong><?php echo JText::_('COM_TRACKS_INDIVIDUALS_GROUP_ADDRESS'); ?></strong>
				</a>
			</li>
		</ul>

		<div class="tab-content">
			<div class="tab-pane active" id="basic">
				<div class="row-fluid">
					<div class="control-group">
						<div class="control-label">
							<?php echo $this->form->getLabel('first_name'); ?>
						</div>
						<div class="controls">
							<?php echo $this->form->getInput('first_name'); ?>
						</div>
					</div>
					<div class="control-group">
						<div class="control-label">
							<?php echo $this->form->getLabel('last_name'); ?>
						</div>
						<div class="controls">
							<?php echo $this->form->getInput('last_name'); ?>
						</div>
					</div>
					<?php if ((!$this->item->user_id) && (!$this->userIndividual) && $this->user->authorise('core.manage', 'com_tracks')): ?>
					<div class="control-group">
						<div class="control-label">
							<?php echo $this->form->getLabel('assign_me'); ?>
						</div>
						<div class="controls">
							<?php echo $this->form->getInput('assign_me'); ?>
						</div>
					</div>
					<?php endif; ?>
					<div class="control-group">
						<div class="control-label">
							<?php echo $this->form->getLabel('nickname'); ?>
						</div>
						<div class="controls">
							<?php echo $this->form->getInput('nickname'); ?>
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
							<?php echo $this->form->getLabel('country_code'); ?>
						</div>
						<div class="controls">
							<?php echo $this->form->getInput('country_code'); ?>
						</div>
					</div>
					<div class="control-group">
						<div class="control-label">
							<?php echo $this->form->getLabel('height'); ?>
						</div>
						<div class="controls">
							<?php echo $this->form->getInput('height'); ?>
						</div>
					</div>
					<div class="control-group">
						<div class="control-label">
							<?php echo $this->form->getLabel('weight'); ?>
						</div>
						<div class="controls">
							<?php echo $this->form->getInput('weight'); ?>
						</div>
					</div>
					<div class="control-group">
						<div class="control-label">
							<?php echo $this->form->getLabel('dob'); ?>
						</div>
						<div class="controls">
							<?php echo $this->form->getInput('dob'); ?>
						</div>
					</div>
					<div class="control-group">
						<div class="control-label">
							<?php echo $this->form->getLabel('hometown'); ?>
						</div>
						<div class="controls">
							<?php echo $this->form->getInput('hometown'); ?>
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
							<?php echo $this->form->getLabel('description'); ?>
						</div>
						<div class="controls">
							<?php echo $this->form->getInput('description'); ?>
						</div>
					</div>
				</div>
			</div>

			<div class="tab-pane" id="address">
				<div class="well fieldset-description"><?php echo JText::_('COM_TRACKS_INDIVIDUALS_GROUP_ADDRESS_DESC'); ?></div>
				<div class="row-fluid">
					<div class="control-group">
						<div class="control-label">
							<?php echo $this->form->getLabel('address'); ?>
						</div>
						<div class="controls">
							<?php echo $this->form->getInput('address'); ?>
						</div>
					</div>
					<div class="control-group">
						<div class="control-label">
							<?php echo $this->form->getLabel('postcode'); ?>
						</div>
						<div class="controls">
							<?php echo $this->form->getInput('postcode'); ?>
						</div>
					</div>
					<div class="control-group">
						<div class="control-label">
							<?php echo $this->form->getLabel('city'); ?>
						</div>
						<div class="controls">
							<?php echo $this->form->getInput('city'); ?>
						</div>
					</div>
					<div class="control-group">
						<div class="control-label">
							<?php echo $this->form->getLabel('state'); ?>
						</div>
						<div class="controls">
							<?php echo $this->form->getInput('state'); ?>
						</div>
					</div>
					<div class="control-group">
						<div class="control-label">
							<?php echo $this->form->getLabel('country'); ?>
						</div>
						<div class="controls">
							<?php echo $this->form->getInput('country'); ?>
						</div>
					</div>
				</div>
			</div>

		</div>
		<?php echo $this->form->getInput('id'); ?>
		<input type="hidden" name="task" value="" />
		<?php echo JHtml::_('form.token'); ?>
	</form>

</div><!-- end of tracks -->
