<?php
/**
 * @package     Tracks
 * @subpackage  Admin
 * @copyright   Tracks (C) 2008-2015 Julien Vonthron. All rights reserved.
 * @license     GNU General Public License version 2 or later
 */

defined('_JEXEC') or die();

jimport('tracks.bootstrap');

$user = JFactory::getUser();
$active = null;
$data = $displayData;

if (isset($data['active']))
{
	$active = $data['active'];
}

// Project switcher
$uri = JUri::getInstance();
$return = base64_encode('index.php' . $uri->toString(array('query')));

RHelperAsset::load('tracksbackend.css', 'com_tracks');

$icons = TrackslibHelperAdmin::getAdminMenuItems(true);
?>
<?php if (isset($data['view']->projectSwitch)): ?>
	<?php $form = $data['view']->projectSwitch; ?>
	<form action="index.php?option=com_tracks&task=project.select&return=<?php echo $return; ?>" method="post" id="project-switch">
		<?php echo $form->getField('currentproject')->input; ?>
	</form>
<?php endif; ?>

<?php if (!empty($icons)): ?>
	<div class="tracks-sidebar" id="tracksSideBarAccordion">
		<?php $index = 0; ?>
		<?php foreach ($icons as $group): ?>
			<div class="accordion-group">
				<div class="accordion-heading">
					<a class="accordion-toggle" data-toggle="collapse" href="#collapse<?php echo $index ?>">
						<i class="<?php echo $group['icon'];?>"></i>
						<?php echo $group['text'];?>
					</a>
				</div>
				<div id="collapse<?php echo $index ?>" class="accordion-body collapse in">
					<?php if (!empty($group['items'])): ?>
						<ul class="nav nav-tabs nav-stacked">
							<?php foreach ($group['items'] as $icon): ?>
								<?php
								$class = '';
								$stat = (isset($icon['count'])) ? $icon['count'] : 0;
								?>
								<?php if ($active === $icon['view']): ?>
									<?php $class = 'active'; ?>
								<?php endif; ?>
								<li class="tracks-sidebar-item <?php echo $class ?>">
									<a href="<?php echo $icon['link'] ?>">
										<i class="<?php echo $icon['icon'] ?>"></i>
										<?php echo $icon['text'] ?>
										<?php if ($stat): ?>
											<span class="badge pull-right"><?php echo $stat; ?></span>
										<?php endif;?>
									</a>
								</li>
							<?php endforeach; ?>
						</ul>
					<?php endif; ?>
				</div>
			</div>
			<?php $index++; ?>
		<?php endforeach; ?>
	</div>
<?php endif; ?>
