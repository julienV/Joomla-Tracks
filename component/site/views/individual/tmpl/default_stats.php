<?php
/**
 * @version        $Id: default.php 101 2008-05-22 08:32:12Z julienv $
 * @package        JoomlaTracks
 * @copyright      Copyright (C) 2008 Julien Vonthron. All rights reserved.
 * @license        GNU/GPL, see LICENSE.php
 * Joomla Tracks is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * See COPYRIGHT.php for copyright notices and details.
 */

// no direct access
use Joomla\CMS\Language\Text;

defined('_JEXEC') or die('Restricted access');

$individual = TrackslibEntityIndividual::load($this->data->id);
$stats      = $individual->getStats();
$params     = \Tracks\Helper\Config::getConfig();
?>

<div class="tracks-section">
	<h3 class="tracks-section__title"><?php echo JText::_('COM_TRACKS_INDIVIDUAL_GLOBAL_STATS'); ?></h3>
	<table class="tracks-individual-stats tracks-table">
		<thead>
		<tr>
			<?php if ($params->get('show_stat_starts', 1)): ?>
				<th class="tracks-individual-stats__stat tracks-individual-stats__stat--starts">
					<?= Text::_('COM_TRACKS_INDIVIDUAL_GLOBAL_STATS_STARTS') ?>
				</th>
			<?php endif; ?>
			<?php if ($params->get('show_stat_points', 1)): ?>
				<th class="tracks-individual-stats__stat tracks-individual-stats__stat--points">
					<?= Text::_('COM_TRACKS_INDIVIDUAL_GLOBAL_STATS_POINTS') ?>
				</th>
			<?php endif; ?>
			<?php if ($params->get('show_stat_wins', 1)): ?>
				<th class="tracks-individual-stats__stat tracks-individual-stats__stat--wins">
					<?= Text::_('COM_TRACKS_INDIVIDUAL_GLOBAL_STATS_WINS') ?>
				</th>
			<?php endif; ?>
			<?php if ($params->get('show_stat_podiums', 1)): ?>
				<th class="tracks-individual-stats__stat tracks-individual-stats__stat--podiums">
					<?= Text::_('COM_TRACKS_INDIVIDUAL_GLOBAL_STATS_PODIUMS') ?>
				</th>
			<?php endif; ?>
		</tr>
		</thead>
		<tbody>
			<tr>
				<?php if ($params->get('show_stat_starts', 1)): ?>
					<td class="tracks-individual-stats__value tracks-individual-stats__value--starts">
						<?= $stats['starts'] ?>
					</td>
				<?php endif; ?>
				<?php if ($params->get('show_stat_points', 1)): ?>
					<td class="tracks-individual-stats__value tracks-individual-stats__value--points">
						<?= $stats['points'] ?>
					</td>
				<?php endif; ?>
				<?php if ($params->get('show_stat_wins', 1)): ?>
					<td class="tracks-individual-stats__value tracks-individual-stats__value--wins">
						<?= $stats['wins'] ?>
					</td>
				<?php endif; ?>
				<?php if ($params->get('show_stat_podiums', 1)): ?>
					<td class="tracks-individual-stats__value tracks-individual-stats__value--podiums">
						<?= $stats['podiums'] ?>
					</td>
				<?php endif; ?>
			</tr>
		</tbody>
	</table>
</div>