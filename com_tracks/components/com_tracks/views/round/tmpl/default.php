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
<table class="contentpaneopen">
<tbody>
<tr>
<td class="contentheading" width="100%"><?php echo $this->round->name; ?></td>
</tr>
</tbody>
</table>

<!-- Content -->
<table class="contentpaneopen">
<tbody>
<tr>
<td><?php echo $this->round->description; ?></td>
</tr>
</tbody>
</table>

<p class="copyright">
  <?php echo HTMLtracks::footer( ); ?>
</p>
</div>