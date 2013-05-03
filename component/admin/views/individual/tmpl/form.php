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

<script language="javascript" type="text/javascript">

	window.addEvent('domready', function(){
	
		$('picture').addEvent('change', function(){
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
	
		$('picture_small').addEvent('change', function(){
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
		
	});

	Joomla.submitbutton = function (pressbutton) {
		var form = document.adminForm;
		if (pressbutton == 'cancel') {
			Joomla.submitform( pressbutton );
			return;
		}

		// do field validation
		if (form.last_name.value == ""){
			alert( "<?php echo JText::_('COM_TRACKS_Individual_must_have_a_last_name', true ); ?>" );
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
		<legend><?php echo JText::_('COM_TRACKS_Individual' ); ?></legend>

		<ul class="adminformlist">
			<li>
				<label for="full_name">
					<?php echo JText::_('COM_TRACKS_First_name' ); ?>:
				</label>
				<input class="text_area" type="text" name="first_name" id="first_name" size="32" maxlength="40" value="<?php echo $this->object->first_name; ?>" />
			</li>
			<li>
				<label for="last_name">
					<?php echo JText::_('COM_TRACKS_Last_name' ); ?>:
				</label>
				<input class="text_area" type="text" name="last_name" id="last_name" size="32" maxlength="40" value="<?php echo $this->object->last_name; ?>" />
			</li>
			<li>
				<label for="alias"> <?php echo JText::_('COM_TRACKS_Alias' ); ?>:</label>
				<input class="text_area" type="text" name="alias" id="alias"
	      size="32" maxlength="250" value="<?php echo $this->object->alias?>" />
			</li>
			<li>
				<label for="nickname">
          <?php echo JText::_('COM_TRACKS_Nickname' ); ?>:
        </label>
        <input class="text_area" type="text" name="nickname" id="nickname" size="32" maxlength="40" value="<?php echo $this->object->nickname; ?>" />
			</li>
			<li>
				<?php echo $this->form->getLabel('user_id'); ?>
				<?php echo $this->form->getInput('user_id'); ?> 
			</li>
			<li>
				<?php echo $this->form->getLabel('picture'); ?>
				<?php echo $this->form->getInput('picture'); ?> 
			</li>
			<li>				
				<?php echo $this->form->getLabel('picture_small'); ?>
				<?php echo $this->form->getInput('picture_small'); ?>
			</li>
			<li>				
        <label for="height">
          <?php echo JText::_('COM_TRACKS_Height' ); ?>:
        </label>
        <input class="text_area" type="text" name="height" id="height" size="10" maxlength="10" value="<?php echo $this->object->height; ?>" />
			</li>
			<li>
				<label for="weight">
          <?php echo JText::_('COM_TRACKS_Weight' ); ?>:
        </label>
        <input class="text_area" type="text" name="weight" id="weight" size="10" maxlength="10" value="<?php echo $this->object->weight; ?>" />
			</li>
			<li>
				<label for="country_code">
					<?php echo JText::_('COM_TRACKS_COUNTRY' ); ?>:
				</label>
				<?php echo $this->lists['countries']; ?>
			</li>
			<li>
				<label for="dob">
					<?php echo JText::_('COM_TRACKS_Date_of_birth_YYYY_MM_DD' ); ?>:
				</label>
			<?php 
      echo  JHTML::calendar( $this->object->dob, 'dob', 'dob' );
      ?>
			</li>
			<li>
				<label for="hometown">
          <?php echo JText::_('COM_TRACKS_Hometown' ); ?>:
        </label>
        <input class="text_area" type="text" name="hometown" id="hometown" size="30" maxlength="50" value="<?php echo $this->object->hometown; ?>" />
			</li>
			<li>
				<label for="address">
					<?php echo JText::_('COM_TRACKS_Address' ); ?>:
				</label>
				<input class="text_area" type="text" name="address" id="address" size="50" maxlength="200" value="<?php echo $this->object->address; ?>" />
			</li>
			<li>
				<label for="postcode">
					<?php echo JText::_('COM_TRACKS_Postcode' ); ?>:
				</label>
				<input class="text_area" type="text" name="postcode" id="postcode" size="10" maxlength="10" value="<?php echo $this->object->postcode; ?>" />
			</li>
			<li>
				<label for="city">
					<?php echo JText::_('COM_TRACKS_City' ); ?>:
				</label>
				<input class="text_area" type="text" name="city" id="city" size="30" maxlength="50" value="<?php echo $this->object->city; ?>" />
			</li>
			<li>
				<label for="state">
					<?php echo JText::_('COM_TRACKS_State' ); ?>:
				</label>
				<input class="text_area" type="text" name="state" id="state" size="30" maxlength="50" value="<?php echo $this->object->state; ?>" />
			</li>
			<li>
				<label for="country">
					<?php echo JText::_('COM_TRACKS_Country' ); ?>:
				</label>
				<input class="text_area" type="text" name="country" id="country" size="30" maxlength="50" value="<?php echo $this->object->country; ?>" />
			</li>
		</ul>		
				
		<div class="clr"></div>
		<?php echo $this->form->getLabel('description'); ?>
		<div class="clr"></div>
		<?php echo $this->form->getInput('description'); ?>
	</fieldset>
</div>

<div class="clr"></div>

<input type="hidden" name="option" value="com_tracks" />
<input type="hidden" name="controller" value="individual" />
<input type="hidden" name="cid[]" value="<?php echo $this->object->id; ?>" />
<input type="hidden" name="task" value="" />
</form>
</div>