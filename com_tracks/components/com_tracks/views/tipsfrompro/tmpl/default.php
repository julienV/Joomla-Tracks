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

$delimage = JHTML::image('components/com_tracks/assets/images/delete.png', 'delete');
?>
<div id="tracks" class="tipsfrompro">

	<h1><?php echo $this->title; ?></h1>
	
	<ul>
	<?php foreach ($this->data as $row): ?>
	<?php $indlink = JHTML::link(TracksHelperRoute::getIndividualRoute($row->islug), $row->first_name.' '.$row->last_name); ?>
	<li>
		<div class="protips-title"><?php echo JText::sprintf('COM_TRACKS_TIPSFROMPRO_TIP_TITLE', $indlink, $row->category, strftime('%m/%d/%Y %H:%M')); ?>
		</div>
		<?php if ($this->user->get('id') == $row->user_id || $this->user->authorise('core.manage', 'com_tracks')): ?>
		<div class="tips-delete" rel="<?php echo JRoute::_('index.php?option=com_tracks&controller=individual&task=deltip&id='.$row->id); ?>"><?php echo $delimage; ?></div>
		<?php endif; ?>
		<div class="protips-text">
		<?php echo str_replace("\n", '<br/>', $row->tips); ?>
		</div>
	</li>
	<?php endforeach; ?>
	</ul>
	<div class="pagination">
		<p class="counter">
				<?php echo $this->pagination->getPagesCounter(); ?>
		</p>
		<?php echo $this->pagination->getPagesLinks(); ?>
	</div>
	
</div>