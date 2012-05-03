<?php
/**
* @version    $Id: form.php 128 2008-06-06 08:08:04Z julienv $ 
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
	JToolBarHelper::title(   JText::_('COM_TRACKS_Individual' ).': <small><small>[ ' . $text.' ]</small></small>' );
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

<script type="text/javascript">
	window.addEvent('domready', function(){
		document.id('has_other_sponsor').addEvent('click', function(){
			if (this.getProperty('checked')) {
				document.id('sponsor_other').removeProperty('disabled');
			}
			else {
				document.id('sponsor_other').setProperty('disabled', 'disabled');
			}				
		});
	
		document.id('picture').addEvent('change', function(){
			if (this.get('value')) {
				$('picture_preview').empty().adopt(
						new Element('img', {
							src: '../'+this.get('value'),
							class: 're-image-preview',
							alt: 'preview'
						}));
			}
			else {
				$('picture_preview').empty();
			}
		}).fireEvent('change');
	
		document.id('picture_small').addEvent('change', function(){
			if (this.get('value')) {
				$('picture_small_preview').empty().adopt(
						new Element('img', {
							src: '../'+this.get('value'),
							class: 're-image-preview',
							alt: 'preview'
						}));
			}
			else {
				$('picture_small_preview').empty();
			}
		}).fireEvent('change');
	
		document.id('picture_background').addEvent('change', function(){
			if (this.get('value')) {
				$('picture_background_preview').empty().adopt(
						new Element('img', {
							src: '../'+this.get('value'),
							class: 're-image-preview',
							alt: 'preview'
						}));
			}
			else {
				$('picture_background_preview').empty();
			}
		}).fireEvent('change');

	});
	
	Joomla.submitbutton = function (pressbutton) {
		var form = document.adminForm;
		if (pressbutton == 'cancel') {
			Joomla.submitform( pressbutton );
			return;
		}
		if (document.id('user_id_id').value && document.id('user_id_id').value != currentuser) {
			if (confirm('<?php echo JText::_('COM_TRACKS_Individual_email_user_confirm', true ); ?>')) {
				document.id('emailuser').value = 1;
			}
			else {
				document.id('emailuser').value = 0;
			}
		}

		// do field validation
		if (form.last_name.value == ""){
			alert( "<?php echo JText::_('COM_TRACKS_Individual_must_have_a_last_name', true ); ?>" );
		} else {
			Joomla.submitform( pressbutton );
		}
	}

	var currentuser = <?php echo $this->object->user_id ? $this->object->user_id : 0; ?>;
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
		<legend><?php echo JText::_('COM_TRACKS_Individual' ); ?></legend>

		<table class="admintable">
		<tr>
			<td width="100" align="right" class="key">
				<label for="full_name">
					<?php echo JText::_('COM_TRACKS_First_name' ); ?>:
				</label>
			</td>
			<td>
				<input class="text_area" type="text" name="first_name" id="first_name" size="32" maxlength="40" value="<?php echo $this->object->first_name; ?>" />
			</td>
		</tr>
		<tr>
			<td width="100" align="right" class="key">
				<label for="last_name">
					<?php echo JText::_('COM_TRACKS_Last_name' ); ?>:
				</label>
			</td>
			<td>
				<input class="text_area" type="text" name="last_name" id="last_name" size="32" maxlength="40" value="<?php echo $this->object->last_name; ?>" />
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
        <label for="nickname">
          <?php echo JText::_('COM_TRACKS_Nickname' ); ?>:
        </label>
      </td>
      <td>
        <input class="text_area" type="text" name="nickname" id="nickname" size="32" maxlength="40" value="<?php echo $this->object->nickname; ?>" />
      </td>
    </tr>
    
    <tr>
      <td width="100" align="right" class="key">
        <label for="gender">
          <?php echo JText::_('COM_TRACKS_GENDER' ); ?>:
        </label>
      </td>
      <td>
          <?php echo $this->lists['gender']; ?>
      </td>
    </tr>
    
		<tr>
			<td width="100" align="right" class="key">
				<?php echo $this->form->getLabel('user_id'); ?>
			</td>
			<td>
				<?php echo $this->form->getInput('user_id'); ?>
      </td>
		</tr>
		
		<!-- start image import -->
    <tr>
      <td width="100" align="right" class="key">
				<?php echo $this->form->getLabel('picture'); ?>
      </td>
      <td>
        <table>
          <tr>
            <td><?php echo $this->form->getInput('picture'); ?></td>
            <td>
              <span id="picture_preview"></span>
            </td>
          </tr>
        </table>
      </td>
    </tr>
    <!-- end image import -->
    <!-- start image import -->
    <tr>
      <td width="100" align="right" class="key">
				<?php echo $this->form->getLabel('picture_small'); ?>
      </td>
      <td>
        <table>
          <tr>
            <td><?php echo $this->form->getInput('picture_small'); ?></td>
            <td>
              <span id="picture_small_preview"></span>
            </td>
          </tr>
        </table>
      </td>
    </tr>
    <!-- end image import -->
    <!-- start image import -->
    <tr>
      <td width="100" align="right" class="key">
				<?php echo $this->form->getLabel('picture_background'); ?>
      </td>
      <td>
        <table>
          <tr>
            <td><?php echo $this->form->getInput('picture_background'); ?></td>
            <td>
              <span id="picture_background_preview"></span>
            </td>
          </tr>
        </table>
      </td>
    </tr>
    <!-- end image import -->
    
    <tr>
      <td width="100" align="right" class="key">
        <label for="height">
          <?php echo JText::_('COM_TRACKS_Height' ); ?>:
        </label>
      </td>
      <td>
        <input class="text_area" type="text" name="height" id="height" size="10" maxlength="10" value="<?php echo $this->object->height; ?>" />
      </td>
    </tr>
    <tr>
      <td width="100" align="right" class="key">
        <label for="weight">
          <?php echo JText::_('COM_TRACKS_Weight' ); ?>:
        </label>
      </td>
      <td>
        <input class="text_area" type="text" name="weight" id="weight" size="10" maxlength="10" value="<?php echo $this->object->weight; ?>" />
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
		<tr>
			<td width="100" align="right" class="key">
				<label for="last_name">
					<?php echo JText::_('COM_TRACKS_Date_of_birth_YYYY_MM_DD' ); ?>:
				</label>
			</td>
			<td>
			<?php 
      echo  JHTML::calendar( $this->object->dob, 'dob', 'dob' );
      ?>
      </td>
		</tr>
    <tr>
      <td width="100" align="right" class="key">
        <label for="hometown">
          <?php echo JText::_('COM_TRACKS_Hometown' ); ?>:
        </label>
      </td>
      <td>
        <input class="text_area" type="text" name="hometown" id="hometown" size="30" maxlength="50" value="<?php echo $this->object->hometown; ?>" />
      </td>
    </tr>
		<tr>
			<td width="100" align="right" class="key">
				<label for="last_name">
					<?php echo JText::_('COM_TRACKS_Address' ); ?>:
				</label>
			</td>
			<td>
				<input class="text_area" type="text" name="address" id="address" size="100" maxlength="200" value="<?php echo $this->object->address; ?>" />
			</td>
		</tr>
		<tr>
			<td width="100" align="right" class="key">
				<label for="last_name">
					<?php echo JText::_('COM_TRACKS_Postcode' ); ?>:
				</label>
			</td>
			<td>
				<input class="text_area" type="text" name="postcode" id="postcode" size="10" maxlength="10" value="<?php echo $this->object->postcode; ?>" />
			</td>
		</tr>
		<tr>
			<td width="100" align="right" class="key">
				<label for="last_name">
					<?php echo JText::_('COM_TRACKS_City' ); ?>:
				</label>
			</td>
			<td>
				<input class="text_area" type="text" name="city" id="city" size="30" maxlength="50" value="<?php echo $this->object->city; ?>" />
			</td>
		</tr>
		<tr>
			<td width="100" align="right" class="key">
				<label for="last_name">
					<?php echo JText::_('COM_TRACKS_State' ); ?>:
				</label>
			</td>
			<td>
				<input class="text_area" type="text" name="state" id="state" size="30" maxlength="50" value="<?php echo $this->object->state; ?>" />
			</td>
		</tr>
		<tr>
			<td width="100" align="right" class="key">
				<label for="last_name">
					<?php echo JText::_('COM_TRACKS_Country' ); ?>:
				</label>
			</td>
			<td>
				<input class="text_area" type="text" name="country" id="country" size="30" maxlength="50" value="<?php echo $this->object->country; ?>" />
			</td>
		</tr>
		<tr>
			<td width="100" align="right" class="key">
				<label for="sponsors">
					<?php echo JText::_('COM_TRACKS_sponsors' ); ?>:
				</label>
			</td>
			<td>
				<div id="d-sponsors">
				<?php foreach ((array) $this->lists['sponsor'] as $opt): ?>
				<input class="inputbox checkbox" type="checkbox" name="sponsors[]" 
				       value="<?php echo $opt->value; ?>" 
				       <?php echo (in_array($opt->value, $this->sponsors) ? ' checked="checked"' : ''); ?>/> <label><?php echo $opt->text; ?></label>
				<?php endforeach; ?>
				<input class="inputbox checkbox" type="checkbox" name="has_other_sponsor" 
				       value="-1" id="has_other_sponsor"
				       <?php echo ($this->object->sponsor_other ? ' checked="checked"' : ''); ?>/> <label><?php echo JText::_('COM_TRACKS_SPONSOR_OTHER'); ?></label>
				<input name="sponsor_other" id="sponsor_other" 
				       value="<?php echo $this->object->sponsor_other; ?>" 
				       <?php echo (empty($this->object->sponsor_other) ? ' disabled="disabled"' : ''); ?>/>
       </div>
			</td>
		</tr>
		<tr>
			<td width="100" align="right" class="key">
				<label for="name">
					<?php echo JText::_('COM_TRACKS_Description' ); ?>:
				</label>
			</td>
			<td>
				<?php echo $this->editor->display('description', $this->object->description, '100%', '400', '70', '15'); ?>
      </td>
		</tr>
	</table>
	</fieldset>
</div>

<div class="clr"></div>

<input type="hidden" name="option" value="com_tracks" />
<input type="hidden" name="controller" value="individual" />
<input type="hidden" name="emailuser" id="emailuser" value="0" />
<input type="hidden" name="cid[]" value="<?php echo $this->object->id; ?>" />
<input type="hidden" name="task" value="" />
</form>
</div>