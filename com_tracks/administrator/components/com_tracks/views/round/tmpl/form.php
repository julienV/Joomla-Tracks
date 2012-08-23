<?php
/**
* @version    $Id: form.php 95 2008-05-02 10:33:44Z julienv $ 
* @package    JoomlaTracks
* @copyright	Copyright (C) 2008 Julien Vonthron. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* Joomla Tracks is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/

defined('_JEXEC') or die('Restricted access'); ?>

<?php JHTML::_('behavior.tooltip'); ?>
<?php JHTML::_('behavior.formvalidation'); ?>

<?php
	// Set toolbar items for the page
	$edit		= JRequest::getVar('edit',true);
	$text = !$edit ? JText::_('COM_TRACKS_New' ) : JText::_('COM_TRACKS_Edit' );
	JToolBarHelper::title(   JText::_('COM_TRACKS_Round' ).': <small><small>[ ' . $text.' ]</small></small>' );
	JToolBarHelper::save();
	JToolBarHelper::apply();
	if (!$edit)  {
		JToolBarHelper::cancel();
	} else {
		// for existing items the button is renamed `close`
		JToolBarHelper::cancel( 'cancel', 'Close' );
	}
    JToolBarHelper::help( 'screen.tracks.edit', true );
?>

<script language="javascript" type="text/javascript">
	Joomla.submitbutton = function (pressbutton) {
		var form = document.adminForm;
		if (pressbutton == 'cancel') {
			Joomla.submitform( pressbutton );
			return;
		}

    // do field validation
    var validator = document.formvalidator;
    if ( validator.validate(form.name) === false ){
      alert( "<?php echo JText::_('COM_TRACKS_ROUND_NAME_IS_REQUIRED', true ); ?>" );
    } else if ( validator.validate(form.short_name) === false ){
			alert( "<?php echo JText::_('COM_TRACKS_ROUND_SHORT_NAME_IS_REQUIRED', true ); ?>" );
		} else {
      Joomla.submitform( pressbutton );
    }
	}
</script>

<div id="tracksmain">
<form action="index.php" method="post" name="adminForm" id="adminForm">
<div class="width-70">
<fieldset class="adminform"><legend><?php echo JText::_('COM_TRACKS_Round' ); ?></legend>

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
			<?php echo $this->form->getLabel('country'); ?> 
			<?php echo $this->form->getInput('country'); ?>
		</li>
		<li>
			<?php echo $this->form->getLabel('picture'); ?> 
			<?php echo $this->form->getInput('picture'); ?>
		</li>
		<li>
			<?php echo $this->form->getLabel('thumbnail'); ?> 
			<?php echo $this->form->getInput('thumbnail'); ?>
		</li>
		<li>
			<?php echo $this->form->getLabel('published'); ?> 
			<?php echo $this->form->getInput('published'); ?>
		</li>
	</ul>
	<div class="clr"></div>
	<?php echo $this->form->getLabel('description'); ?>
	<div class="clr"></div>
	<?php echo $this->form->getInput('description'); ?>
</fieldset>
</div>

<div class="clr"></div>

<input type="hidden" name="option" value="com_tracks" />
<input type="hidden" name="controller" value="round" />
<input type="hidden" name="cid[]" value="<?php echo $this->object->id; ?>" />
<input type="hidden" name="task" value="" />
</form>
</div>