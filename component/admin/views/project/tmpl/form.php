<?php
/**
 * @version    2.0
 * @package    JoomlaTracks
 * @copyright  Copyright (C) 2008 Julien Vonthron. All rights reserved.
 * @license    GNU/GPL, see LICENSE.php
 * Joomla Tracks is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * See COPYRIGHT.php for copyright notices and details.
 */

defined('_JEXEC') or die();

$editor = JFactory::getEditor();

$this->loadHelper('select');
$this->loadHelper('filtering');

FOFTemplateUtils::addCSS('media://com_tracks/css/tracksbackend.css');
?>

<?php JHTML::_('behavior.tooltip'); ?>
<?php JHTML::_('behavior.formvalidation'); ?>

<form id="adminForm" class="form-validate " name="adminForm" method="post" action="index.php">
	<input type="hidden" name="option" value="com_tracks" />
	<input type="hidden" name="view" value="rounds" />
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="id" value="<?php echo $this->item->id ?>" />
	<input type="hidden" name="<?php echo JFactory::getSession()->getFormToken();?>" value="1" />

	<div class="width-100 fltlft">
		<fieldset class="adminform"><legend><?php echo JText::_('COM_TRACKS_General' ); ?></legend>

			<ul class="adminformlist">
				<li>
					<?php echo $this->form->getLabel('name'); ?>
					<?php echo $this->form->getInput('name'); ?>
				</li>
				<li>
					<?php echo $this->form->getLabel('alias'); ?>
					<?php echo $this->form->getInput('alias'); ?>
				</li>
				<li>
					<?php echo $this->form->getLabel('published'); ?>
					<?php echo $this->form->getInput('published'); ?>
				</li>
				<li>
					<?php echo $this->form->getLabel('competition_id'); ?>
					<?php echo $this->form->getInput('competition_id'); ?>
				</li>
				<li>
					<?php echo $this->form->getLabel('season_id'); ?>
					<?php echo $this->form->getInput('season_id'); ?>
				</li>
				<li>
					<?php echo $this->form->getLabel('admin_id'); ?>
					<?php echo $this->form->getInput('admin_id'); ?>
				</li>
			</ul>
			<div class="clr"></div>
			<?php echo $this->form->getLabel('description'); ?>
			<div class="clr"></div>
			<?php echo $this->form->getInput('description'); ?>
		</fieldset>
	</div>

	<div class="width-40 fltrt">
		<?php
		// Iterate through the normal form fieldsets and display each one.
//		foreach ($this->params->getFieldsets() as $fieldsets => $fieldset):
//			?>
<!--			<fieldset class="adminform">-->
<!--				<legend>-->
<!--					--><?php //echo JText::_($fieldset->name); ?>
<!--				</legend>-->
<!--				<dl>-->
<!--					--><?php
//					// Iterate through the fields and display them.
//					foreach($this->params->getFieldset($fieldset->name) as $field):
//						// If the field is hidden, only use the input.
//						if ($field->hidden):
//							echo $field->input;
//						else:
//							?>
<!--							<dt>-->
<!--								--><?php //echo $field->label; ?>
<!--							</dt>-->
<!--							<dd--><?php //echo ($field->type == 'Editor' || $field->type == 'Textarea') ? ' style="clear: both; margin: 0;"' : ''?><!-->-->
<!--								--><?php //echo $field->input ?>
<!--							</dd>-->
<!--						--><?php
//						endif;
//					endforeach;
//					?>
<!--				</dl>-->
<!--			</fieldset>-->
<!--		--><?php
//		endforeach;
		?>
</form>
