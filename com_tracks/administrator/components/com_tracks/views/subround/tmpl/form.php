<?php
/**
* @version    $Id: form.php 96 2008-05-02 10:35:43Z julienv $ 
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

<?php
	// Set toolbar items for the page
	$edit		= JRequest::getVar('edit',true);
	$text = !$edit ? JText::_('COM_TRACKS_New' ) : JText::_('COM_TRACKS_Edit' );
	JToolBarHelper::title(   JText::_('COM_TRACKS_Project' ).': <small><small>[ ' . $text.' ]</small></small>' );
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

	window.addEvent('domready', function(){
      document.formvalidator.setHandler('notzero',
        function (value) {
          if(value=="0") {
            return false;
          } else {
            return true;
          }
        }
      );
	});

	Joomla.submitbutton = function (pressbutton) {
		var form = document.adminForm;
		if (pressbutton == 'cancel') {
			Joomla.submitform( pressbutton );
			return;
		}

		var validator = document.formvalidator;
		// do field validation
		if (!validator.validate(form.type)){
			alert( "<?php echo JText::_('COM_TRACKS_You_must_chose_a_type', true ); ?>" );
		} else {
			Joomla.submitform( pressbutton );
		}
	}
</script>
<style type="text/css">
	table.paramlist td.paramlist_key {
		width: 92px;
		text-align: left;
		height: 30px;
	}
</style>

<div id="tracksmain">
<form action="index.php" method="post" name="adminForm" id="adminForm">
<div class="col50">
	<fieldset class="adminform">
		<legend><?php echo JText::_('COM_TRACKS_Subround' ); ?></legend>

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

<div class="clr"></div>

<input type="hidden" name="option" value="com_tracks" />
<input type="hidden" name="controller" value="subround" />
<input type="hidden" name="cid[]" value="<?php echo $this->object->id; ?>" />
<input type="hidden" name="projectround_id" value="<?php echo $this->object->projectround_id; ?>" />
<input type="hidden" name="task" value="" />
</form>
</div>