<?php
/**
* @version    $Id: default.php 101 2008-05-22 08:32:12Z julienv $ 
* @package    JoomlaTracks
* @copyright	Copyright (C) 2008 Julien Vonthron. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* Joomla Tracks is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/ 

// no direct access
defined('_JEXEC') or die('Restricted access'); ?>

<form name="add-ride" id="add-ride" method="post" action="index.php" enctype="multipart/form-data">
<label for="file"><?php echo JText::_('COM_TRACKS_SELECT_PICTURE'); ?></label>
<input type="file" name="file"/>

<input name="option" type="hidden" value="com_tracks" />
<input name="controller" type="hidden" value="addride" />
<input name="task" type="hidden" value="submit" />
<input name="tmpl" type="hidden" value="component" />
<input name="rid" type="hidden" value="<?php echo $this->rid; ?>" />
<input name="id" type="hidden" value="<?php echo $this->item ? $this->item->id : 0; ?>" />
<br/>
<button type="submit"><?php echo JText::_('COM_TRACKS_ADD_RIDE'); ?></button>
</form>