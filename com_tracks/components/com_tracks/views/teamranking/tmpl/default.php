<?php
/**
* @version    $Id: default.php 103 2008-05-23 15:45:29Z julienv $ 
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
<h1><?php echo $this->project->name . ' ' . JText::_('COM_TRACKS_Team_Rankings' ); ?></h1>

<table class="raceResults" cellspacing="0" cellpadding="0" summary="">
  <tbody>
    <tr>
      <th><?php echo JText::_('COM_TRACKS_POSITION_SHORT' ); ?></th>
      <?php if ($this->projectparams->get('showflag') && $this->params->get('showflag')): ?>
      <th><?php echo JText::_('COM_TRACKS_COUNTRY_SHORT' ); ?></th>
      <?php endif; ?>
      <th><?php echo JText::_('COM_TRACKS_Team' ); ?></th>
      <th><?php echo JText::_('COM_TRACKS_Points' ); ?></th>
      <th><?php echo JText::_('COM_TRACKS_Wins' ); ?></th>
      <th><?php echo JText::_('COM_TRACKS_Best_rank' ); ?></th>
    </tr>
    <?php
    $k = 0; 
    foreach( $this->rankings AS $ranking )
    {     
      $link_team = JRoute::_( TracksHelperRoute::getTeamRoute($ranking->slug, $this->project->slug) ); 
      ?>
      <tr class="<?php echo ($k++ % 2 ? 'd1' : 'd0'); ?>">
        <td><?php echo $ranking->rank; ?></td>
        <?php if ($this->projectparams->get('showflag') && $this->params->get('showflag')): ?>
        <td>
          <?php if ( $ranking->country_code ): ?>
          <img src="<?php echo TracksCountries::getIsoFlag($ranking->country_code); ?>"
             title="<?php echo $ranking->country_code; ?>"
             alt="<?php echo $ranking->country_code; ?>"/>
          <?php endif ?>
        </td>
        <?php endif; ?>
        <td>
            <a href="<?php echo $link_team; ?>"
               title="<?php echo JText::_('COM_TRACKS_Details' ); ?>"> 
            <?php echo $ranking->team_name; ?>
            <?php //echo JHTML::image('media/com_tracks/images/teams/'.$ranking->team_logo, $ranking->team_name, array('title' => $ranking->team_name, 'class' => 'ranking-logo')); ?>
            </a>
        </td>
        <td><?php echo $ranking->points; ?></td>
        <td><?php echo $ranking->wins; ?></td>
        <td><?php echo $ranking->best_rank; ?></td>
      </tr>
      <?php
    }
    ?>
  </tbody>
</table>
<p class="copyright">
  <?php echo HTMLtracks::footer( ); ?>
</p>
</div>
  
