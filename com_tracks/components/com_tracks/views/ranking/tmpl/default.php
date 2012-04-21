<?php
/**
* @version    $Id: default.php 135 2008-06-08 21:50:12Z julienv $ 
* @package    JoomlaTracks
* @copyright	Copyright (C) 2008 Julien Vonthron. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* Joomla Tracks is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/
defined('_JEXEC') or die('Restricted access'); ?>

<div id="tracks">

<h1><?php echo $this->project->name . ' ' . JText::_('COM_TRACKS_Rankings' ); ?></h1>

<table class="raceResults" cellspacing="0" cellpadding="0" summary="">
  <thead>
    <tr>
      <th><?php echo JText::_('COM_TRACKS_POSITION_SHORT' ); ?></th>
      
      <?php if ($this->params->get('shownumber')): ?>
      <th><?php echo JText::_('COM_TRACKS_NUMBER_SHORT' ); ?></th>
      <?php endif; ?>
      
      <?php if ($this->params->get('showflag')): ?>
      <th><?php echo JText::_('COM_TRACKS_COUNTRY_SHORT' ); ?></th>
      <?php endif; ?>
      
      <th><?php echo JText::_('COM_TRACKS_Individual' ); ?></th>
      
      <?php if ($this->params->get('showteams')): ?>
      <th><?php echo JText::_('COM_TRACKS_Team' ); ?></th>
      <?php endif; ?>
      
      <th><?php echo JText::_('COM_TRACKS_Points' ); ?></th>
      <th><?php echo JText::_('COM_TRACKS_Wins' ); ?></th>
      <th><?php echo JText::_('COM_TRACKS_Best_rank' ); ?></th>
      
      <?php if ($this->params->get('rk_show_top3')): ?>
      <th><?php echo JText::_('COM_TRACKS_TABLE_HEADER_TOP3' ); ?></th>
      <?php endif; ?>
      
      <?php if ($this->params->get('rk_show_top5')): ?>
      <th><?php echo JText::_('COM_TRACKS_TABLE_HEADER_TOP5' ); ?></th>
      <?php endif; ?>
      
      <?php if ($this->params->get('rk_show_top10')): ?>
      <th><?php echo JText::_('COM_TRACKS_TABLE_HEADER_TOP10' ); ?></th>
      <?php endif; ?>
      
      <?php if ($this->params->get('rk_show_average')): ?>
      <th><?php echo JText::_('COM_TRACKS_TABLE_HEADER_AVERAGE' ); ?></th>
      <?php endif; ?>
    </tr>
  </thead>
  <tbody>
    <?php
    $i = 0;
    foreach( $this->rankings AS $ranking )
    {
      $link_ind = JRoute::_( TracksHelperRoute::getIndividualRoute($ranking->slug, $this->project->slug) ); 
      $link_team = JRoute::_( TracksHelperRoute::getTeamRoute($ranking->teamslug, $this->project->slug) ); 
      ?>
      <tr class="<?php echo ($i ? 'd1' : 'd0'); ?>">
        <td><?php echo $ranking->rank; ?></td>
        
        <?php if ($this->params->get('shownumber')): ?>
	      <td><?php echo $ranking->number; ?></td>
	      <?php endif; ?>
	      
	      <?php if ($this->params->get('showflag')): ?>
	      <td>
	        <?php if ( $ranking->country_code ): ?>
            <?php echo TracksCountries::getCountryFlag($ranking->country_code); ?>
          <?php endif; ?>
        </td>
	      <?php endif; ?>
	      
        <td>
          <a href="<?php echo $link_ind; ?>"
  				   title="<?php echo JText::_('COM_TRACKS_Details' ); ?>">
          <?php echo $ranking->first_name . ' ' . $ranking->last_name; ?>
          </a>
        </td>
        <?php  if ($this->params->get('showteams')): ?>
        <td>
        	<?php if ($ranking->team_id): ?>
          <a href="<?php echo $link_team; ?>"
             title="<?php echo JText::_('COM_TRACKS_Details' ); ?>"> 
            <?php echo $ranking->team_name; ?></a>
          <?php endif; ?>
        </td>
        <?php endif; ?>
        <td><?php echo $ranking->points; ?></td>
        <td><?php echo $ranking->wins; ?></td>
        <td><?php echo $ranking->best_rank; ?></td>
      
	      <?php if ($this->params->get('rk_show_top3')): ?>
	      <td><?php echo TracksHelperTools::getTop3($ranking); ?></td>
	      <?php endif; ?>
	      
	      <?php if ($this->params->get('rk_show_top5')): ?>
	      <td><?php echo TracksHelperTools::getTop5($ranking); ?></td>
	      <?php endif; ?>
	      
	      <?php if ($this->params->get('rk_show_top10')): ?>
	      <td><?php echo TracksHelperTools::getTop10($ranking); ?></td>
	      <?php endif; ?>

	      <?php if ($this->params->get('rk_show_average')): ?>
	      <td><?php echo TracksHelperTools::getAverageFinish($ranking); ?></td>
	      <?php endif; ?>
	      
      </tr>
      <?php    
      $i = 1 - $i; 
    }
    ?>
  </tbody>
</table>
<p class="copyright">
  <?php echo HTMLtracks::footer( ); ?>
</p>
</div>
  
