<?php
/**
 * @package    Tracks.Site
 * @copyright  Tracks (C) 2008-2020 Julien Vonthron. All rights reserved.
 * @license    GNU General Public License version 2 or later
 */

use Joomla\CMS\Language\Text;
use Joomla\CMS\Router\Route;

defined('JPATH_PLATFORM') or die;

/**
 * @var array  $displayData
 * @var object $project
 */

extract($displayData);

$entity = TrackslibEntityProject::load($project->id);
?>
<div class="tracks-project-nav">
	<a class="tracks-project-nav__link" href="<?= Route::_(TrackslibHelperRoute::getProjectRoute($project->id)) ?>"><?= Text::_('COM_TRACKS_ROUNDS') ?></a>
	| <a  class="tracks-project-nav__link" href="<?= Route::_(TrackslibHelperRoute::getRankingRoute($project->id)) ?>"><?= Text::_('COM_TRACKS_RANKINGS') ?></a>
	<?php if ($entity->getParam('use_teams', 1)): ?>
	| <a  class="tracks-project-nav__link" href="<?= Route::_(TrackslibHelperRoute::getTeamRankingRoute($project->id)) ?>"><?= Text::_('COM_TRACKS_TEAM_RANKINGS') ?></a>
	<?php endif; ?>
</div>
