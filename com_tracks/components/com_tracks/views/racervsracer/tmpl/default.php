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

<div>


<?php
$id1=$_GET["id1"];
$id2=$_GET["id2"];
$res = TracksHelperTools::racervs($id1, $id2);
// first individual
$stats1 = $res[$id1];
$stats2 = $res[$id2];


function percent($num_amount, $num_total) {
$count1 = ($num_total > 0 ? $num_amount / $num_total : 0 );
$count2 = $count1 * 100;
$count = number_format($count2, 0);
return $count;
}

?>

Updated daily. <A HREF="http://xjetski.com/2012-02-09-23-57-47/contact?view=alfcontact">Suggest</A> next rider VS rider.<p><br><p>


<div><UL class="transparent" style="height:30px;line-height:30px;margin:0;color:white;padding:5px 10px 5px 10px; letter-spacing:1px;font-size: 19px;font-family:MicrogrammaDEEBolExtRegular , Arial, Helvetica, sans-serif; text-transform: uppercase; border-top:1px solid #4cabfb;border-left:1px solid #4cabfb;border-bottom:1px solid #0A78CB;border-right:1px solid #0A78CB;"><li style="list-style:none;float:left;width:400px;"><?php echo $stats1->name; ?></li><li style="text-align:center;list-style:none;float:left;width:90px;">VS</li><li style="list-style:none;float:left;text-align:right;width:400px;"><?php echo $stats2->name; ?></li></UL></div>

<div style="float:left;"><IMG width=250 SRC="<?php echo $stats1->picture; ?>"></div>


<div style="float:left;width:420px;" class="transparent">


  <div style="width:90px;margin-right:10px;" class="textright racervsracer <?php echo ($stats1->starts > $stats2->starts ? "racervsracerwinner" : ""); ?>"><?php echo $stats1->starts; ?></div>
  <div style="width:220px;" class="transparent racervsracer"><h2 style="border:0;padding:0;">STARTS</h2></div>
  <div style="width:90px;margin-left:10px;" class="textleft racervsracer <?php echo ($stats2->starts > $stats1->starts ? "racervsracerwinner" : ""); ?>"><?php echo $stats2->starts; ?></div>

  <div style="width:90px;margin-right:10px;" class="textright racervsracer <?php echo ($stats1->wins > $stats2->wins ? "racervsracerwinner" : ""); ?>"><?php echo $stats1->wins; ?></div>
  <div style="width:220px;" class="transparent racervsracer"><h2 style="border:0;padding:0;">WINS</h2></div>
  <div style="width:90px;margin-left:10px;" class="textleft racervsracer <?php echo ($stats2->wins > $stats1->wins ? "racervsracerwinner" : ""); ?>"><?php echo $stats2->wins; ?></div>

  <div style="width:90px;margin-right:10px;" class="textright racervsracer <?php echo (percent($stats1->wins,$stats1->starts) > percent($stats2->wins,$stats2->starts) ? "racervsracerwinner" : ""); ?>"><?php echo percent($stats1->wins,$stats1->starts) ?></div>
  <div style="width:220px;" class="transparent racervsracer"><h2 style="border:0;padding:0;">% WINS</h2></div>
  <div style="width:90px;margin-left:10px;" class="textleft racervsracer <?php echo (percent($stats2->wins,$stats2->starts) > percent($stats1->wins,$stats1->starts) ? "racervsracerwinner" : ""); ?>"><?php echo percent($stats2->wins,$stats2->starts) ?></div>

  <div style="width:90px;margin-right:10px;" class="textright racervsracer <?php echo ($stats1->podiums > $stats2->podiums ? "racervsracerwinner" : ""); ?>"><?php echo $stats1->podiums; ?></div>
  <div style="width:220px;" class="transparent racervsracer"><h2 style="border:0;padding:0;">PODIUMS</h2></div>
  <div style="width:90px;margin-left:10px;" class="textleft racervsracer <?php echo ($stats2->podiums > $stats1->podiums ? "racervsracerwinner" : ""); ?>"><?php echo $stats2->podiums; ?></div>

  <div style="width:90px;margin-right:10px;" class="textright racervsracer <?php echo (percent($stats1->podiums,$stats1->starts) > percent($stats2->podiums,$stats2->starts) ? "racervsracerwinner" : ""); ?>"><?php echo percent($stats1->podiums,$stats1->starts) ?></div>
  <div style="width:220px;" class="transparent racervsracer"><h2 style="border:0;padding:0;">% PODIUMS</h2></div>
  <div style="width:90px;margin-left:10px;" class="textleft racervsracer <?php echo (percent($stats2->podiums,$stats2->starts) > percent($stats1->podiums,$stats1->starts) ? "racervsracerwinner" : ""); ?>"><?php echo percent($stats2->podiums,$stats2->starts) ?></div>

  <div style="width:90px;margin-right:10px;" class="textright racervsracer <?php echo ($stats1->top5 > $stats2->top5 ? "racervsracerwinner" : ""); ?>"><?php echo $stats1->top5; ?></div>
  <div style="width:220px;" class="transparent racervsracer"><h2 style="border:0;padding:0;">TOP 5</h2></div>
  <div style="width:90px;margin-left:10px;" class="textleft racervsracer <?php echo ($stats2->top5 > $stats1->top5 ? "racervsracerwinner" : ""); ?>"><?php echo $stats2->top5; ?></div>

  <div style="width:90px;margin-right:10px;" class="textright racervsracer <?php echo (percent($stats1->top5,$stats1->starts) > percent($stats2->top5,$stats2->starts) ? "racervsracerwinner" : ""); ?>"><?php echo percent($stats1->top5,$stats1->starts) ?></div>
  <div style="width:220px;" class="transparent racervsracer"><h2 style="border:0;padding:0;">% TOP 5</h2></div>
  <div style="width:90px;margin-left:10px;" class="textleft racervsracer <?php echo (percent($stats2->top5,$stats2->starts) > percent($stats1->top5,$stats1->starts) ? "racervsracerwinner" : ""); ?>"><?php echo percent($stats2->top5,$stats2->starts) ?></div>

  <div style="width:90px;margin-right:10px;" class="textright racervsracer <?php echo ($stats1->top10 > $stats2->top10 ? "racervsracerwinner" : ""); ?>"><?php echo $stats1->top10; ?></div>
  <div style="width:220px;" class="transparent racervsracer"><h2 style="border:0;padding:0;">TOP 10</h2></div>
  <div style="width:90px;margin-left:10px;" class="textleft racervsracer <?php echo ($stats2->top10 > $stats1->top10 ? "racervsracerwinner" : ""); ?>"><?php echo $stats2->top10; ?></div>

  <div style="width:90px;margin-right:10px;" class="textright racervsracer <?php echo (percent($stats1->top10,$stats1->starts) > percent($stats2->top10,$stats2->starts) ? "racervsracerwinner" : ""); ?>"><?php echo percent($stats1->top10,$stats1->starts) ?></div>
  <div style="width:220px;" class="transparent racervsracer"><h2 style="border:0;padding:0;">% TOP 10</h2></div>
  <div style="width:90px;margin-left:10px;" class="textleft racervsracer <?php echo (percent($stats2->top10,$stats2->starts) > percent($stats1->top10,$stats1->starts) ? "racervsracerwinner" : ""); ?>"><?php echo percent($stats2->top10,$stats2->starts) ?></div>

  <div style="width:90px;margin-right:10px;" class="textright racervsracer <?php echo ($stats1->average < $stats2->average ? "racervsracerwinner" : ""); ?>"><?php echo round($stats1->average); ?></div>
  <div style="width:220px;" class="transparent racervsracer"><h2 style="border:0;padding:0;">AVERAGE FINISH</h2></div>
  <div style="width:90px;margin-left:10px;" class="textleft racervsracer <?php echo ($stats2->average < $stats1->average ? "racervsracerwinner" : ""); ?>"><?php echo round($stats2->average); ?></div>

  <div style="width:90px;margin-right:10px;" class="textright racervsracer <?php echo ($stats1->beatsother > $stats2->beatsother ? "racervsracerwinner" : ""); ?>"><?php echo $stats1->beatsother; ?></div>
  <div style="width:220px;" class="transparent racervsracer"><h2 style="border:0;padding:0;">EYE-TO-EYE</h2></div>
  <div style="width:90px;margin-left:10px;" class="textleft racervsracer <?php echo ($stats2->beatsother > $stats1->beatsother ? "racervsracerwinner" : ""); ?>"><?php echo $stats2->beatsother; ?></div>

</div>

<div style="float:right;width:250px;"><IMG width=250 SRC="<?php echo $stats2->picture; ?>"></div>

    
</div>