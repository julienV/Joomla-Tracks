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
<?php JHTML::_('behavior.formvalidation'); ?>

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
    var validator = document.formvalidator;
    if ( validator.validate(form.round_id) === false ){
      alert( "<?php echo JText::_('COM_TRACKS_ROUND_ID_IS_REQUIRED', true ); ?>" );
    } else {
      Joomla.submitform( pressbutton );
    }
	}
</script>

<div id="tracksmain">
<form action="index.php" method="post" name="adminForm" id="adminForm">
<div class="width-70">
	<fieldset class="adminform">
		<legend><?php echo JText::_('COM_TRACKS_Projectround' ); ?></legend>
		
		<ul class="adminformlist">
			<li>
				<label for="round_id">
					<?php echo JText::_('COM_TRACKS_ROUND' ); ?>:
				</label>
				<?php echo $this->lists['rounds']; ?>
			</li>
			<li>
				<label for="published"><?php echo JText::_('COM_TRACKS_Published' ); ?>:</label>
        <?php echo $this->lists['published']; ?>
			</li>
			<li>
				<label for="last_name">
					<?php echo JText::_('COM_TRACKS_Round_start' ); ?>:
				</label>
				<?php 
		    echo  JHTML::calendar( $this->projectround->start_date, 'start_date', 'start_date', '%Y-%m-%d %H:%M:%S' );
		    ?>
			</li>
			<li>
				<label for="last_name">
					<?php echo JText::_('COM_TRACKS_Round_end' ); ?>:
				</label>
			<?php 
	        echo  JHTML::calendar( $this->projectround->end_date, 'end_date', 'end_date', '%Y-%m-%d %H:%M:%S' );
	        ?>
			</li>
			<li>
				<label for="ordering">
	          <?php echo JText::_('COM_TRACKS_Ordering' ); ?>:
	        </label>
	        <?php echo $this->lists['ordering']; ?>
			</li>
			</ul>
	</fieldset>
</div>

<div class="clr"></div>

<input type="hidden" name="option" value="com_tracks" />
<input type="hidden" name="controller" value="projectround" />
<input type="hidden" name="cid[]" value="<?php echo $this->projectround->id; ?>" />
<input type="hidden" name="project_id" value="<?php echo $this->projectround->project_id; ?>" />
<input type="hidden" name="task" value="" />
</form>
</div>