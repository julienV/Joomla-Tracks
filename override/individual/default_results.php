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
defined('_JEXEC') or die('Restricted access'); 
// echo '<pre>';print_r($this->rankings); echo '</pre>';exit;
?>

<?php if (count($this->results) ): ?>


<div id="individualresults" style="width:250px;">


<?php foreach ($this->results as $k => $project): ?>

  <?php if ($project[0]->seasonname == "event"):?>

    <h3 class="<?php echo $project[0]->competitionname; ?>"><?php echo $k; ?></h3>

    <?php foreach ($project as $result): ?>

      <UL style="margin-left:10px;line-height:15px;">
      <li style="width:85%;"><?php echo JHTML::link(JRoute::_(TracksHelperRoute::getRoundResultRoute($result->prslug)), $result->roundname); ?></li>
      <li style="font-size:15px;width:10%;text-align:right;<?php echo ($result->rank == 1 ? 'color:red;font-weight:bold;' : ''); ?>"><?php echo ($result->rank == 0 ? '-' : $result->rank); ?></li>
      </UL><br>

    <?php endforeach; ?>

  <?php endif; ?>

<?php endforeach; ?>

</div>



<div id="individualresults" style="width:250px;">



<?php foreach ($this->results as $k => $project): ?>

  <?php if ($project[0]->seasonname != "event"):?>

    <h3 class="<?php echo $project[0]->competitionname; ?>"><!--<?php echo $project[0]->seasonname; ?> --><?php echo $k; ?></h3>

    <?php $n = 0; $currentseason = ""; $currentround = "" ; ?>
    <?php foreach ($project as $result): ?>





      <?php
      if ($currentseason != $project[$n]->seasonname)
        {
//         	echo '<pre>';print_r($project[$n]); echo '</pre>';
//         	echo '<pre>';print_r($this->rankings[$project[$n]->project_id]); echo '</pre>';exit;
          $currentseason = $project[$n]->seasonname ;
          echo '<UL><li class="finalresult" style="padding-left:5px;width:98%;font-size:15px;color:#0A78CB;">' . $currentseason .' - overall '. $this->addOrdinalNumberSuffix($this->rankings[$project[$n]->project_id]->ranking->rank).  '</li></UL>';
        }
      ?>



      <UL style="height:15px;line-height:13px;">
      

      <li style="padding-left:5px;width:14%;">&nbsp;</li>





      <?php
      if ( strstr ($result->subroundname,'final') )
        {
          echo '<li style="width:74%;" class="finalresult">';
          $currentround = $result->roundname ;
          echo ' &nbsp; <A HREF="' . TracksHelperRoute::getRoundResultRoute($result->prslug) . '">' . $result->roundname . '</A>';
          echo'</li>';
          echo '<li style="font-size:15px;width:10%;text-align:right;" class="finalresult">';
          echo ($result->rank == 0 ? '-' : $result->rank);
          echo ' &nbsp; </li>';
        }
        else
        {
          echo '<li style="width:62%;">';
          echo ' &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; ' . $result->subroundname;
          echo'</li>';
          echo '<li style="width:10%;text-align:right;">';
          echo ($result->rank == 0 ? '-' : $result->rank);
          echo '</li>';
        }
      ?>


    </UL>
   












    <?php $n++; ?>

    <?php endforeach; ?>

  <?php endif; ?>

<?php endforeach; ?>





<?php else: ?>
<span id="no-results"><?php echo JText::_('COM_TRACKS_VIEW_INDIVIDUAL_NO_RESULTS'); ?></span>
<?php endif; ?>
</div>




