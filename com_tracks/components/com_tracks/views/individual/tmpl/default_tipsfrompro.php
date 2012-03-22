<?php

// no direct access
defined('_JEXEC') or die('Restricted access');

?>
<h2><?php echo JText::_('COM_TRACKS_TIPS_FROM_PRO'); ?></h2>

<form name="pro-tips" id="pro-tips" action="index.php" method="post">
<div class="tip-cat">
<label for="category"><?php echo JText::_('COM_TRACKS_TIPS_SELECT_category_LABEL'); ?></label>
<?php echo $this->tipscategory; ?>
</div>

<textarea name="tip" cols="80" rows="10"></textarea>

<input name="option" type="hidden" value="com_tracks" />
<input name="controller" type="hidden" value="individual" />
<input name="task" type="hidden" value="newtip" />
<input name="id" type="hidden" value="<?php echo $this->data->id; ?>" />
<br/>
<button type="submit"><?php echo JText::_('COM_TRACKS_TIPS_BUTTON_LABEL'); ?></button>
</form>