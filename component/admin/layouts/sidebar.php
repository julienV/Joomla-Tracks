<?php
/**
 * @package     Tracks
 * @subpackage  Admin
 * @copyright   Tracks (C) 2008-2015 Julien Vonthron. All rights reserved.
 * @license     GNU General Public License version 2 or later
 */

defined('_JEXEC') or die();

$user = JFactory::getUser();
$active = null;
$data = $displayData;

if (isset($data['active']))
{
	$active = $data['active'];
}

$icons = array(
	array('view' => 'projects', 'icon' => 'icon-flag-checkered', 'text' => JText::_('COM_TRACKS_PROJECTS'), 'access' => 'core.edit'),
	array('view' => 'competitions', 'icon' => 'icon-trophy', 'text' => JText::_('COM_TRACKS_COMPETITIONS'), 'access' => 'core.edit'),
	array('view' => 'seasons', 'icon' => 'icon-calendar', 'text' => JText::_('COM_TRACKS_seasons'), 'access' => 'core.edit'),
	array('view' => 'teams', 'icon' => 'icon-group', 'text' => JText::_('COM_TRACKS_teams'), 'access' => 'core.edit'),
	array('view' => 'individuals', 'icon' => 'icon-user', 'text' => JText::_('COM_TRACKS_individuals'), 'access' => 'core.edit'),
	array('view' => 'rounds', 'icon' => 'icon-calendar-empty', 'text' => JText::_('COM_TRACKS_rounds'), 'access' => 'core.edit'),
	array('view' => 'subroundtypes', 'icon' => 'icon-ellipsis-vertical', 'text' => JText::_('COM_TRACKS_subroundtypes'), 'access' => 'core.edit'),
	array('view' => 'about', 'icon' => 'icon-question', 'text' => JText::_('COM_TRACKS_about'), 'access' => 'core.edit'),
	array('view' => 'configuration', 'icon' => 'icon-gears', 'text' => JText::_('COM_TRACKS_SETTINGS'), 'access' => 'core.manage'),
);

// Configuration link
$uri = JUri::getInstance();
$return = base64_encode('index.php' . $uri->toString(array('query')));
$configurationLink = 'index.php?option=com_redcore&view=config&layout=edit&component=com_tracks&return=' . $return;
?>

<ul class="nav nav-pills nav-stacked redmember-sidebar">
	<?php foreach ($icons as $icon) : ?>
		<?php if ($user->authorise($icon['access'], 'com_redevent')): ?>
			<?php $class = ($active === $icon['view']) ? 'active' : ''; ?>
			<li class="<?php echo $class; ?>">
				<?php if ($icon['view'] == 'configuration') : ?>
					<?php $link = $configurationLink; ?>
				<?php else : ?>
					<?php $link = JRoute::_('index.php?option=com_redevent&view=' . $icon['view']); ?>
				<?php endif; ?>
				<a href="<?php echo $link; ?>">
					<i class="<?php echo $icon['icon']; ?>"></i>
					<?php echo $icon['text']; ?>
				</a>
			</li>
		<?php endif; ?>
	<?php endforeach; ?>
</ul>
