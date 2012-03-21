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
defined('_JEXEC') or die('Restricted access');

?>

<div id="tracks" class="latest-updates">

	<h1><?php echo JText::_('COM_TRACKS_latest_updates_title_view' ) ?></h1>
	
	<ul>
	<?php foreach ($this->data as $row): ?>
	<li><?php echo $row->text.' ('.strftime('%m/%d/%Y %H:%M').')'; ?></li>
	<?php endforeach; ?>
	</ul>
	<div class="pagination">
		<p class="counter">
				<?php echo $this->pagination->getPagesCounter(); ?>
		</p>
		<?php echo $this->pagination->getPagesLinks(); ?>
	</div>
	
</div>