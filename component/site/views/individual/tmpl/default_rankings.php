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
use Joomla\CMS\Router\Route;

defined('_JEXEC') or die('Restricted access');

$individual = TrackslibEntityIndividual::load($this->data->id);
$stats      = $individual->getStats();
$results    = $stats['results'];
$params     = \Tracks\Helper\Config::getConfig();

if (!empty($results) && $params->get('indview_ranking_limit'))
{
	$results = $params->get('indview_ranking_limit') ? array_slice($results, 0, $params->get('indview_ranking_limit'))
		: $results;
}
?>
<div class="tracks-section">
	<h3 class="tracks-section__title"><?php echo JText::_('COM_TRACKS_INDIVIDUAL_RANKING_HISTORY'); ?></h3>
	<table class="tracks-individual-rankings tracks-table">
		<thead>
		<tr>
			<th class="tracks-individual-rankings__head tracks-individual-rankings__head--title">
				<?= Text::_('COM_TRACKS_INDIVIDUAL_RESULTS_COL_TITLE') ?>
			</th>
			<?php if ($params->get('indview_results_showteam', 1)): ?>
				<th class="tracks-individual-rankings__head tracks-individual-rankings__head--team">
					<?= Text::_('COM_TRACKS_INDIVIDUAL_RESULTS_COL_TEAM') ?>
				</th>
			<?php endif; ?>
			<?php if ($params->get('show_individual_stat_rank', 1)): ?>
				<th class="tracks-individual-rankings__head tracks-individual-rankings__head--rank">
					<?= Text::_('COM_TRACKS_INDIVIDUAL_RESULTS_COL_RANK') ?>
				</th>
			<?php endif; ?>
			<?php if ($params->get('show_individual_stat_points', 1)): ?>
				<th class="tracks-individual-rankings__head tracks-individual-rankings__head--points">
					<?= Text::_('COM_TRACKS_INDIVIDUAL_RESULTS_COL_POINTS') ?>
				</th>
			<?php endif; ?>
			<?php if ($params->get('show_individual_stat_wins', 1)): ?>
				<th class="tracks-individual-rankings__head tracks-individual-rankings__head--wins">
					<?= Text::_('COM_TRACKS_INDIVIDUAL_RESULTS_COL_WINS') ?>
				</th>
			<?php endif; ?>
			<?php if ($params->get('show_individual_stat_podiums', 1)): ?>
				<th class="tracks-individual-rankings__head tracks-individual-rankings__head--podiums">
					<?= Text::_('COM_TRACKS_INDIVIDUAL_RESULTS_COL_PODIUMS') ?>
				</th>
			<?php endif; ?>
		</tr>
		</thead>
		<tbody>
		<?php foreach ($results as $result): ?>
			<?php
			/**
			 * @var TrackslibEntityProject $project
			 */
			$project = $result['project'];
			/**
			 * @var RankingtoolInterface $rankingtool
			 */
			$rankingtool = $result['rankingtool'];
			?>
			<tr class="mod_tracks_individual__global__results__row">
				<td class="tracks-individual-rankings__value tracks-individual-rankings__value--title">
					<a href="<?= Route::_(TrackslibHelperRoute::getProjectRoute($project->id)) ?>"><?= $project->name ?></a>
				</td>
				<?php if ($params->get('indview_results_showteam', 1)): ?>
					<td class="tracks-individual-rankings__value tracks-individual-rankings__value--eam">
						<?php if (!empty($result['ranking']->team_id)): ?>
							<a href="<?= Route::_(TrackslibHelperRoute::getTeamRoute($result['ranking']->team_id)) ?>">
								<?= $result['ranking']->team_name ?>
							</a>
						<?php endif; ?>
					</td>
				<?php endif; ?>
				<?php if ($params->get('show_individual_stat_rank', 1)): ?>
					<td class="tracks-individual-rankings__value tracks-individual-rankings__value--rank">
						<?= $result['ranking']->rank ?>
					</td>
				<?php endif; ?>
				<?php if ($params->get('show_individual_stat_points', 1)): ?>
					<td class="tracks-individual-rankings__value tracks-individual-rankings__value--points">
						<?= $result['ranking']->points ?>
					</td>
				<?php endif; ?>
				<?php if ($params->get('show_individual_stat_wins', 1)): ?>
					<td class="tracks-individual-rankings__value tracks-individual-rankings__value--wins">
						<?= TrackslibHelperTools::getCustomTop($result['ranking'], 1); ?>
					</td>
				<?php endif; ?>
				<?php if ($params->get('show_individual_stat_podiums', 1)): ?>
					<td class="tracks-individual-rankings__value tracks-individual-rankings__value--podiums">
						<?= TrackslibHelperTools::getCustomTop($result['ranking'], 3); ?>
					</td>
				<?php endif; ?>
			</tr>
		<?php endforeach; ?>
		</tbody>
	</table>

</div>