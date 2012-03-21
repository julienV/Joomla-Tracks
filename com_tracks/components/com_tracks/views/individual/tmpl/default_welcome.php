<?php

// no direct access
defined('_JEXEC') or die('Restricted access');


$xposure = 0;
$report = '';

if  (strpos($this->data->picture_small, "tnnophoto"))
  {  $xposure++; $report .= '- Add small photo (helps in search)<br>'; }

if  (strpos($this->data->picture, "tnnophoto"))
  {  $xposure++;  $report .= '- Add medium photo (helps in search)<br>'; }

if  (strpos($this->data->picture_background, "tnnophoto"))
  {  $xposure++;  $report .= '- Add background photo<br>'; }

if  ( $this->data->lat == '')
  {  $xposure++;  $report .= '- Add coordinates on the map (helps with local search)<br>'; }

if  ( $this->data->hull == '')
  {  $xposure++;   $report .= '- Select hull manufacturer (helps with sponsors)<br>'; }

if  ( $this->data->motor == '')
  {  $xposure++;   $report .= '- Select motor manufacturer (helps with sponsors)<br>'; }

if  ( $this->data->country_code == '')
  {  $xposure++;   $report .= '- Select your country (helps in search)<br>'; }


	$database = &JFactory::getDbo();
	$individual = $_GET["i"];
	$query = "select user_id as user_id FROM #__tracks_individuals where id = ' $individual ' LIMIT 1";
  $database->setQuery($query );
	$individual = $database->loadResult();
	$sql = "select count(user_id) as rowsempty FROM #__jitter_tb where user_id = ' $individual ' LIMIT 1";
	$database->setQuery($sql);
	$rowsempty = $database->loadResult();

if  ( ! $rowsempty > 0 )
  {  $xposure++;  $report .= '- Make a wave (it shows up on front page)<br>'; }

?>




<?php if ($this->show_edit_link): ?>

<style type="text/css">
a.boxpopup3{position:relative;
	z-index:24;
	color:#046;
	text-decoration:none}
a.boxpopup3:hover{z-index:25; zbackground-color:#FF0}
a.boxpopup3 span{display: none}
a.boxpopup3:hover span{ /*DISPLAYS ONLY ON HOVER*/
	display:block;
	position:absolute;
	top:-30px; left:-250px; width:300px;
	padding:.3em;
	border:0px outset #BBB;
	color:#000; background:#FF9;font-size:11px;text-transform:none;font-family:Arial;letter-spacing:0px;}
</style>
<span style="float:right;"><a class=boxpopup3 href="#"><IMG style="margin:3px;" SRC="http://xjetski.com/images/icons/xposure/<?php echo $xposure; ?>.png">
<span><b>Improve your exposure on Xjetski</b>:<br><?php echo $report; ?><br><b>TIPS:</b><br>- Promote your profile on facebook and elsewhere so visitors propel your profile to show up in most popular option.<br>- Change photos often, so your profile is first in latest updated profiles option.</span></A></span>

<?php endif; ?>