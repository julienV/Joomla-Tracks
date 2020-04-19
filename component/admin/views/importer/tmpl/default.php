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

$importer = \Joomla\CMS\Factory::getApplication()->input->getString('importer');
$html = '';

PluginHelper::importPlugin('tracks_import');
$dispatcher = JEventDispatcher::getInstance();
$dispatcher->trigger('onTracksGetImporterScreen', [$importer, &$html]);
?>
<div class="tracks-importer">
	<?= $html ?>
</div>
