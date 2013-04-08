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
	JToolBarHelper::title(   JText::_('COM_TRACKS_Subround_type' ).': <small><small>[ ' . $text.' ]</small></small>' );
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

<script type="text/javascript">
	Joomla.submitbutton = function (pressbutton) {
		var form = document.adminForm;
		if (pressbutton == 'cancel') {
			Joomla.submitform( pressbutton );
			return;
		}

    // do field validation
    var validator = document.formvalidator;
    if ( validator.validate(form.name) === false ){
      alert( "<?php echo JText::_('COM_TRACKS_SUBROUND_TYPE_NAME_IS_REQUIRED', true ); ?>" );
    } else {
      Joomla.submitform( pressbutton );
    }
	}
</script>

<div id="tracksmain">
	<form action="index.php" method="post" name="adminForm" id="adminForm">
		<div class="width-70">
			<fieldset class="adminform">
				<legend><?php echo JText::_('COM_TRACKS_Subround_type' ); ?></legend>

				<ul class="adminformlist">
					<li><label for="name"> <?php echo JText::_('COM_TRACKS_Name' ); ?>:
						</label>
						<input class="text_area required" type="text" name="name" id="name"
						size="32" maxlength="40" value="<?php echo $this->object->name?>" />
					</li>
					<li>
						<label for="alias"> <?php echo JText::_('COM_TRACKS_Alias' ); ?>:
						</label>
						<input class="text_area" type="text" name="alias" id="alias"
							size="32" maxlength="250" value="<?php echo $this->object->alias?>" />
					</li>
					<li><label for="alias"> <?php echo JText::_('COM_TRACKS_Note' ); ?>:
						</label>
						<input class="text_area" type="text" name="note" id="note"
							size="32" maxlength="100" value="<?php echo $this->object->note?>" />
					</li>
					<li>
						<label for="points_attribution"> <?php echo JText::_('COM_TRACKS_Points' ); ?>:
						</label>
						<input class="text_area" type="text" name="points_attribution" id="points_attribution"
							size="50" maxlength="255" value="<?php echo $this->object->points_attribution?>" />
					</li>
				</ul>

				<div class="clr"></div>
				<label for="description"> <?php echo JText::_('COM_TRACKS_Description' ); ?>:</label>
				<div class="clr"></div>
				<?php echo $this->editor->display('description', $this->object->description, '100%', '400', '70', '15'); ?>

			</fieldset>
		</div>

	<div class="clr"></div>

	<input type="hidden" name="option" value="com_tracks" />
	<input type="hidden" name="controller" value="subroundtype" />
	<input type="hidden" name="cid[]" value="<?php echo $this->object->id; ?>" />
	<input type="hidden" name="task" value="" />
	</form>
</div>