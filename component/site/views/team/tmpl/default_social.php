<?php
/**
* @version    $Id: default.php 101 2008-05-22 08:32:12Z julienv $
* @package    JoomlaTracks
* @copyright  Copyright (C) 2008 Julien Vonthron. All rights reserved.
* @license    GNU/GPL, see LICENSE.php
* Joomla Tracks is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/
 // no direct access
defined('_JEXEC') or die('Restricted access');

$socials = TracksHelperTools::getTeamSocialItems($this->data);

if (count($socials)):
?>
<div class="team-social">
	<h3><?php echo JTExt::_('COM_TRACKS_TEAM_SOCIAL_LINKS'); ?></h3>
	<ul>
		<?php foreach ($socials as $name => $s): ?>


			<li class="ts-<?php echo $name; ?>"><span class="social-lbl"><?php echo $s->label; ?></span>
				<?php echo JHtml::link($s->link, JHtml::image($s->icon, $s->label, null, true)); ?>
			</li>

		<?php endforeach ;?>
	</ul>
</div>
<?php endif;
