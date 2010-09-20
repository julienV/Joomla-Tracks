<?php
/**
* @version    $Id: default.php 104 2008-05-23 16:17:55Z julienv $ 
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
jimport( 'joomla.filter.output' );

$dispatcher = & JDispatcher::getInstance();
JPluginHelper::importPlugin('content');

?>
<div id="tracks">

<h1><?php echo $this->round->name . ' - ' . $this->round->project_name; ?></h1>

<?php if ($this->params->get('resultview_results_showrounddesc', 1)): ?>
<div class="tracks-round-description">
<?php 
$this->round->text = $this->round->description;
$results = $dispatcher->trigger('onPrepareContent', array (& $this->round, null, 0));
echo $this->round->text;
?>
</div>
<?php endif; ?>

<?php
foreach ($this->results as $subround)
{
	?>
	<h2><?php echo $subround->typename; ?></h2>
	
	<?php if ($this->params->get('resultview_results_showsubrounddesc', 1)): ?>
	<div class="tracks-round-description">
	<?php 
	$subround->text = $subround->description;
	$results = $dispatcher->trigger('onPrepareContent', array (& $subround, null, 0));
	echo $subround->description;
	?>
	</div>
	<?php endif; ?>
	
	<table class="raceResults" cellspacing="0" cellpadding="0" summary="">
	  <tbody>
	    <tr>
	      <th><?php echo JText::_( 'POSITION SHORT' ); ?></th>
	      <?php if ($this->projectparams->get('shownumber')): ?>
	      <th><?php echo JText::_( 'NUMBER SHORT' ); ?></th>
	      <?php endif; ?>
	      <?php if ($this->projectparams->get('showflag')): ?>
	      <th><?php echo JText::_( 'COUNTRY SHORT' ); ?></th>
	      <?php endif; ?>
	      <th><?php echo JText::_( 'Individual' ); ?></th>
	      <?php if ($this->projectparams->get('showteams')): ?>
	      <th><?php echo JText::_( 'Team' ); ?></th>
	      <?php endif; ?>
	      <th><?php echo JText::_( 'Performance' ); ?></th>
	      <?php if (!empty($subround->points_attribution)): ?>
	      <th><?php echo JText::_( 'Points' ); ?></th>
	      <?php endif; ?>
	    </tr>
	    <?php 
	    $k = 0;
	    foreach( $subround->results AS $result )
	    {        
        $ind_slug = $result->individual_id . ':' . JFilterOutput::stringURLSafe( $result->first_name.' '.$result->last_name) ;
	      $link_ind = JRoute::_( TracksHelperRoute::getIndividualRoute($ind_slug) ); 
	      $team_slug = $result->team_id . ':' . JFilterOutput::stringURLSafe( $result->team_name ) ;
	      $link_team = JRoute::_( TracksHelperRoute::getTeamRoute($team_slug) ); 
	      ?>				
      	<tr class="<?php echo ($k++ % 2 ? 'd1' : 'd0'); ?>">
					<td>
						<?php	if ($result->rank) {
							echo $result->rank;
						}
						else {
							echo "-";
						}	?>
					</td>
	        <?php if ($this->projectparams->get('shownumber')): ?>
          <td><?php echo $result->number; ?></td>
	        <?php endif; ?>
	        
	        <?php if ($this->projectparams->get('showflag')): ?>
	        <td>
	          <?php if ( $result->country_code ): ?>
	          <?php echo TracksCountries::getCountryFlag($result->country_code); ?>
	          <?php endif ?>
	        </td>
	        <?php endif; ?>
        
					<td>
					 <a href="<?php echo $link_ind; ?>"
						  title="<?php echo JText::_( 'Details' ); ?>">
					 <?php	echo $result->first_name . ' ' . $result->last_name; ?>
					 </a>
					</td>
					<?php if ($this->projectparams->get('showteams')): ?>
					<td>
					 <a href="<?php echo $link_team; ?>"
						title="<?php echo JText::_( 'Details' ); ?>"> <?php echo $result->team_name; ?>&nbsp;
					 </a>
					</td>
					<?php endif; ?>
					<td><?php echo $result->performance; ?>&nbsp;</td>
					<?php if (!empty($subround->points_attribution)): ?>
					<td><?php echo $result->points + $result->bonus_points; ?>&nbsp;</td>
					<?php endif; ?>
				</tr>
				<?php
	    }
	    ?>
	  </tbody>
	</table>
	<?php
}
?>

<p class="copyright">
  <?php echo HTMLtracks::footer( ); ?>
</p>
</div>