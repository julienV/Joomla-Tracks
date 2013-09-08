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
	<input type="hidden" name="view" value="teams" />
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="id" value="<?php echo $this->item->id ?>" />
	<input type="hidden" name="<?php echo JFactory::getSession()->getFormToken();?>" value="1" />

	<div class="width-60 fltlft">
		<fieldset class="adminform">
			<legend><?php echo JText::_('COM_TRACKS_TEAM_BASIC_LABEL'); ?></legend>
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
					<?php echo $this->form->getLabel('short_name'); ?>
					<?php echo $this->form->getInput('short_name'); ?>
				</li>
				<li>
					<?php echo $this->form->getLabel('published'); ?>
					<?php echo $this->form->getInput('published'); ?>
				</li>
				<li>
					<?php echo $this->form->getLabel('picture'); ?>
					<?php echo $this->form->getInput('picture'); ?>
				</li>
				<li>
					<?php echo $this->form->getLabel('picture_small'); ?>
					<?php echo $this->form->getInput('picture_small'); ?>
				</li>
				<li>
					<?php echo $this->form->getLabel('country_code'); ?>
					<?php echo $this->form->getInput('country_code'); ?>
				</li>
				<li>
					<?php echo $this->form->getLabel('admin_id'); ?>
					<?php echo $this->form->getInput('admin_id'); ?>
				</li>
			</ul>
			<div class="clr"></div>
				<?php echo $this->form->getLabel('description'); ?>
				<?php echo $this->form->getInput('description'); ?>
			<div class="clr"></div>
		</fieldset>
	</div>

	<div class="width-40 fltlft">
		<fieldset class="adminform">
			<legend><?php echo JText::_('COM_TRACKS_TEAM_SOCIAL_LINKS'); ?></legend>
			<ul class="adminformlist">
				<li>
					<?php echo $this->form->getLabel('url'); ?>
					<?php echo $this->form->getInput('url'); ?>
				</li>
				<li>
					<?php echo $this->form->getLabel('facebook'); ?>
					<?php echo $this->form->getInput('facebook'); ?>
				</li>
				<li>
					<?php echo $this->form->getLabel('twitter'); ?>
					<?php echo $this->form->getInput('twitter'); ?>
				</li>
				<li>
					<?php echo $this->form->getLabel('googleplus'); ?>
					<?php echo $this->form->getInput('googleplus'); ?>
				</li>
				<li>
					<?php echo $this->form->getLabel('youtube'); ?>
					<?php echo $this->form->getInput('youtube'); ?>
				</li>
				<li>
					<?php echo $this->form->getLabel('instagram'); ?>
					<?php echo $this->form->getInput('instagram'); ?>
				</li>
				<li>
					<?php echo $this->form->getLabel('pinterest'); ?>
					<?php echo $this->form->getInput('pinterest'); ?>
				</li>
				<li>
					<?php echo $this->form->getLabel('vimeo'); ?>
					<?php echo $this->form->getInput('vimeo'); ?>
				</li>
		</fieldset>
	</div>
</form>
