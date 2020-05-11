<?php
/**
 * @package    Tracks.Site
 * @copyright  Tracks (C) 2008-2015 Julien Vonthron. All rights reserved.
 * @license    GNU General Public License version 2 or later
 */

defined('_JEXEC') or die('Restricted access'); ?>

<div id="tracks">
	<h2 class="tracks-title"><?php echo JText::_('COM_TRACKS_project_list') ?></h2>

	<table class="raceResults">
		<thead>
		<tr>
			<th><?php echo JText::_('COM_TRACKS_Name') ?></th>
			<th><?php echo JText::_('COM_TRACKS_Competition') ?></th>
			<th><?php echo JText::_('COM_TRACKS_Season') ?></th>
			<th><?php echo JText::_('') ?></th>
			<th><?php echo JText::_('') ?></th>
		</tr>
		</thead>
		<?php
		if (count($this->projects))
		{
			foreach ($this->projects as $project)
			{
				$link_project = JRoute::_(TrackslibHelperRoute::getProjectRoute($project->slug));
				$link_ranking = JRoute::_(TrackslibHelperRoute::getRankingRoute($project->slug));
				$link_teams_ranking = JRoute::_(TrackslibHelperRoute::getTeamRankingRoute($project->slug));
				?>
				<tr>
					<td>
						<a href="<?php echo $link_project; ?>" title="<?php echo JText::_('COM_TRACKS_Display'); ?>">
							<?php echo $project->name; ?>
						</a>
					</td>
					<td><?php echo $project->competition_name; ?></td>
					<td><?php echo $project->season_name; ?></td>
					<td>
						<a href="<?php echo $link_ranking; ?>" title="<?php echo JText::_('COM_TRACKS_Rankings'); ?>">
							<?php echo JText::_('COM_TRACKS_Rankings'); ?>
						</a>
					</td>
					<td>
						<a href="<?php echo $link_teams_ranking; ?>"
						   title="<?php echo JText::_('COM_TRACKS_Team_Rankings'); ?>">
							<?php echo JText::_('COM_TRACKS_Team_Rankings'); ?>
						</a>
					</td>
				</tr>
			<?php
			}
		}
		?>
	</table>
	<p class="copyright">
		<?php echo TrackslibHelperTools::footer(); ?>
	</p>
</div>
