<?php
/**
* @version    $Id: tracks.php 109 2008-05-24 11:05:07Z julienv $ 
* @package    JoomlaTracks
* @subpackage RankingModule
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
<div class="mod_tracksranking">

<table cellspacing="0" cellpadding="0" summary="">
	<thead>
    <tr>
      <th><?php echo JText::_( 'Pos' ); ?></th>
      <th><?php echo JText::_( 'Individual' ); ?></th>
      <?php if ($showteams) { ?><th><?php echo JText::_( 'Team' ); ?></th><?php } ?>
      <th><?php echo JText::_( 'Points' ); ?></th>
    </tr>
	</thead>
  <tbody>
    <?php
    $rank = 1;
    $count = 0;
    foreach( $list AS $ranking )
    {
      $link_ind = JRoute::_( 'index.php?option=com_tracks&view=individual&i=' . $ranking->id ); 
      $link_team = JRoute::_( 'index.php?option=com_tracks&view=team&t=' . $ranking->team_id ); 
      ?>
      <tr>
        <td><?php echo $rank++; ?></td>
        <td>
          <a href="<?php echo $link_ind; ?>"
             title="<?php echo JText::_( 'Details' ); ?>">
          <?php echo $ranking->last_name; ?>
          </a>
        </td>
        <?php if ($showteams) { ?>
        <td>
          <a href="<?php echo $link_team; ?>"
             title="<?php echo JText::_( 'Details' ); ?>"> 
            <?php echo $ranking->team_acronym; ?>&nbsp;
          </a>
        </td>
        <?php } ?>
        <td><?php echo $ranking->points; ?>&nbsp;</td>
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
$link = JRoute::_( 'index.php?option=com_tracks&view=ranking&p=' . $project->id );
?>
<a id="fulltablelink" href="<?php echo $link; ?>"
             title="<?php echo JText::_( 'View full table' ); ?>"> 
            <?php echo JText::_( 'View full table' ); ?>&nbsp;
</a>
</div>
