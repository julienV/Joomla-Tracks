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
defined('_JEXEC') or die('Restricted access');



$img_dir = JPATH_SITE.DS.'media'.DS.'com_tracks'.DS.'images'.DS.'individuals'.DS.'small'.DS;

$link_results = JRoute::_( TracksHelperRoute::getRankingRoute($this->project->slug) );
$link_rounds = JRoute::_( TracksHelperRoute::getProjectRoute($this->project->slug) );
?>


<base target="_parent">

<div id='externalresults'>


<h3 class="title"><?php echo $this->project->name; ?></h3>

<UL style="text-align:center;line-height:16px;"><li style="width:100%;background:#f5f5ff">&nbsp;scores by <A HREF="http://xjetski.com"><b>xjetski</b>.com</A></li></UL>

    <?php
    $rank = 1;
    $count = 0;
    foreach( $this->rankings AS $ranking )
    {
      $link_ind = JRoute::_( TracksHelperRoute::getIndividualRoute($ranking->slug, $this->project->slug) );
      $link_team = JRoute::_( TracksHelperRoute::getTeamRoute($ranking->teamslug, $this->project->slug) );
      ?>
<UL><li style="width:12%;text-align:center"><?php echo $rank++; ?></li>
        <li style="width:78%;">



          <a href="<?php echo $link_ind; ?>"
             title="" class="">


          <?php echo $ranking->first_name; ?> <?php echo $ranking->last_name; ?>
          </a>
        </li>
        <li style="width:10%;text-align:right"><?php echo $ranking->points; ?>&nbsp;</li>
      </UL>
      <?php
      if ( ++$count >= 3 ) {
        break;
      }
    }
    
    if ($count == 0) echo "<UL><li>&nbsp;</li></UL><UL><li> &nbsp; &nbsp; No scores yet</li></UL><UL><li>&nbsp;</li></UL>";
    ?>

<UL><li style="width:100%;text-align:center;background:#f5f5ff;"><a href="<?php echo $link_results; ?>" title="see complete point standing">all standings</a> | <A href="<?php echo $link_rounds; ?>" title="see all rounds">all rounds</A></UL>



</div>
