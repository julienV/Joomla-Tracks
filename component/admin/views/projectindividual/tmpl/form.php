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
	<input type="hidden" name="view" value="projectindividuals" />
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="id" value="<?php echo $this->item->id ?>" />
	<input type="hidden" name="<?php echo JFactory::getSession()->getFormToken();?>" value="1" />

	<div class="width-100 fltlft">
		<fieldset class="adminform"><legend><?php echo JText::_('COM_TRACKS_Project_Participant' ); ?></legend>

			<ul class="adminformlist">
				<li>
					<?php echo $this->form->getLabel('individual_id'); ?>
					<?php echo $this->form->getInput('individual_id'); ?>
				</li>
				<li>
					<?php echo $this->form->getLabel('number'); ?>
					<?php echo $this->form->getInput('number'); ?>
				</li>
				<li>
					<?php echo $this->form->getLabel('team_id'); ?>
					<?php echo $this->form->getInput('team_id'); ?>
				</li>
			</ul>
		</fieldset>
	</div>
</form>
