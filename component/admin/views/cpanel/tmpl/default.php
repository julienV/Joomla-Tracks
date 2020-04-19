<?php
/**
 * @package     Tracks
 * @subpackage  Admin
 * @copyright   Tracks (C) 2008-2015 Julien Vonthron. All rights reserved.
 * @license     GNU General Public License version 2 or later
 */
defined('_JEXEC') or die('Restricted access');

$items = TrackslibHelperAdmin::getAdminMenuItems();

$i = 1;
?>
<div class="row-fluid">
	<div class="container" id="tracks-cpanel">
		<div class="span9 tracksDashboardMainIcons">
			<?php foreach ($items as $group): ?>
				<div class="accordion-group navbar-inverse" id="tracks-cpanel-<?php echo $i;?>">
					<div class="accordion-heading navbar-inner">
						<a class="accordion-toggle" data-toggle="collapse" data-parent="#tracks-cpanel-<?php echo $i;?>" href="#collapse-cpanel-<?php echo $i;?>">
							<h4>
								<i class="<?php echo $group['icon'];?>"></i>
								<?php echo $group['text'];?>
							</h4>
						</a>
					</div>
					<div class="accordion-body collapse in" id="collapse-cpanel-<?php echo $i;?>">
						<div class="row-fluid">
							<?php foreach ($group['items'] as $item): ?>
								<?php if ($this->user->authorise($item['access'], 'com_tracks')) :?>
									<div class="span2">
										<a href="<?= JRoute::_($item['link']); ?>" class="tracks-cpanel-icon-link">
											<div class="tracks-cpanel-icon-wrapper">
												<div class="tracks-cpanel-icon">
													<i class="<?= $item['icon'] ?> icon-5x"></i>
												</div>
												<?php if (!empty($item['stats'])): ?>
													<span class="badge tracks-cpanel-count"><?= $item['stats']['total'] ?></span>
												<?php endif; ?>
											</div>
											<div class="tracks-cpanel-text">
												<?=  $item['text']; ?>
											</div>
										</a>
									</div>
								<?php endif;?>
							<?php endforeach;?>
						</div>
					</div>
				</div>
				<?php $i++;?>
			<?php endforeach; ?>
		</div>
		<div class="span3 tracksDashboardSideIcons">
			<div class="well">
				<div>
					<strong class="row-title">
						<?php echo JText::_('COM_TRACKS_VERSION'); ?>
					</strong>
					<span class="badge badge-success pull-right" title="<?php echo JText::_('COM_TRACKS_VERSION'); ?>">
						<?php echo TrackslibHelperAdmin::getVersion(); ?>
					</span>
				</div>
			</div>
		</div>
	</div>
</div>
