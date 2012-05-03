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



<h3 class="title" style="clear:both;"><?php echo $project[0]->seasonname; ?> <?php echo $k; ?></h3>

      <UL>
<!--
      <li style="zwidth:100%;text-align:right;background:#f5f5ff;"><?php echo JText::_('COM_TRACKS_ROUND'); ?></li>
      <?php if ($this->params->get('indview_results_showrace', 1)): ?>
	    <li style="zwidth:100%;text-align:right;background:#f5f5ff;"><?php echo JText::_('COM_TRACKS_RACE'); ?></li>
      <?php endif; ?>     
      <?php if ($this->params->get('indview_results_showrank', 1)): ?>
	    <li style="width:100%;text-align:right;background:#f5f5ff;"><?php echo JText::_('COM_TRACKS_RANK'); ?></li>
      <?php endif; ?>
-->
      </UL>

    <?php foreach ($project as $result): ?>

      <?php if ( ( $result->subroundname == "freestyle final") || ( $result->subroundname == "freeride final") || ( $result->subroundname == "racing final") ): ?>

      <UL style="margin-left:3px;line-height:13px;">

      <li style="width:85%;"><?php echo JHTML::link(JRoute::_(TracksHelperRoute::getRoundResultRoute($result->prslug)), $result->roundname); ?></li>

<!--
        <li><?php echo $result->subroundname; ?></li>
-->

      <li style="font-size:15px;width:10%;text-align:right;<?php echo ($result->rank == 1 ? 'color:red;font-weight:bold;' : ''); ?>"><?php echo ($result->rank == 0 ? '-' : $result->rank); ?></li>

      <?php endif; ?>

      </UL>
    <?php endforeach; ?>

<?php endforeach; ?>
<?php else: ?>
<span id="no-results"><?php echo JText::_('COM_TRACKS_VIEW_INDIVIDUAL_NO_RESULTS'); ?></span>
<?php endif; ?>
</div>
