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
		if (form.subround_id.value == ""){
			alert( "<?php echo JText::_('COM_TRACKS_Result_item_must_have_subround_id', true ); ?>" );
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
		<legend>
		  <?php 
		  echo $this->result->round_name . ' - ' 
		     . $this->result->typename . ' - '
		     . JText::_('COM_TRACKS_Result' ); ?></legend>

		<table class="admintable">
		<tr>
			<td valign="top" align="right" class="key">
				<label for="individual">
					<?php echo JText::_('COM_TRACKS_Individual' ); ?>:
				</label>
			</td>
			<td>
			  <?php if (!$edit): ?>
        <?php echo $this->lists['participants'] ; ?>
			  <?php else: ?>
				<?php echo $this->result->first_name . ' ' . $this->result->last_name ; ?>
				<?php endif; ?>
			</td>
    </tr>
    <tr>
      <td width="100" align="right" class="key">
        <label for="rank">
          <?php echo JText::_('COM_TRACKS_Rank' ); ?>:
        </label>
      </td>
      <td>
        <input class="text_area" type="text" name="rank" id="rank" size="3" maxlength="3" value="<?php echo $this->result->rank; ?>" />
      </td>
		</tr>
    <tr>
      <td width="100" align="right" class="key">
        <label for="bonus_points">
          <?php echo JText::_('COM_TRACKS_Bonus_points' ); ?>:
        </label>
      </td>
      <td>
        <input class="text_area" type="text" name="bonus_points" id="bonus_points" size="3" maxlength="3" value="<?php echo $this->result->bonus_points; ?>" />
      </td>
    </tr>
    <tr>
      <td width="100" align="right" class="key">
        <label for="performance">
          <?php echo JText::_('COM_TRACKS_Performance' ); ?>:
        </label>
      </td>
      <td>
        <input class="text_area" type="text" name="performance" id="performance" size="10" maxlength="20" value="<?php echo $this->result->performance; ?>" />
      </td>
    </tr>
    <tr>
      <td width="100" align="right" class="key">
        <label for="name">
          <?php echo JText::_('COM_TRACKS_Comment' ); ?>:
        </label>
      </td>
      <td>
        <?php echo $this->editor->display('comment', $this->result->comment, '100%', '200', '70', '10'); ?>
      </td>
    </tr>
	</table>
	</fieldset>
</div>

<div class="clr"></div>

<input type="hidden" name="option" value="com_tracks" />
<input type="hidden" name="controller" value="subroundresult" />
<input type="hidden" name="cid[]" value="<?php echo $this->result->id; ?>" />
<input type="hidden" name="subround_id" value="<?php echo $this->result->subround_id; ?>" />
<input type="hidden" name="task" value="" />
</form>
</div>