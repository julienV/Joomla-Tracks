<?php
/**
 * @package AkeebaReleaseSystem
 * @copyright Copyright (c)2010-2013 Nicholas K. Dionysopoulos
 * @license GNU General Public License version 3, or later
 * @version $Id$
 */

defined('_JEXEC') or die();

$editor = JFactory::getEditor();

$this->loadHelper('select');
$this->loadHelper('filtering');

FOFTemplateUtils::addCSS('media://com_tracks/css/tracksbackend.css');

?>

<form id="adminForm" class="form-validate " name="adminForm" method="post" action="index.php">
	<input type="hidden" name="option" value="com_tracks" />
	<input type="hidden" name="view" value="projectrounds" />
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="id" value="<?php echo $this->item->id ?>" />
	<input type="hidden" name="<?php echo JFactory::getSession()->getFormToken();?>" value="1" />

	<div class="width-100 fltlft">
		<fieldset class="adminform"><legend><?php echo JText::_('COM_TRACKS_Projectround' ); ?></legend>

			<ul class="adminformlist">
				<li>
					<?php echo $this->form->getLabel('round_id'); ?>
					<?php echo $this->form->getInput('round_id'); ?>
				</li>
				<li>
					<?php echo $this->form->getLabel('published'); ?>
					<?php echo $this->form->getInput('published'); ?>
				</li>
				<li>
					<?php echo $this->form->getLabel('start_date'); ?>
					<?php echo $this->form->getInput('start_date'); ?>
				</li>
				<li>
					<?php echo $this->form->getLabel('end_date'); ?>
					<?php echo $this->form->getInput('end_date'); ?>
				</li>
			</ul>
<!--			<div class="clr"></div>-->
<!--			--><?php //echo $this->form->getLabel('description'); ?>
<!--			<div class="clr"></div>-->
<!--			--><?php //echo $this->form->getInput('description'); ?>
<!--			<div class="clr"></div>-->
<!--			--><?php //echo $this->form->getLabel('comment'); ?>
<!--			<div class="clr"></div>-->
<!--			--><?php //echo $this->form->getInput('comment'); ?>
		</fieldset>
	</div>
</form>
