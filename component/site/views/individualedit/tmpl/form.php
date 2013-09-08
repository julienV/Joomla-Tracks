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

<h2><?php echo JText::_('COM_TRACKS_VIEW_INDIVIDUAL_EDIT_PROFILE'); ?></h2>

<div class="edit individual-page">
<form id="adminForm" class="form-validate " name="adminForm" method="post" action="index.php">
	<input type="hidden" value="com_tracks" name="option">
	<input type="hidden" value="individualedit" name="view">
	<input type="hidden" value="" name="task">
	<input type="hidden" name="id" value="<?php echo $this->item->id ?>" />
	<input type="hidden" name="<?php echo JFactory::getSession()->getFormToken();?>" value="1" />

	<fieldset>
		<legend><?php echo Jtext::_('COM_TRACKS_INDIVIDUALS_GROUP_BASIC'); ?></legend>

		<div class="formelm-buttons">
			<button onclick="Joomla.submitbutton('save')" type="button"> Save </button>
			<button onclick="Joomla.submitbutton(' cancel')" type="button"> Cancel </button>
		</div>

		<div class="formelm">
			<?php echo $this->form->getLabel('last_name'); ?>
			<?php echo $this->form->getInput('last_name'); ?>
		</div>

		<div class="formelm">
			<?php echo $this->form->getLabel('first_name'); ?>
			<?php echo $this->form->getInput('first_name'); ?>
		</div>

		<div class="formelm">
			<?php echo $this->form->getLabel('nickname'); ?>
			<?php echo $this->form->getInput('nickname'); ?>
		</div>

		<div class="formelm">
			<?php echo $this->form->getLabel('alias'); ?>
			<?php echo $this->form->getInput('alias'); ?>
		</div>

		<div class="formelm">
			<?php echo $this->form->getLabel('country_code'); ?>
			<?php echo $this->form->getInput('country_code'); ?>
		</div>

		<div class="formelm">
			<?php echo $this->form->getLabel('height'); ?>
			<?php echo $this->form->getInput('height'); ?>
		</div>

		<div class="formelm">
			<?php echo $this->form->getLabel('weight'); ?>
			<?php echo $this->form->getInput('weight'); ?>
		</div>

		<div class="formelm">
			<?php echo $this->form->getLabel('dod'); ?>
			<?php echo $this->form->getInput('dod'); ?>
		</div>

		<div class="formelm">
			<?php echo $this->form->getLabel('hometown'); ?>
			<?php echo $this->form->getInput('hometown'); ?>
		</div>

		<div class="formelm">
			<?php echo $this->form->getLabel('picture'); ?>
			<div class="edit-pic"><?php echo $this->form->getInput('picture'); ?></div>
		</div>

		<div class="formelm">
			<?php echo $this->form->getLabel('picture_small'); ?>
			<div class="edit-pic"><?php echo $this->form->getInput('picture_small'); ?></div>
		</div>

		<?php echo $this->form->getInput('description'); ?>
	</fieldset>

	<fieldset>
		<legend><?php echo Jtext::_('COM_TRACKS_INDIVIDUALS_GROUP_ADDRESS'); ?></legend>

		<div class="formelm">
			<?php echo $this->form->getLabel('address'); ?>
			<?php echo $this->form->getInput('address'); ?>
		</div>
		<div class="formelm">
			<?php echo $this->form->getLabel('postcode'); ?>
			<?php echo $this->form->getInput('postcode'); ?>
		</div>
		<div class="formelm">
			<?php echo $this->form->getLabel('city'); ?>
			<?php echo $this->form->getInput('city'); ?>
		</div>
		<div class="formelm">
			<?php echo $this->form->getLabel('state'); ?>
			<?php echo $this->form->getInput('state'); ?>
		</div>
		<div class="formelm">
			<?php echo $this->form->getLabel('country'); ?>
			<?php echo $this->form->getInput('country'); ?>
		</div>

	</fieldset>

</form>
</div>
