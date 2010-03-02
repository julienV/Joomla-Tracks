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
<h1><?php echo $this->project->name . ' ' . JText::_( 'Team Rankings' ); ?></h1>

<table class="raceResults" cellspacing="0" cellpadding="0" summary="">
  <tbody>
    <tr>
      <th><?php echo JText::_( 'POSITION SHORT' ); ?></th>
      <?php if ($this->projectparams->get('showflag')): ?>
      <th><?php echo JText::_( 'COUNTRY SHORT' ); ?></th>
      <?php endif; ?>
      <th><?php echo JText::_( 'Team' ); ?></th>
      <th><?php echo JText::_( 'Points' ); ?></th>
      <th><?php echo JText::_( 'Wins' ); ?></th>
      <th><?php echo JText::_( 'Best rank' ); ?></th>
    </tr>
    <?php
    $rank = 1;
    $k = 0; 
    foreach( $this->rankings AS $ranking )
    {     
      $link_team = JRoute::_( 'index.php?view=team&t=' . $ranking->team_id ); 
      ?>
      <tr class="<?php echo ($k++ % 2 ? 'd1' : 'd0'); ?>">
        <td><?php echo $rank++; ?></td>
        <?php if ($this->projectparams->get('showflag')): ?>
        <td>
          <?php if ( $ranking->country_code ): ?>
          <img src="<?php echo TracksCountries::getIso3Flag($ranking->country_code); ?>"
             title="<?php echo $ranking->country_code; ?>"
             alt="<?php echo $ranking->country_code; ?>"/>
          <?php endif ?>
        </td>
        <?php endif; ?>
        <td>
            <a href="<?php echo $link_team; ?>"
               title="<?php echo JText::_( 'Details' ); ?>"> 
            <?php echo $ranking->team_name; ?>&nbsp;
            <?php //echo JHTML::image('media/com_tracks/images/teams/'.$ranking->team_logo, $ranking->team_name, array('title' => $ranking->team_name, 'class' => 'ranking-logo')); ?>
            </a>
        </td>
        <td><?php echo $ranking->points; ?>&nbsp;</td>
        <td><?php echo $ranking->wins; ?>&nbsp;</td>
        <td><?php echo $ranking->best_rank; ?>&nbsp;</td>
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
  
