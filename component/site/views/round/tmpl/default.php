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

<div id="tracks<?php echo $this->params->get('pageclass_sfx'); ?>">

<?php if ($this->params->get('show_page_heading', 1)) : ?>
<h1>
	<?php echo $this->escape($this->params->get('page_heading')); ?>
</h1>
<?php endif; ?>

<h2><?php echo $this->round->name; ?></h2>

<!-- Content -->

<div class="round-description">
<?php if ($img = TrackslibHelperImage::modalimage($this->round->picture, $this->round->name, 400, array('class' => 'round-image'))): ?>
	<?php echo $img; ?>
<?php endif;?>
<?php echo $this->round->description; ?>
</div>
<div class="tracks-clear"></div>
<p class="copyright">
  <?php echo TrackslibHelperTools::footer( ); ?>
</p>
</div>
