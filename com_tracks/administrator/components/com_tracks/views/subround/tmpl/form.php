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
	Joomla.submitbutton = function (pressbutton) {
		var form = document.adminForm;
		if (pressbutton == 'cancel') {
			Joomla.submitform( pressbutton );
			return;
		}

		// do field validation
		if (form.type.value == ""){
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
<div class="width-70">
	<fieldset class="adminform">
		<legend><?php echo JText::_('COM_TRACKS_Subround' ); ?></legend>

		<ul class="adminformlist">
			<li>
				<label for="competition">
					<?php echo JText::_('COM_TRACKS_Type' ); ?>:
				</label>
				<?php echo $this->lists['types']; ?>
			</li>
			<li>
				<label for="published"><?php echo JText::_('COM_TRACKS_Published' ); ?>:</label>
				<?php echo $this->lists['published']; ?>
			</li>
			<li>
				<label for="start_date">
					<?php echo JText::_('COM_TRACKS_Round_start' ); ?>:
				</label>
      <?php 
		    echo  JHTML::calendar( $this->object->start_date, 'start_date', 'start_date', '%Y-%m-%d %H:%M:%S' );
		  ?>
			</li>
			<li>
				<label for="end_date">
					<?php echo JText::_('COM_TRACKS_Round_end' ); ?>:
				</label>
			<?php 
	        echo  JHTML::calendar( $this->object->end_date, 'end_date', 'end_date', '%Y-%m-%d %H:%M:%S' );
	    ?>
			</li>
			<li>
				<label for="ordering">
	     <?php echo JText::_('COM_TRACKS_Ordering' ); ?>:
	     </label>
	    <?php echo $this->lists['ordering']; ?>
			</li>
			<li>
				<label for="description">
          <?php echo JText::_('COM_TRACKS_Description' ); ?>:
        </label>
        <?php echo $this->editor->display('description', $this->object->description, '100%', '400', '70', '15'); ?>
			</li>
			<li>
				<label for="comment">
          <?php echo JText::_('COM_TRACKS_Comment' ); ?>:
        </label>
        <?php echo $this->editor->display('comment', $this->object->comment, '100%', '400', '70', '15'); ?>
			</li>
		</ul>				
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