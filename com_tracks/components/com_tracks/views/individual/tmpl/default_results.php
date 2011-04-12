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

<h2><?php echo JText::_('Results'); ?></h2>

<div id="individualresults">
<?php if (count($this->results)): ?>
<?php foreach ($this->results as $k => $project): ?>
<h3>
<?php echo $k; ?>
<?php if ($this->params->get('indview_results_showcompetition', 1) || $this->params->get('indview_results_showseason', 1)) {
  $html = ' (';
  $elements = array();  
  if ($this->params->get('indview_results_showcompetition', 1)) {
    $elements[] = $project[0]->competitionname;
  }
  if ($this->params->get('indview_results_showseason', 1)) {
    $elements[] = $project[0]->seasonname;
  }
  $html .= implode(' / ', $elements);
  $html .= ')';
  echo $html;
}
?>
</h3>
<table class="raceResults">
  <thead>
    <tr>    
      <?php if ($this->params->get('indview_results_showteam', 1)): ?>
      <th><?php echo JText::_('TEAM'); ?></th>
      <?php endif; ?>     
      <th><?php echo JText::_('ROUND'); ?></th>
      <?php if ($this->params->get('indview_results_showrace', 1)): ?>
	    <th><?php echo JText::_('RACE'); ?></th>
      <?php endif; ?>     
      <?php if ($this->params->get('indview_results_showperformance', 1)): ?>
      <th><?php echo JText::_('PERFORMANCE'); ?></th>
      <?php endif; ?>    
      <?php if ($this->params->get('indview_results_showrank', 1)): ?>
	    <th><?php echo JText::_('RANK'); ?></th>
      <?php endif; ?>     
      <?php if ($this->params->get('indview_results_points', 1)): ?>
	    <th><?php echo JText::_('POINTS'); ?></th>
      <?php endif; ?>     
    </tr>
  </thead>
  <tbody>
    <?php foreach ($project as $result): ?>
    <tr<?php echo ($result->rank == 1 ? 'class="winner"' : ''); ?>>    
      <?php if ($this->params->get('indview_results_showteam', 1)): ?>
      <td><?php echo $result->teamname; ?></td>
      <?php endif; ?>
      <td><?php echo JHTML::link(JRoute::_(TracksHelperRoute::getRoundResultRoute($result->prslug)), $result->roundname); ?></td>
      <?php if ($this->params->get('indview_results_showrace', 1)): ?>
      <td><?php echo $result->subroundname; ?></td>
      <?php endif; ?> 
      <?php if ($this->params->get('indview_results_showperformance', 1)): ?>
      <td><?php echo $result->performance; ?></td>
      <?php endif; ?>
      <?php if ($this->params->get('indview_results_showrank', 1)): ?>
      <td><?php echo $result->rank; ?></td>
      <?php endif; ?>
      <?php if ($this->params->get('indview_results_points', 1)): ?>
      <td><?php echo TracksHelperTools::getSubroundPoints($result); ?></td>
      <?php endif; ?>
    </tr>
    <?php endforeach; ?>
  </tbody>
</table>
<?php endforeach; ?>
<?php else: ?>
<span id="no-results"><?php echo JText::_('COM_TRACKS_VIEW_INDIVIDUAL_NO_RESULTS'); ?></span>
<?php endif; ?>
</div>