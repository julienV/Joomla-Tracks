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
// echo '<pre>';print_r($this->winners); echo '</pre>';exit;
?>
<div id="tracks" class="seasonwinners">

<h1><?php echo $this->title; ?></h1>

<table>
<?php $i = 0; ?>
<?php foreach ((array) $this->winners as $project): ?>
<?php 
if (isset($project->prid)) {
	$tablelink = TracksHelperRoute::getRoundResultRoute($project->prid);
}
else {
	$tablelink = TracksHelperRoute::getRankingRoute($project->slug);
}
?>
<tr class="<?php echo ($i ? 'd1' : 'd0'); ?>">
<td class="projectname"><?php echo JHTML::link($tablelink, $project->name); ?></td>
<td class="winner">
<?php
$winners = array();
foreach ((array) $project->winners as $ind) {
	$link_ind = JRoute::_( TracksHelperRoute::getIndividualRoute($ind->slug, $project->slug) );
	$picture = JHTML::image(HTMLTracks::getIndividualThumb($ind, 100), $ind->first_name . ' ' . $ind->last_name);
	$winners[] = $picture.JHTML::link($link_ind, $ind->first_name.' '.$ind->last_name);	
}
echo implode('<br/>', $winners);
?>
</td>
</tr>
<?php endforeach; ?>
</table>

</div>