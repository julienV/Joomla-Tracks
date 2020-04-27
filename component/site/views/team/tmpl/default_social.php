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

$socials = TrackslibHelperTools::getTeamSocialItems($this->data);

if (!empty($socials)):
?>
<div class="tracks-team__social">
	<h3><?php echo JTExt::_('COM_TRACKS_TEAM_SOCIAL_LINKS'); ?></h3>
	<div class="tracks-team__social__list">
		<?php foreach ($socials as $name => $s): ?>
			<div class="tracks-team__social__list__item ts-<?php echo $name; ?>">
				<?php echo JHtml::link($s->link, JHtml::image($s->icon, $s->label, null, true)); ?>
			</div>
		<?php endforeach ;?>
	</div>
</div>
<?php endif;
