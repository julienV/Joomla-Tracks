<?php
/**
 * @version    $Id: default.php 101 2008-05-22 08:32:12Z julienv $
 * @package    JoomlaTracks
 * @copyright  Copyright (C) 2008 Julien Vonthron. All rights reserved.
 * @license    GNU/GPL, see LICENSE.php
 * Joomla Tracks is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * See COPYRIGHT.php for copyright notices and details.
 */
// no direct access
use Joomla\CMS\Language\Text;

defined('_JEXEC') or die('Restricted access');

if (!empty($this->data->vehicle_picture) || !empty($this->data->vehicle_description)): ?>

	<div class="tracks-team__vehicle">
		<h3><?php echo Text::_('COM_TRACKS_VIEW_TEAM_VEHICLE'); ?></h3>

		<div class="tracks-team__vehicle__content">
		<?php if ($this->data->vehicle_picture): ?>
			<div class="tracks-team__vehicle__content__picture">
				<?php echo TrackslibHelperImage::modalimage(JPATH_SITE . '/' . $this->data->vehicle_picture, Jtext::_('COM_TRACKS_TEAM_VEHICLE_PICTURE'), 400); ?>
			</div>
		<?php endif; ?>

		<?php if ($this->data->vehicle_description): ?>
			<div class="tracks-team__vehicle__content__desc">
				<?php echo $this->data->vehicle_description; ?>
			</div>
		<?php endif; ?>
		</div>
	</div>
<?php endif;
