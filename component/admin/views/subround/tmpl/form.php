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

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();

$editor = JFactory::getEditor();

$this->loadHelper('select');
$this->loadHelper('filtering');

FOFTemplateUtils::addCSS('media://com_tracks/css/tracksbackend.css');
$model = $this->getModel();
?>

<form id="adminForm" class="form-validate " name="adminForm" method="post" action="index.php">
	<input type="hidden" name="option" value="com_tracks" />
	<input type="hidden" name="view" value="subrounds" />
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="id" value="<?php echo $this->item->id ?>" />
	<input type="hidden" name="projectround_id" value="<?php echo $model->getState('projectround_id'); ?>" />
	<input type="hidden" name="<?php echo JFactory::getSession()->getFormToken();?>" value="1" />

	<div class="width-100 fltlft">
		<fieldset class="adminform"><legend><?php echo JText::_('COM_TRACKS_Projectround' ); ?></legend>

			<ul class="adminformlist">
				<li>
					<?php echo $this->form->getLabel('type'); ?>
					<?php echo $this->form->getInput('type'); ?>
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
			<div class="clr"></div>
			<?php echo $this->form->getLabel('description'); ?>
			<div class="clr"></div>
			<?php echo $this->form->getInput('description'); ?>
			<div class="clr"></div>
			<?php echo $this->form->getLabel('comment'); ?>
			<div class="clr"></div>
			<?php echo $this->form->getInput('comment'); ?>
		</fieldset>
	</div>
</form>
