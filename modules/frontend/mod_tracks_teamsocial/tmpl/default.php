<?php
/**
* @version    $Id: tracks.php 109 2008-05-24 11:05:07Z julienv $
* @package    JoomlaTracks
* @subpackage RankingModule
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

JHtml::_('behavior.tooltip');
?>Tracks team link
<div class="mod_tracks_teamsocial">
	<ul>
		<?php foreach ($links as $name => $s): ?>
			<?php $attr = array('class' => 'hasTip', 'title' => $s->label); ?>
			<li class="ts-<?php echo $name; ?>">
				<?php echo JHtml::link($s->link, JHtml::image($s->icon, $s->label, null, true), $attr); ?>
			</li>

		<?php endforeach ;?>
	</ul>
</div>
