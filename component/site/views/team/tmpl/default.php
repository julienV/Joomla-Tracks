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

<div id="tracks">
<!-- Title -->
<h1><?php echo $this->data->name; ?></h1>
	<?php if ($this->canEdit): ?>
		<?php
			$url = TracksHelperRoute::getTeamEditRoute($this->data->id);
			$link = JHtml::link($url, JText::_('COM_TRACKS_EDIT'));
		?>
		<div class="tracks-edit-link"><?php echo $link; ?></div>
	<?php endif; ?>

<!-- Content -->
<?php if ($this->data->picture): ?>
<div id="teampic">
	<?php echo $this->data->picture; ?>
</div>
<?php endif; ?>

<div id="teamdetails">
	<?php echo $this->data->description; ?>
</div>
<div class="clear"></div>

<?php if (count($this->individuals)): ?>
	<h2 class="team-inds"><?php echo JTExt::_('COM_TRACKS_VIEW_TEAM_INDIVIDUALS'); ?></h2>
	<?php foreach ($this->individuals as $proj): ?>
	<div class="project-inds"><span class="project-title"><?php echo current($proj)->project_name; ?></span>
	<?php foreach ($proj as $i): ?>
	<?php $text = ($i->number ? $i->number.' ' : '').$i->first_name.' '.$i->last_name; ?>
	<div class="team-ind"><?php echo JHTML::link(TracksHelperRoute::getIndividualRoute($i->slug, $i->projectslug), $text); ?></div>
	<?php endforeach; ?>
	</div>
	<?php endforeach; ?>
<?php endif; ?>
<div class="clear"></div>
<p class="copyright">
  <?php echo HTMLtracks::footer( ); ?>
</p>
</div>
