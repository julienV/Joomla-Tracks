<?php
/**
 * @package     Tracks
 * @subpackage  Admin
 * @copyright   Tracks (C) 2008-2014 Julien Vonthron. All rights reserved.
 * @license     GNU General Public License version 2 or later
 */
defined('_JEXEC') or die('Restricted access');

$return = base64_encode('index.php?option=com_tracks');

$icons = array(
	array('link' => 'index.php?option=com_tracks&view=projects', 'icon' => 'icon-flag-checkered', 'text' => JText::_('COM_TRACKS_PROJECTS'), 'access' => 'core.edit'),
	array('link' => 'index.php?option=com_tracks&view=competitions', 'icon' => 'icon-trophy', 'text' => JText::_('COM_TRACKS_COMPETITIONS'), 'access' => 'core.edit'),
	array('link' => 'index.php?option=com_tracks&view=seasons', 'icon' => 'icon-calendar', 'text' => JText::_('COM_TRACKS_seasons'), 'access' => 'core.edit'),
	array('link' => 'index.php?option=com_tracks&view=teams', 'icon' => 'icon-group', 'text' => JText::_('COM_TRACKS_teams'), 'access' => 'core.edit'),
	array('link' => 'index.php?option=com_tracks&view=individuals', 'icon' => 'icon-user', 'text' => JText::_('COM_TRACKS_individuals'), 'access' => 'core.edit'),
	array('link' => 'index.php?option=com_tracks&view=rounds', 'icon' => 'icon-calendar-empty', 'text' => JText::_('COM_TRACKS_rounds'), 'access' => 'core.edit'),
	array('link' => 'index.php?option=com_tracks&view=subroundtypes', 'icon' => 'icon-ellipsis-vertical', 'text' => JText::_('COM_TRACKS_subroundtypes'), 'access' => 'core.edit'),
	array('link' => 'index.php?option=com_tracks&view=about', 'icon' => 'icon-question', 'text' => JText::_('COM_TRACKS_about'), 'access' => 'core.edit'),
);
?>

<script type="text/javascript">
	Joomla.submitbutton = function (pressbutton) {
		submitbutton(pressbutton);
	};
</script>

<div class="row-fluid">
	<div class="span9 tracksDashboardMainIcons">
		<?php $iconsRow = array_chunk($icons, 6); ?>
		<?php foreach ($iconsRow as $row) : ?>
			<p></p>
			<div class="row-fluid">
				<?php foreach ($row as $icon) : ?>
					<?php if ($this->user->authorise($icon['access'], 'com_tracks')): ?>
						<div class="span2">
							<a href="<?php echo $icon['link']; ?>" class="tracks-cpanel-icon-link">
								<div class="tracks-cpanel-icon-wrapper">
									<div class="tracks-cpanel-icon">
										<i class="<?php echo $icon['icon']; ?> icon-5x"></i>
									</div>
									<?php if (isset($icon['stat'])): ?>
										<span class="badge tracks-cpanel-count"><?php echo $icon['stat'] ?></span>
									<?php endif; ?>
								</div>
								<div class="tracks-cpanel-text">
									<?php echo $icon['text']; ?>
								</div>
							</a>
						</div>
					<?php endif; ?>
				<?php endforeach; ?>
			</div>
		<?php endforeach; ?>
	</div>
</div>
