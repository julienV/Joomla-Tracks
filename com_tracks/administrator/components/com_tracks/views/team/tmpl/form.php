<?php
/**
* @version    $Id: form.php 98 2008-05-02 10:46:58Z julienv $ 
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
	JToolBarHelper::title(   JText::_('COM_TRACKS_Team' ).': <small><small>[ ' . $text.' ]</small></small>' );
	JToolBarHelper::save();
	JToolBarHelper::apply();
	if (!$edit)  {
		JToolBarHelper::cancel();
	} else {
		// for existing items the button is renamed `close`
		JToolBarHelper::cancel( 'cancel', 'Close' );
	}
	JToolBarHelper::help( 'screen.tracks.edit' );
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
    if ( validator.validate(form.name) === false ){
      alert( "<?php echo JText::_('COM_TRACKS_TEAM_NAME_IS_REQUIRED', true ); ?>" );
    } else {
      submitform( pressbutton );
    }
	}
</script>

<div id="tracksmain">
<form action="index.php" method="post" name="adminForm" id="adminForm">
<div class="col50">
	<fieldset class="adminform">
		<legend><?php echo JText::_('COM_TRACKS_Team' ); ?></legend>

		<table class="admintable">
		<tr>
			<td width="100" align="right" class="key">
				<label for="name">
					<?php echo JText::_('COM_TRACKS_Name' ); ?>:
				</label>
			</td>
			<td>
				<input class="text_area required" type="text" name="name" id="name" size="32" maxlength="40" value="<?php echo $this->object->name?>" />
			</td>
		</tr>
    <tr>
      <td width="100" align="right" class="key"><label for="alias"> <?php echo JText::_('COM_TRACKS_Alias' ); ?>:
      </label></td>
      <td><input class="text_area" type="text" name="alias" id="alias"
        size="32" maxlength="250" value="<?php echo $this->object->alias?>" />
      </td>
    </tr>
		<tr>
			<td width="100" align="right" class="key">
				<label for="short_name">
					<?php echo JText::_('COM_TRACKS_Short_name' ); ?>:
				</label>
			</td>
			<td>
				<input class="text_area" type="text" name="short_name" id="short_name" size="20" maxlength="20" value="<?php echo $this->object->short_name?>" />
			</td>
		</tr>
		<tr>
			<td width="100" align="right" class="key">
				<label for="acronym">
					<?php echo JText::_('COM_TRACKS_Acronym' ); ?>:
				</label>
			</td>
			<td>
				<input class="text_area" type="text" name="acronym" id="acronym" size="10" maxlength="10" value="<?php echo $this->object->acronym?>" />
			</td>
		</tr>
    <tr>
      <td width="100" align="right" class="key">
        <label for="last_name">
          <?php echo JText::_('COM_TRACKS_COUNTRY' ); ?>:
        </label>
      </td>
      <td>
        <?php echo $this->lists['countries']; ?>
      </td>
    </tr>
		    <!-- start image import -->
    <tr>
      <td width="100" align="right" class="key">
        <label for="image">
          <span class="hasTip" title='<?php echo JText::_('COM_TRACKS_TEAMPICTURE' ); ?>::<?php echo JText::_('COM_TRACKS_TEAMPICTUREDESC' ); ?>'>
            <?php echo JText::_('COM_TRACKS_TEAMPICTURE' ).':'; ?>
          </span>
        </label>
      </td>
      <td>
        <table>
          <tr>
            <td><?php echo $this->imageselect; ?></td>
            <td>
              <img class="imagepreview" src="../images/M_images/blank.png" name="picture_preview" id="picture_preview" width="80" height="80" border="2" alt="Preview" />
            </td>
          </tr>
        </table>
      </td>
    </tr>
    <!-- end image import -->
    <!-- start image import -->
    <tr>
      <td width="100" align="right" class="key">
        <label for="miniimage">
          <span class="hasTip" title='<?php echo JText::_('COM_TRACKS_TEAMSMALLPICTURE' ); ?>::<?php echo JText::_('COM_TRACKS_TEAMSMALLPICTUREDESC' ); ?>'>
            <?php echo JText::_('COM_TRACKS_TEAMSMALLPICTURE' ).':'; ?>
          </span>
        </label>
      </td>
      <td>
        <table>
          <tr>
            <td><?php echo $this->miniimageselect; ?></td>
            <td>
              <img class="imagepreview" src="../images/M_images/blank.png" name="mini_picture_preview" id="mini_picture_preview" width="80" height="80" border="2" alt="Preview" />
            </td>
          </tr>
        </table>
      </td>
    </tr>
    <!-- end image import -->
		<tr>
			<td width="100" align="right" class="key">
				<label for="details">
					<?php echo JText::_('COM_TRACKS_Details' ); ?>:
				</label>
			</td>
			<td>
				<?php echo $this->editor->display('description', $this->object->description, '600', '400', '70', '15'); ?>
      </td>
		</tr>
	</table>
	</fieldset>
</div>

<div class="clr"></div>

<input type="hidden" name="option" value="com_tracks" />
<input type="hidden" name="controller" value="team" />
<input type="hidden" name="cid[]" value="<?php echo $this->object->id; ?>" />
<input type="hidden" name="task" value="" />
</form>
</div>