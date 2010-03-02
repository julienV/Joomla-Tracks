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

<h1><?php echo $this->project->name . ' ' . JText::_( 'Rankings' ); ?></h1>

<table class="raceResults" cellspacing="0" cellpadding="0" summary="">
  <thead>
    <tr>
      <th><?php echo JText::_( 'POSITION SHORT' ); ?></th>
      
      <?php if ($this->params->get('shownumber')): ?>
      <th><?php echo JText::_( 'NUMBER SHORT' ); ?></th>
      <?php endif; ?>
      
      <?php if ($this->params->get('showflag')): ?>
      <th><?php echo JText::_( 'COUNTRY SHORT' ); ?></th>
      <?php endif; ?>
      
      <th><?php echo JText::_( 'Individual' ); ?></th>
      
      <?php if ($this->params->get('showteams')): ?>
      <th><?php echo JText::_( 'Team' ); ?></th>
      <?php endif; ?>
      
      <th><?php echo JText::_( 'Points' ); ?></th>
      <th><?php echo JText::_( 'Wins' ); ?></th>
      <th><?php echo JText::_( 'Best rank' ); ?></th>
    </tr>
  </thead>
  <tbody>
    <?php
    $rank = 1; 
    $i = 0;
    foreach( $this->rankings AS $ranking )
    {
      $link_ind = JRoute::_( 'index.php?option=com_tracks&view=individual&i=' . $ranking->slug ); 
      $link_team = JRoute::_( 'index.php?option=com_tracks&view=team&t=' . $ranking->teamslug ); 
      ?>
      <tr class="<?php echo ($i ? 'd1' : 'd0'); ?>">
        <td><?php echo $rank++; ?></td>
        
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
  				   title="<?php echo JText::_( 'Details' ); ?>">
          <?php echo $ranking->first_name . ' ' . $ranking->last_name; ?>
          </a>
        </td>
        <?php  if ($this->params->get('showteams')): ?>
        <td>
          <a href="<?php echo $link_team; ?>"
             title="<?php echo JText::_( 'Details' ); ?>"> 
            <?php echo $ranking->team_name; ?>&nbsp;
          </a>
        </td>
        <?php endif; ?>
        <td><?php echo $ranking->points; ?>&nbsp;</td>
        <td><?php echo $ranking->wins; ?>&nbsp;</td>
        <td><?php echo $ranking->best_rank; ?>&nbsp;</td>
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
  
