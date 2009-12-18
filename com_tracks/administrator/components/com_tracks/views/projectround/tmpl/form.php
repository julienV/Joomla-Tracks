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
	$text = !$edit ? JText::_( 'New' ) : JText::_( 'Edit' );
	JToolBarHelper::title(   JText::_( 'Project' ).': <small><small>[ ' . $text.' ]</small></small>' );
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
	function submitbutton(pressbutton) {
		var form = document.adminForm;
		if (pressbutton == 'cancel') {
			submitform( pressbutton );
			return;
		}

    // do field validation
    var validator = document.formvalidator;
    if ( validator.validate(form.round_id) === false ){
      alert( "<?php echo JText::_( 'ROUND ID IS REQUIRED', true ); ?>" );
    } else {
      submitform( pressbutton );
    }
	}
</script>

<div id="tracksmain">
<form action="index.php" method="post" name="adminForm" id="adminForm">
<div class="col50">
	<fieldset class="adminform">
		<legend><?php echo JText::_( 'Projectround' ); ?></legend>

		<table class="admintable">
		<tr>
			<td valign="top" align="right" class="key">
				<label for="round_id">
					<?php echo JText::_( 'ROUND' ); ?>:
				</label>
			</td>
			<td>
				<?php echo $this->lists['rounds']; ?>
			</td>
		</tr>
        <tr>
            <td valign="top" align="right" class="key"><?php echo JText::_( 'Published' ); ?>:
            </td>
            <td><?php echo $this->lists['published']; ?></td>
        </tr>
		<tr>
			<td width="100" align="right" class="key">
				<label for="last_name">
					<?php echo JText::_( 'Round start' ); ?>:
				</label>
			</td>
			<td>
            <?php 
		    echo  JHTML::calendar( $this->projectround->start_date, 'start_date', 'start_date', '%Y-%m-%d %H:%M:%S' );
		    ?>
		    </td>
		</tr>
		<tr>
			<td width="100" align="right" class="key">
				<label for="last_name">
					<?php echo JText::_( 'Round end' ); ?>:
				</label>
			</td>
			<td>
			<?php 
	        echo  JHTML::calendar( $this->projectround->end_date, 'end_date', 'end_date', '%Y-%m-%d %H:%M:%S' );
	        ?>
	        </td>
		</tr>
		<tr>
	        <td valign="top" align="right" class="key">
	        <label for="ordering">
	          <?php echo JText::_( 'Ordering' ); ?>:
	        </label>
	        </td>
	        <td>
	        <?php echo $this->lists['ordering']; ?>
	        </td>
	    </tr>
	</table>
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