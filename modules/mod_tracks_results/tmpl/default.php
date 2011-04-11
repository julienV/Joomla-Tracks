<?php
/**
* @version    $Id: default.php 139 2008-06-10 14:20:13Z julienv $ 
* @package    JoomlaTracks
* @subpackage ResultsModule
* @copyright  Copyright (C) 2008 Julien Vonthron. All rights reserved.
* @license    GNU/GPL, see LICENSE.php
* Joomla Tracks is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/
// no direct access
defined('_JEXEC') or die('Restricted access'); ?>

<?php
JHTML::_('behavior.tooltip', '.mod-result-tip', array('className' => 'tip-mod-result'));
$document = &JFactory::getDocument(); 
$document->addStyleSheet( JURI::base() . 'modules/mod_tracks_results/mod_tracks_results.css' );
$document->addScript( JURI::base() . 'modules/mod_tracks_results/mod_tracks_results.js' );
?>

<div class="mod_tracksresults">
<div id="roundname"><?php echo $round->name . ' - ' . $list->typename; ?></div>
<table cellspacing="0" cellpadding="0" summary="">
	<thead>
    <tr>
      <th><?php echo JText::_( 'Pos' ); ?></th>
      <th><?php echo JText::_( 'Individual' ); ?></th>
      <?php if ($showteams) { ?><th><?php echo JText::_( 'Team' ); ?></th><?php } ?>
      <?php if ($showpoints) { ?><th><?php echo JText::_( 'Points' ); ?></th><?php } ?>
    </tr>
	</thead>
  <tbody>
    <?php
    $rank = 1;
    $count = 0;
    foreach( $list->results AS $rows )
    {
      $link_ind = JRoute::_( TracksHelperRoute::getIndividualRoute($rows->individual_id) ); 
      $link_team = JRoute::_( TracksHelperRoute::getTeamRoute($rows->team_id) );
      ?>
      <tr>
        <td><?php echo $rows->rank; ?></td>
        <td>
          <a href="<?php echo $link_ind; ?>"
             title="<?php
              echo trim($rows->first_name . ' ' . $rows->last_name).'::'
                  . $rows->performance; ?>" class="mod-result-tip"><?php echo $rows->last_name; ?>
          </a>
        </td> 
        <?php if ($showteams) { ?>
        <td>
          <a href="<?php echo $link_team; ?>"
             title="<?php echo $rows->team_name . '::' . JText::_( 'Click for details' ); ?>" class="mod-result-tip">
            <?php echo $rows->team_acronym; ?>&nbsp;
          </a>
        </td>
        <?php } ?>
        <?php if ($showpoints) { ?>
        <td><?php echo $rows->points + $rows->bonus_points; ?>&nbsp;</td>
        <?php } ?>
      </tr>
      <?php
      if ( ++$count >= $limit ) {
        break;
      }
    }
    ?>
  </tbody>
</table>
<?php 
$link = JRoute::_( TracksHelperRoute::getRoundResultRoute($round->projectround_id) );
?>
<a class="fulltablelink" href="<?php echo $link; ?>"
             title="<?php echo JText::_( 'View full table' ); ?>"> 
            <?php echo JText::_( 'View full table' ); ?>&nbsp;
</a>
</div>
