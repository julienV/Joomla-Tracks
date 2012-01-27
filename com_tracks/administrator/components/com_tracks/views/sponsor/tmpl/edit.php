<?php
// No direct access
defined('_JEXEC') or die('Restricted access');
JHtml::_('behavior.tooltip');
JHTML::_('behavior.formvalidation');
?>
<form action="<?php echo JRoute::_('index.php?option=com_tracks&view=sponsor&&layout=edit&id='.(int) $this->item->id); ?>"
      method="post" name="adminForm" id="sponsor-form" class="form-validate">
	<fieldset class="adminform">
		<legend><?php echo JText::_( 'COM_TRACKS_SPONSOR_DETAILS' ); ?></legend>
		<ul class="adminformlist">
			<ul class="adminformlist">
				<li><?php echo $this->form->getLabel('name'); ?>
				<?php echo $this->form->getInput('name'); ?></li>
				
				<li><?php echo $this->form->getLabel('alias'); ?>
				<?php echo $this->form->getInput('alias'); ?></li>

				<li><?php echo $this->form->getLabel('url'); ?>
				<?php echo $this->form->getInput('url'); ?></li>

				<li><?php echo $this->form->getLabel('cost'); ?>
				<?php echo $this->form->getInput('cost'); ?></li>

				<li><?php echo $this->form->getLabel('logo'); ?>
				<?php echo $this->form->getInput('logo'); ?></li>

				<li><?php echo $this->form->getLabel('state'); ?>
				<?php echo $this->form->getInput('state'); ?></li>

				<li><?php echo $this->form->getLabel('id'); ?>
				<?php echo $this->form->getInput('id'); ?></li>
			</ul>

			<div>
				<?php echo $this->form->getLabel('description'); ?>
				<div class="clr"></div>
				<?php echo $this->form->getInput('description'); ?>
			</div>
	</fieldset>
	<div>
		<input type="hidden" name="task" value="helloworld.edit" />
		<?php echo JHtml::_('form.token'); ?>
	</div>
</form>