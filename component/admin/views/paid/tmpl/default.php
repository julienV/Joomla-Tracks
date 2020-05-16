<?php
/**
 * @package     Tracks
 * @subpackage  Admin
 * @copyright   Tracks (C) 2008-2020 Julien Vonthron. All rights reserved.
 * @license     GNU General Public License version 2 or later
 */

defined('_JEXEC') or die('Restricted access');

use Joomla\CMS\Language\Text;

$extensions = [
	[
		'name'        => 'Custom fields plugin',
		'description' => 'Integrate Joomla custom fields to Tracks',
		'link'        => 'https://jlv-solutions.com/products/tracks/tracks-custom-fields'
	],
	[
		'name'        => 'Individual and paid modules',
		'description' => 'Display individual and team information anywere on your site',
		'link'        => 'https://jlv-solutions.com/products/tracks/individual-and-team-modules'
	],
	[
		'name'        => 'Upcoming events ticker',
		'description' => 'Display upcoming events, with a big ticker',
		'link'        => 'https://jlv-solutions.com/products/tracks/tracks-upcoming-events-ticker'
	],
];
?>
<div class="tracks-paid">
	<div class="tracks-paid__intro">
		<?= Text::_('COM_TRACKS_PAID_INTRO'); ?>
	</div>

	<div class="tracks-paid__extensions">
		<table class="tracks-paid__extensions__table">
			<thead>
			<tr>
				<th class="tracks-paid__extensions__table__name">
					<?= Text::_('COM_TRACKS_PAID_EXTENSION_NAME'); ?>
				</th>
				<th class="tracks-paid__extensions__table__description">
					<?= Text::_('COM_TRACKS_PAID_EXTENSION_DESCRIPTION'); ?>
				</th>
			</tr>
			</thead>
			<tbody>
			<?php foreach ($extensions as $extension): ?>
				<tr>
					<td>
						<a href="<?= $extension['link'] ?>">
							<?= $extension['name'] ?>
						</a>
					</td>
					<td><?= $extension['description'] ?></td>
				</tr>
			<?php endforeach; ?>
			</tbody>
		</table>
	</div>
</div>
