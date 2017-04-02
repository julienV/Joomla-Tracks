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
		jQuery(".btn-group").each(function(index) {
			if (jQuery(this).hasClass('disabled'))
			{
				jQuery(this).find("label").off('click');
			}
		});
	});
</script>
<form
	action="index.php?option=com_tracks&task=eventresult.edit&id=<?php echo $this->item->id; ?>"
	method="post" name="adminForm" class="form-validate form-horizontal" id="adminForm">

		<div class="row-fluid">
			<div class="control-group">
				<div class="control-label">
					<?php echo $this->form->getLabel('number'); ?>
				</div>
				<div class="controls">
					<?php echo $this->form->getInput('number'); ?>
				</div>
			</div>
			<div class="control-group">
				<div class="control-label">
					<?php echo $this->form->getLabel('individual_id'); ?>
				</div>
				<div class="controls">
					<?php echo $this->form->getInput('individual_id'); ?>
				</div>
			</div>
			<div class="control-group">
				<div class="control-label">
					<?php echo $this->form->getLabel('team_id'); ?>
				</div>
				<div class="controls">
					<?php echo $this->form->getInput('team_id'); ?>
				</div>
			</div>
			<div class="control-group">
				<div class="control-label">
					<?php echo $this->form->getLabel('rank'); ?>
				</div>
				<div class="controls">
					<?php echo $this->form->getInput('rank'); ?>
				</div>
			</div>
			<div class="control-group">
				<div class="control-label">
					<?php echo $this->form->getLabel('bonus_points'); ?>
				</div>
				<div class="controls">
					<?php echo $this->form->getInput('bonus_points'); ?>
				</div>
			</div>
			<div class="control-group">
				<div class="control-label">
					<?php echo $this->form->getLabel('performance'); ?>
				</div>
				<div class="controls">
					<?php echo $this->form->getInput('performance'); ?>
				</div>
			</div>
			<div class="control-group">
				<div class="control-label">
					<?php echo $this->form->getLabel('comment'); ?>
				</div>
				<div class="controls">
					<?php echo $this->form->getInput('comment'); ?>
				</div>
			</div>
		</div>
	<?php echo $this->form->getInput('event_id'); ?>
	<?php echo $this->form->getInput('id'); ?>
	<input type="hidden" name="task" value="" />
	<?php echo JHtml::_('form.token'); ?>
</form>
