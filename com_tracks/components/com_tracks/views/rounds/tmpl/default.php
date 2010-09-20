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

<div id="tracks">
<!-- Title -->
<table class="contentpaneopen">
<tbody>
<tr>
<td class="contentheading" width="100%"><?php echo JText::_( 'All rounds' ); ?></td>
</tr>
</tbody>
</table>

<?php if ( $total = count( $this->rows ) ) : ?>
<?php 
$columns = array();
$i = 0;
foreach ($this->rows as $r) 
{
  $column[floor($i/$total*3)][] = $r;
  $i++;
}
?>
    
<table id="namelist">
  <tr>
    <?php $letter = strtoupper(substr($column[0][0]->name, 0, 1)); ?>
    <?php foreach ($column as $k => $c): ?>
    <td>
      <?php if ($k == 0): ?>
      <div class="letter"><?php echo $letter; ?></div>
      <?php endif; ?>
      <?php 
      foreach ($c as $r) 
      {
        $link_round = JRoute::_( TracksHelperRoute::getRoundRoute($r->slug) ); 
        if ($letter != strtoupper(substr($r->name, 0, 1)))
        {
          $letter = strtoupper(substr($r->name, 0, 1)); ?>
          <div class="letter"><?php echo $letter; ?></div>
          <?php 
        } 
        ?>
        <a href="<?php echo $link_round; ?>" title ="<?php echo JText::_( 'Display details' ) ?>">
          <?php echo $r->name; ?>
          </a>
          <br />
          <?php 
      } ?>          
    </td>
    <?php endforeach; ?>
  </tr>
</table>
<?php endif; ?>

<p class="copyright">
  <?php echo HTMLtracks::footer( ); ?>
</p>
</div>