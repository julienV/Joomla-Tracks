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
	function submitbutton(pressbutton) {
		var form = document.adminForm;
		if (pressbutton == 'cancel') {
			submitform( pressbutton );
			return;
		}

		// do field validation
		if (form.type.value == ""){
			alert( "<?php echo JText::_('COM_TRACKS_You_must_chose_a_type', true ); ?>" );
		} else {
			submitform( pressbutton );
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

		<table class="admintable">
		<tr>
			<td valign="top" align="right" class="key">
				<label for="competition">
					<?php echo JText::_('COM_TRACKS_Type' ); ?>:
				</label>
			</td>
			<td>
				<?php echo $this->lists['types']; ?>
			</td>
		</tr>
		<tr>
			<td valign="top" align="right" class="key"><?php echo JText::_('COM_TRACKS_Published' ); ?>:
			</td>
			<td><?php echo $this->lists['published']; ?></td>
		</tr>
		<tr>
			<td width="100" align="right" class="key">
				<label for="last_name">
					<?php echo JText::_('COM_TRACKS_Round_start' ); ?>:
				</label>
			</td>
			<td>
      <?php 
		    echo  JHTML::calendar( $this->object->start_date, 'start_date', 'start_date', '%Y-%m-%d %H:%M:%S' );
		  ?>
		  </td>
		</tr>
		<tr>
			<td width="100" align="right" class="key">
				<label for="last_name">
					<?php echo JText::_('COM_TRACKS_Round_end' ); ?>:
				</label>
			</td>
			<td>
			<?php 
	        echo  JHTML::calendar( $this->object->end_date, 'end_date', 'end_date', '%Y-%m-%d %H:%M:%S' );
	    ?>
	    </td>
		</tr>
		<tr>
	    <td valign="top" align="right" class="key">
	     <label for="ordering">
	     <?php echo JText::_('COM_TRACKS_Ordering' ); ?>:
	     </label>
	    </td>
	    <td>
	    <?php echo $this->lists['ordering']; ?>
	    </td>
	  </tr>
    <tr>
      <td width="100" align="right" class="key">
        <label for="description">
          <?php echo JText::_('COM_TRACKS_Description' ); ?>:
        </label>
      </td>
      <td>
        <?php echo $this->editor->display('description', $this->object->description, '100%', '400', '70', '15'); ?>
      </td>
    </tr>
    <tr>
      <td width="100" align="right" class="key">
        <label for="comment">
          <?php echo JText::_('COM_TRACKS_Comment' ); ?>:
        </label>
      </td>
      <td>
        <?php echo $this->editor->display('comment', $this->object->comment, '100%', '400', '70', '15'); ?>
      </td>
    </tr>
	</table>
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