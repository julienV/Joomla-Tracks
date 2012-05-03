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

<base target="_parent" >







<div id="externalrider" style="padding-bottom:20px;">
<?php if (count($this->results)): ?>
<?php foreach ($this->results as $k => $project): ?>

  <?php if ($project[0]->seasonname == "event"):?>

      <h3 class="title" style="clear:both;"><?php echo $k; ?></h3>

      <?php foreach ($project as $result): ?>

        <?php if ( strstr ($result->subroundname,'final') ): ?>

        <UL style="margin-left:3px;line-height:13px;">
        <li style="width:85%;"><?php echo JHTML::link(JRoute::_(TracksHelperRoute::getRoundResultRoute($result->prslug)), $result->roundname); ?></li>
        <li style="font-size:15px;width:10%;text-align:right;<?php echo ($result->rank == 1 ? 'color:red;font-weight:bold;' : ''); ?>"><?php echo ($result->rank == 0 ? '-' : $result->rank); ?></li>
        </UL>

        <?php endif; ?>

      <?php endforeach; ?>

  <?php endif; ?>

<?php endforeach; ?>
<?php else: ?>
<span id="no-results"><?php echo JText::_('COM_TRACKS_VIEW_INDIVIDUAL_NO_RESULTS'); ?></span>
<?php endif; ?>
</div>











<div id="externalrider" style="padding-bottom:20px;">
<?php if (count($this->results)): ?>
<?php foreach ($this->results as $k => $project): ?>

  <?php if ($project[0]->seasonname != "event"):?>

      <h3 class="title" style="clear:both;"><?php echo $k; ?></h3>

      <?php $n = 0; ?>
      <?php foreach ($project as $result): ?>

        <?php if ( strstr ($result->subroundname,'final') ): ?>

        <UL style="margin-left:3px;line-height:13px;">
        <li style="width:85%;"><A HREF="<?php echo TracksHelperRoute::getRoundResultRoute($result->prslug) ?>"><?php echo $project[$n]->seasonname; ?> - <?php echo $result->roundname; ?></A></li>
  <!--
          <li><?php echo $result->subroundname; ?></li>
  -->
        <li style="font-size:15px;width:10%;text-align:right;"><?php echo ($result->rank == 0 ? '-' : $result->rank); ?></li>
        </UL>

        <?php endif; ?>

      <?php $n++; ?>

      <?php endforeach; ?>

  <?php endif; ?>

<?php endforeach; ?>
<?php else: ?>
<span id="no-results"><?php echo JText::_('COM_TRACKS_VIEW_INDIVIDUAL_NO_RESULTS'); ?></span>
<?php endif; ?>
</div>
