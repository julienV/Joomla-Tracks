<?php
/**
 * @package     Tracks
 * @subpackage  Admin
 * @copyright   Tracks (C) 2008-2015 Julien Vonthron. All rights reserved.
 * @license     GNU General Public License version 2 or later
 */

defined('_JEXEC') or die;

JHtml::_('rjquery.chosen', 'select');
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
	});
</script>
<form
	action="index.php?option=com_tracks&task=project.edit&id=<?php echo $this->item->id; ?>"
	method="post" name="adminForm" class="form-validate form-horizontal" id="adminForm">

	<ul class="nav nav-tabs" id="individualTab">
		<li class="active">
			<a href="#main" data-toggle="tab">
				<strong><?php echo JText::_('COM_TRACKS_INDIVIDUAL'); ?></strong>
			</a>
		</li>
		<?php foreach ($this->form->getFieldsets('params') as $fieldset): ?>
		<li>
			<a href="#<?php echo $fieldset->name; ?>" data-toggle="tab">
				<strong><?php echo $fieldset->name; ?></strong>
			</a>
		</li>
		<?php endforeach; ?>
	</ul>

	<div class="tab-content">
		<div class="tab-pane active" id="main">
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
						<?php echo $this->form->getLabel('type'); ?>
					</div>
					<div class="controls">
						<?php echo $this->form->getInput('type'); ?>
					</div>
				</div>
				<div class="control-group">
					<div class="control-label">
						<?php echo $this->form->getLabel('competition_id'); ?>
					</div>
					<div class="controls">
						<?php echo $this->form->getInput('competition_id'); ?>
					</div>
				</div>
				<div class="control-group">
					<div class="control-label">
						<?php echo $this->form->getLabel('season_id'); ?>
					</div>
					<div class="controls">
						<?php echo $this->form->getInput('season_id'); ?>
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
			</div>
		</div>

		<?php foreach ($this->form->getFieldsets('params') as $fieldset): ?>
		<div class="tab-pane" id="<?php echo $fieldset->name; ?>">
			<div class="row-fluid">
				<?php foreach ($this->form->getFieldset($fieldset->name) as $field): ?>
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
		<?php endforeach; ?>
	</div>
	<?php echo $this->form->getInput('id'); ?>
	<input type="hidden" name="task" value="" />
	<?php echo JHtml::_('form.token'); ?>
</form>
