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
<?php 
  /**
   * return formated string for round start date - end date
   *
   * @param object round (must have variables start_date, end_date) 
   * @return string html     
   */        
  function formatRoundStartEnd( $round )
  {
    if ( $round->start_date && $round->start_date != '0000-00-00 00:00:00' )
    {
      if ( $round->end_date && $round->end_date != '0000-00-00 00:00:00' )
      {
        // both dates are defined.
        $format_end = 'j F Y';
        if ( JHTML::date( $round->start_date, 'Ym' ) == JHTML::date( $round->end_date, 'Ym' ) ) {
          // no need to display twice the month and year here
          $format_start = 'j';  
        }
        else {
          $format_start = 'j F Y';        
        }
        return JHTML::date( $round->start_date, $format_start ). ' - ' . JHTML::date( $round->end_date, $format_end );
      }
      else {
        return JHTML::date( $round->start_date, 'j F Y' );
      }
    }
    else {
      return '';
    }
  }
?>

<div id="tracks">

<h1><?php echo $this->project->season_name. ' ' .$this->project->name . ' ' . JText::_('COM_TRACKS_Season_Summary' ); ?></h1>

<table class="raceResults" cellspacing="0" cellpadding="0" summary="">
	<tbody>
		<tr>
			<th><?php echo JText::_('COM_TRACKS_Round' ); ?></th>
			<th><?php echo JText::_('COM_TRACKS_Date' ); ?></th>
			<th><?php echo JText::_('COM_TRACKS_Winner' ); ?></th>
		</tr>
		<?php 
		$k = 0;
		foreach( $this->results AS $result )
		{			
      $link_round = JRoute::_( TracksHelperRoute::getRoundResultRoute($result->slug) );			
      ?>
      <tr class="<?php echo ($k++ % 2 ? 'd1' : 'd0'); ?>">
        <td>
          <a href="<?php echo $link_round; ?>" title ="<?php echo JText::_('COM_TRACKS_Display' ) ?>">
          <?php 
          echo $result->round_name; 
          ?>
          </a>
        </td>
        <td><?php echo formatRoundStartEnd( $result ); ?></td>
        <td>
        	<?php if ($result->winner): ?>
        	<?php foreach ($result->winner as $winner): ?>
        	<div class="winner"><?php echo $winner->first_name . ' ' . $winner->last_name 
					            . ($this->params->get('showteams', 1) && $winner->team_name ? ' ('.$winner->team_name.')' : ''); ?></div>
        	<?php endforeach; ?>
        	<?php endif; ?>
        </td>
      </tr>
      <?php			
		}
		?>
	</tbody>
</table>
<div class="icalbutton">
  <a href="<?php echo JRoute::_(TracksHelperRoute::getProjectRoute($this->project->slug).'&format=ical') ?>" title="<?php echo JText::_('COM_TRACKS_ICAL_EXPORT'); ?>">
    <img src="<?php echo JURI::base().'/components/com_tracks/assets/images/ical.gif'; ?>"  alt="<?php echo JText::_('COM_TRACKS_ICAL_EXPORT'); ?>"/>
  </a>
</div>

<p class="copyright">
  <?php echo HTMLtracks::footer( ); ?>
</p>
</div>