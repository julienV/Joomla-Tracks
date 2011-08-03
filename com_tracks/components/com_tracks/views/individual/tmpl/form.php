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

<script language="javascript" type="text/javascript">
	function submitbutton(pressbutton) {
		var form = document.individualform;
		if (pressbutton == 'cancel') {
			submitform( pressbutton );
			return;
		}

		// do field validation
		if (form.last_name.value == ""){
			alert( "<?php echo JText::_( 'COM_TRACKS_VIEW_INDIVIDUAL_MUST_HAVE_A_LASTNAME', true ); ?>" );
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

<form enctype="multipart/form-data" action="index.php" method="post" name="individualform" id="individualform">
  
  <div class="componentheading<?php echo $this->params->get( 'pageclass_sfx' ); ?>">
    <?php echo $this->escape($this->params->get('page_title')); ?>
    <?php 
    if (!$this->object->id) { 
      echo " - " . JText::_( 'COM_TRACKS_VIEW_INDIVIDUAL_CREATE_PROFILE' );
    }
    else {
      echo " - " . JText::_( 'COM_TRACKS_VIEW_INDIVIDUAL_EDIT_PROFILE' );    	
    }
    ?>    
  </div>
  
<table cellpadding="5" cellspacing="0" border="0" width="100%">
		<tr>
      <td width="100" align="right" class="key">
        <label for="full_name">
          <?php echo JText::_( 'COM_TRACKS_VIEW_INDIVIDUAL_First_name' ); ?>:
        </label>
      </td>
      <td>
        <input class="text_area" type="text" name="first_name" id="first_name" size="32" maxlength="40" value="<?php echo $this->object->first_name; ?>" />
      </td>
    </tr>
    <tr>
      <td width="100" align="right" class="key">
        <label for="last_name">
          <?php echo JText::_( 'COM_TRACKS_VIEW_INDIVIDUAL_Last_name' ); ?>:
        </label>
      </td>
      <td>
        <input class="text_area" type="text" name="last_name" id="last_name" size="32" maxlength="40" value="<?php echo $this->object->last_name; ?>" />
      </td>
    </tr>
    <tr>
      <td width="100" align="right" class="key">
        <label for="nickname">
          <?php echo JText::_( 'COM_TRACKS_VIEW_INDIVIDUAL_Nickname' ); ?>:
        </label>
      </td>
      <td>
        <input class="text_area" type="text" name="nickname" id="nickname" size="32" maxlength="40" value="<?php echo $this->object->nickname; ?>" />
      </td>
    </tr>
    <?php if ($this->user->authorise('core.manage', 'com_tracks')): ?>
    <tr>
      <td width="100" align="right" class="key">
        <label for="user_id">
          <?php echo JText::_( 'COM_TRACKS_VIEW_INDIVIDUAL_User' ); ?>:
        </label>
      </td>
      <td>
        <?php echo $this->lists['users']; ?>
      </td>
    </tr>    
    <?php endif; ?>
    <tr>
      <td width="100" align="right" class="key">
        <label for="picture">
          <?php echo JText::_( 'COM_TRACKS_VIEW_INDIVIDUAL_Picture' ); ?>:
        </label>
      </td>
      <td>
        <input class="inputbox" name="picture" id="picture" type="file" />
        <?php echo $this->object->picture; ?>
      </td>
    </tr>
    <tr>
      <td width="100" align="right" class="key">
        <label for="picture_small">
          <?php echo JText::_( 'COM_TRACKS_VIEW_INDIVIDUAL_Small_picture' ); ?>:
        </label>
      </td>
      <td>
        <input class="inputbox" name="picture_small" id="picture_small" type="file" />
        <?php echo $this->object->picture_small; ?>
      </td>
    </tr>
    <tr>
      <td width="100" align="right" class="key">
        <label for="height">
          <?php echo JText::_( 'COM_TRACKS_VIEW_INDIVIDUAL_Height' ); ?>:
        </label>
      </td>
      <td>
        <input class="text_area" type="text" name="height" id="height" size="10" maxlength="10" value="<?php echo $this->object->height; ?>" />
      </td>
    </tr>
    <tr>
      <td width="100" align="right" class="key">
        <label for="weight">
          <?php echo JText::_( 'COM_TRACKS_VIEW_INDIVIDUAL_Weight' ); ?>:
        </label>
      </td>
      <td>
        <input class="text_area" type="text" name="weight" id="weight" size="10" maxlength="10" value="<?php echo $this->object->weight; ?>" />
      </td>
    </tr>
    <tr>
      <td width="100" align="right" class="key">
        <label for="country_code">
          <?php echo JText::_( 'COM_TRACKS_VIEW_INDIVIDUAL_COUNTRY' ); ?>:
        </label>
      </td>
      <td>
        <?php echo $this->lists['countries']; ?>
      </td>
    </tr>
    <tr>
      <td width="100" align="right" class="key">
        <label for="dob">
          <?php echo JText::_( 'COM_TRACKS_VIEW_INDIVIDUAL_DOB' ); ?>:
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
          <?php echo JText::_( 'COM_TRACKS_VIEW_INDIVIDUAL_Hometown' ); ?>:
        </label>
      </td>
      <td>
        <input class="text_area" type="text" name="hometown" id="hometown" size="30" maxlength="50" value="<?php echo $this->object->hometown; ?>" />
      </td>
    </tr>
    <tr>
      <td width="100" align="right" class="key">
        <label for="last_name">
          <?php echo JText::_( 'COM_TRACKS_VIEW_INDIVIDUAL_Address' ); ?>:
        </label>
      </td>
      <td>
        <input class="text_area" type="text" name="address" id="address" size="32" maxlength="200" value="<?php echo $this->object->address; ?>" />
      </td>
    </tr>
    <tr>
      <td width="100" align="right" class="key">
        <label for="last_name">
          <?php echo JText::_( 'COM_TRACKS_VIEW_INDIVIDUAL_Postcode' ); ?>:
        </label>
      </td>
      <td>
        <input class="text_area" type="text" name="postcode" id="postcode" size="10" maxlength="10" value="<?php echo $this->object->postcode; ?>" />
      </td>
    </tr>
    <tr>
      <td width="100" align="right" class="key">
        <label for="last_name">
          <?php echo JText::_( 'COM_TRACKS_VIEW_INDIVIDUAL_City' ); ?>:
        </label>
      </td>
      <td>
        <input class="text_area" type="text" name="city" id="city" size="30" maxlength="50" value="<?php echo $this->object->city; ?>" />
      </td>
    </tr>
    <tr>
      <td width="100" align="right" class="key">
        <label for="last_name">
          <?php echo JText::_( 'COM_TRACKS_VIEW_INDIVIDUAL_State' ); ?>:
        </label>
      </td>
      <td>
        <input class="text_area" type="text" name="state" id="state" size="30" maxlength="50" value="<?php echo $this->object->state; ?>" />
      </td>
    </tr>
    <tr>
      <td width="100" align="right" class="key">
        <label for="last_name">
          <?php echo JText::_( 'COM_TRACKS_VIEW_INDIVIDUAL_Country' ); ?>:
        </label>
      </td>
      <td>
        <input class="text_area" type="text" name="country" id="country" size="30" maxlength="50" value="<?php echo $this->object->country; ?>" />
      </td>
    </tr>
    <tr>
      <td width="100" align="right" class="key">
        <label for="name">
          <?php echo JText::_( 'COM_TRACKS_VIEW_INDIVIDUAL_Description' ); ?>:
        </label>
      </td>
      <td>
        <?php echo $this->editor->display('description', $this->object->description, '100%', '400', '70', '15'); ?>
      </td>
    </tr>
</table>

<button class="button" type="submit" onclick="submitbutton( this.form );return false;"><?php echo JText::_('COM_TRACKS_VIEW_INDIVIDUAL_Save'); ?></button>

<input type="hidden" name="option" value="com_tracks" />
<input type="hidden" name="controller" value="individual" />
<input type="hidden" name="i" value="<?php echo $this->object->id; ?>" />    
<input type="hidden" name="task" value="save" />
<?php if (!$this->user->authorise('core.manage', 'com_tracks')): ?>
<input type="hidden" name="user_id" value="<?php echo $this->user->id; ?>" />  
<?php endif; ?>

<?php echo JHTML::_( 'form.token' ); ?>
</form>
