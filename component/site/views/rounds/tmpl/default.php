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
?>
<div id="tracks" class="tracks-rounds">
	<!-- Title -->
	<h2 class="tracks-title"><?php echo JText::_('COM_TRACKS_All_rounds' ); ?></h2>

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

		<div class="tracks-alpha-list">
			<?php $letter = strtoupper(substr($column[0][0]->name, 0, 1)); ?>
			<?php foreach ($column as $k => $c): ?>
				<div class="tracks-alpha-list__column">
					<?php if ($k == 0): ?>
						<div class="tracks-alpha-list__letter"><?php echo $letter; ?></div>
					<?php endif; ?>

					<?php foreach ($c as $r):
						$link_round = JRoute::_( TrackslibHelperRoute::getRoundRoute($r->slug) );
						if ($letter != strtoupper(substr($r->name, 0, 1))):
							$letter = strtoupper(substr($r->name, 0, 1)); ?>
							<div class="tracks-alpha-list__letter"><?php echo $letter; ?></div>
						<?php
						endif;
						?>
						<a class="tracks-alpha-list__link" href="<?php echo $link_round; ?>" title ="<?php echo JText::_('COM_TRACKS_Display_details' ) ?>">
							<?php echo $r->name; ?>
						</a>
						<br />
					<?php endforeach; ?>
				</div>
			<?php endforeach; ?>
		</div>
	<?php endif; ?>

<p class="copyright">
  <?php echo TrackslibHelperTools::footer( ); ?>
</p>
</div>
