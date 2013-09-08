<?php
/**
* @version    $Id: form.php 128 2008-06-06 08:08:04Z julienv $
* @package    JoomlaTracks
* @copyright	Copyright (C) 2008 Julien Vonthron. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* Joomla Tracks is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/

defined('_JEXEC') or die('Restricted access');

JHtml::_('behavior.formvalidation');
?>

<script type="text/javascript">
	Joomla.submitbutton = function(task) {
		if (task == 'cancel' || document.formvalidator.isValid(document.id('adminForm'))) {
			<?php echo $this->form->getField('description')->save(); ?>
			Joomla.submitform(task);
		} else {
			alert('<?php echo $this->escape(JText::_('JGLOBAL_VALIDATION_FORM_FAILED'));?>');
		}
	}
</script>

<h2><?php echo JText::_('COM_TRACKS_VIEW_TEAM_EDIT'); ?></h2>

<div class="edit team-page">
<form id="adminForm" class="form-validate " name="adminForm" method="post" action="index.php">
	<input type="hidden" value="com_tracks" name="option">
	<input type="hidden" value="teamedit" name="view">
	<input type="hidden" value="" name="task">
	<input type="hidden" name="id" value="<?php echo $this->item->id ?>" />
	<input type="hidden" name="<?php echo JFactory::getSession()->getFormToken();?>" value="1" />

	<fieldset>
		<legend><?php echo Jtext::_('COM_TRACKS_EDIT_TEAM_FIELDSET_LEGEND'); ?></legend>

		<div class="formelm">
			<?php echo $this->form->getLabel('name'); ?>
			<?php echo $this->form->getInput('name'); ?>
		</div>

		<div class="formelm-buttons">
			<button onclick="Joomla.submitbutton('save')" type="button"> Save </button>
			<button onclick="Joomla.submitbutton(' cancel')" type="button"> Cancel </button>
		</div>

		<div class="formelm">
			<?php echo $this->form->getLabel('alias'); ?>
			<?php echo $this->form->getInput('alias'); ?>
		</div>

		<div class="formelm">
			<?php echo $this->form->getLabel('short_name'); ?>
			<?php echo $this->form->getInput('short_name'); ?>
		</div>

		<div class="formelm">
			<?php echo $this->form->getLabel('picture'); ?>
			<div class="edit-pic"><?php echo $this->form->getInput('picture'); ?></div>
		</div>

		<div class="formelm">
			<?php echo $this->form->getLabel('picture_small'); ?>
			<div class="edit-pic"><?php echo $this->form->getInput('picture_small'); ?></div>
		</div>

		<div class="formelm">
			<?php echo $this->form->getLabel('country_code'); ?>
			<?php echo $this->form->getInput('country_code'); ?>
		</div>

		<?php echo $this->form->getInput('description'); ?>
	</fieldset>

	<fieldset>
		<legend><?php echo Jtext::_('COM_TRACKS_ITEMS_GROUP_VEHICLE'); ?></legend>

		<div class="formelm">
			<?php echo $this->form->getLabel('vehicle_picture'); ?>
			<div class="edit-pic"><?php echo $this->form->getInput('vehicle_picture'); ?></div>
		</div>

		<?php echo $this->form->getInput('vehicle_description'); ?>

	</fieldset>

	<fieldset>
		<legend><?php echo Jtext::_('COM_TRACKS_TEAM_SOCIAL_LINKS'); ?></legend>

		<div class="formelm">
			<?php echo $this->form->getLabel('url'); ?>
			<?php echo $this->form->getInput('url'); ?>
		</div>

		<div class="formelm">
			<?php echo $this->form->getLabel('facebook'); ?>
			<?php echo $this->form->getInput('facebook'); ?>
		</div>

		<div class="formelm">
			<?php echo $this->form->getLabel('twitter'); ?>
			<?php echo $this->form->getInput('twitter'); ?>
		</div>

		<div class="formelm">
			<?php echo $this->form->getLabel('googleplus'); ?>
			<?php echo $this->form->getInput('googleplus'); ?>
		</div>

		<div class="formelm">
			<?php echo $this->form->getLabel('youtube'); ?>
			<?php echo $this->form->getInput('youtube'); ?>
		</div>

		<div class="formelm">
			<?php echo $this->form->getLabel('instagram'); ?>
			<?php echo $this->form->getInput('instagram'); ?>
		</div>

		<div class="formelm">
			<?php echo $this->form->getLabel('pinterest'); ?>
			<?php echo $this->form->getInput('pinterest'); ?>
		</div>

		<div class="formelm">
			<?php echo $this->form->getLabel('vimeo'); ?>
			<?php echo $this->form->getInput('vimeo'); ?>
		</div>

	</fieldset>
</form>
</div>
