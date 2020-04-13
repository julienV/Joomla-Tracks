<?php
/**
 * @package     Tracks
 * @subpackage  Admin
 * @copyright   Tracks (C) 2008-2020 Julien Vonthron. All rights reserved.
 * @license     GNU General Public License version 2 or later
 */

defined('_JEXEC') or die('Restricted access');
use Joomla\CMS\Language\Text;
use Joomla\CMS\Plugin\PluginHelper;

$importers = [];

PluginHelper::importPlugin('tracks_import');
$dispatcher = JEventDispatcher::getInstance();
$dispatcher->trigger('onTracksGetImporters', [&$importers]);
?>
<div class="tracks-import">
	<div class="tracks-import__intro">
		<?= Text::_('COM_TRACKS_IMPORT_INTRO'); ?>
	</div>

	<div class="tracks-import__importers">
		<?php if (empty($importers)): ?>
			<div class="alert alert-info">
				<?= Text::_('COM_TRACKS_IMPORT_NO_IMPORTER'); ?>
			</div>
		<?php else: ?>
			<table class="tracks-import__importers__table">
				<thead>
				<tr>
					<th class="tracks-import__importers__table__name">
						<?= Text::_('COM_TRACKS_IMPORT_IMPORTER_NAME'); ?>
					</th>
					<th class="tracks-import__importers__table__description">
						<?= Text::_('COM_TRACKS_IMPORT_IMPORTER_DESCRIPTION'); ?>
					</th>
					<th class="tracks-import__importers__table__developer">
						<?= Text::_('COM_TRACKS_IMPORT_IMPORTER_DEVELOPER'); ?>
					</th>
				</tr>
				</thead>
				<tbody>
					<?php foreach ($importers as $importer): ?>
						<tr>
							<td>
								<a href="index.php?option=com_tracks&view=importer&importer=<?= $importer['id'] ?>">
									<?= $importer['name'] ?>
								</a>
							</td>
							<td><?= $importer['description'] ?></td>
							<td><?= $importer['developer'] ?></td>
						</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
		<?php endif; ?>
	</div>
</div>
