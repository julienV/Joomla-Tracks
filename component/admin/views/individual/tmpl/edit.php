<?php
/**
 * @package     Tracks
 * @subpackage  Admin
 * @copyright   Tracks (C) 2008-2015 Julien Vonthron. All rights reserved.
 * @license     GNU General Public License version 2 or later
 */

use Joomla\CMS\Plugin\PluginHelper;
use Tracks\Helper\Config;

defined('_JEXEC') or die;

JHtml::_('rbootstrap.tooltip');
JHtml::_('rjquery.chosen', 'select');
JHtml::_('behavior.formvalidation');

/**
 * @var RForm $form
 */
$form            = $this->form;
$customFieldsets = PluginHelper::isEnabled('tracks', 'customfields')
	? $form->getFieldsets('com_fields') : [];
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
	action="index.php?option=com_tracks&task=individual.edit&id=<?php echo $this->item->id; ?>"
	method="post" name="adminForm" class="form-validate form-horizontal" id="adminForm">

	<ul class="nav nav-tabs" id="individualTab">
		<li class="active">
			<a href="#basic" data-toggle="tab">
				<strong><?php echo JText::_('COM_TRACKS_INDIVIDUAL'); ?></strong>
			</a>
		</li>
		<?php if (Config::getConfig()->get('enable_individual_address', 1)): ?>
		<li>
			<a href="#address" data-toggle="tab">
				<strong><?php echo JText::_('COM_TRACKS_INDIVIDUALS_GROUP_ADDRESS'); ?></strong>
			</a>
		</li>
		<?php endif; ?>
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
				<?php if (Config::getConfig()->get('enable_individual_nickname', 1)): ?>
				<div class="control-group">
					<div class="control-label">
						<?php echo $this->form->getLabel('nickname'); ?>
					</div>
					<div class="controls">
						<?php echo $this->form->getInput('nickname'); ?>
					</div>
				</div>
				<?php endif; ?>
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
						<?php echo $this->form->getLabel('team_id'); ?>
					</div>
					<div class="controls">
						<?php echo $this->form->getInput('team_id'); ?>
					</div>
				</div>
				<?php if (Config::getConfig()->get('enable_individual_gender', 1)): ?>
				<div class="control-group">
					<div class="control-label">
						<?php echo $this->form->getLabel('gender', 'params'); ?>
					</div>
					<div class="controls">
						<?php echo $this->form->getInput('gender', 'params'); ?>
					</div>
				</div>
				<?php endif; ?>
				<div class="control-group">
					<div class="control-label">
						<?php echo $this->form->getLabel('country_code'); ?>
					</div>
					<div class="controls">
						<?php echo $this->form->getInput('country_code'); ?>
					</div>
				</div>
				<?php if (Config::getConfig()->get('enable_individual_height', 1)): ?>
				<div class="control-group">
					<div class="control-label">
						<?php echo $this->form->getLabel('height'); ?>
					</div>
					<div class="controls">
						<?php echo $this->form->getInput('height'); ?>
					</div>
				</div>
				<?php endif; ?>
				<?php if (Config::getConfig()->get('enable_individual_weight', 1)): ?>
				<div class="control-group">
					<div class="control-label">
						<?php echo $this->form->getLabel('weight'); ?>
					</div>
					<div class="controls">
						<?php echo $this->form->getInput('weight'); ?>
					</div>
				</div>
				<?php endif; ?>
				<?php if (Config::getConfig()->get('enable_individual_dob', 1)): ?>
				<div class="control-group">
					<div class="control-label">
						<?php echo $this->form->getLabel('dob'); ?>
					</div>
					<div class="controls">
						<?php echo $this->form->getInput('dob'); ?>
					</div>
				</div>
				<?php endif; ?>
				<?php if (Config::getConfig()->get('enable_individual_hometown', 1)): ?>
				<div class="control-group">
					<div class="control-label">
						<?php echo $this->form->getLabel('hometown'); ?>
					</div>
					<div class="controls">
						<?php echo $this->form->getInput('hometown'); ?>
					</div>
				</div>
				<?php endif; ?>
				<div class="control-group">
					<div class="control-label">
						<?php echo $this->form->getLabel('user_id'); ?>
					</div>
					<div class="controls">
						<?php echo $this->form->getInput('user_id'); ?>
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
						<?php echo $this->form->getLabel('description'); ?>
					</div>
					<div class="controls">
						<?php echo $this->form->getInput('description'); ?>
					</div>
				</div>
			</div>
		</div>

		<?php if (Config::getConfig()->get('enable_individual_address', 1)): ?>
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
		<?php endif; ?>

		<?php if (!empty($customFieldsets)): ?>
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
		<?php endif; ?>
	</div>
	<?php echo $this->form->getInput('id'); ?>
	<input type="hidden" name="task" value="" />
	<?php echo JHtml::_('form.token'); ?>
</form>
