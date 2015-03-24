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
	array('view' => 'eventtypes', 'icon' => 'icon-ellipsis-vertical', 'text' => JText::_('COM_TRACKS_eventtypes'), 'access' => 'core.edit'),
	array('view' => 'about', 'icon' => 'icon-question', 'text' => JText::_('COM_TRACKS_about'), 'access' => 'core.edit'),
	array('view' => 'configuration', 'icon' => 'icon-gears', 'text' => JText::_('COM_TRACKS_SETTINGS'), 'access' => 'core.manage'),
);

// Configuration link
$uri = JUri::getInstance();
$return = base64_encode('index.php' . $uri->toString(array('query')));
$configurationLink = 'index.php?option=com_redcore&view=config&layout=edit&component=com_tracks&return=' . $return;

if ($currentproject = JFactory::getApplication()->getUserState('currentproject'))
{

	$projectIcons = array(
		array('view' => 'projectrounds', 'icon' => 'icon-calendar-empty', 'text' => JText::_('COM_TRACKS_PROJECT_ROUNDS'), 'access' => 'core.edit'),
		array('view' => 'participants', 'icon' => 'icon-user', 'text' => JText::_('COM_TRACKS_PARTICIPANTS'), 'access' => 'core.edit'),
	);
}

?>
<?php if (isset($data['view']->projectSwitch)): ?>
	<?php $form = $data['view']->projectSwitch; ?>
	<form action="index.php?option=com_tracks&task=project.select&return=<?php echo $return; ?>" method="post" id="project-switch">
		<?php echo $form->getField('currentproject')->input; ?>
	</form>
<?php endif; ?>

<?php if ($currentproject): ?>
<ul class="nav nav-pills nav-stacked tracks-sidebar">
	<?php foreach ($projectIcons as $icon) : ?>
		<?php if ($user->authorise($icon['access'], 'com_tracks')): ?>
			<?php $class = ($active === $icon['view']) ? 'active' : ''; ?>
			<li class="<?php echo $class; ?>">
				<?php if ($icon['view'] == 'configuration') : ?>
					<?php $link = $configurationLink; ?>
				<?php else : ?>
					<?php $link = JRoute::_('index.php?option=com_tracks&view=' . $icon['view']); ?>
				<?php endif; ?>
				<a href="<?php echo $link; ?>">
					<i class="<?php echo $icon['icon']; ?>"></i>
					<?php echo $icon['text']; ?>
				</a>
			</li>
		<?php endif; ?>
	<?php endforeach; ?>
</ul>
<?php endif; ?>

<ul class="nav nav-pills nav-stacked tracks-sidebar">
	<?php foreach ($icons as $icon) : ?>
		<?php if ($user->authorise($icon['access'], 'com_tracks')): ?>
			<?php $class = ($active === $icon['view']) ? 'active' : ''; ?>
			<li class="<?php echo $class; ?>">
				<?php if ($icon['view'] == 'configuration') : ?>
					<?php $link = $configurationLink; ?>
				<?php else : ?>
					<?php $link = JRoute::_('index.php?option=com_tracks&view=' . $icon['view']); ?>
				<?php endif; ?>
				<a href="<?php echo $link; ?>">
					<i class="<?php echo $icon['icon']; ?>"></i>
					<?php echo $icon['text']; ?>
				</a>
			</li>
		<?php endif; ?>
	<?php endforeach; ?>
</ul>
