<?php
/**
 * @package     Tracks.Plugin
 * @subpackage  imports
 *
 * @copyright   Copyright (C) 2020 Julien Vonthron. All rights reserved.
 * @license     GNU General Public License version 3 or later, see LICENSE.
 */

defined('JPATH_BASE') or die;
?>
<div class="formula1-importer">
	<h3 class="formula1-importer__title">
		Formula 1 importer
	</h3>
	<div class="formula1-importer__instructions">
		<p>To import data from ergast.com, use the following url:<br>
			index.php?option=com_ajax&group=tracks_import&plugin=F1ImportSeason&project_id=#project id#&season=#season year#&format=html</p>

		<ul>
			<li>#project_id#: the id of a project in tracks</li>
			<li>#season year#: a year of formula 1, for example '2019'</li>
		</ul>

		<p>Of course, you can use it in a cron job to get regular syncing</p>
	</div>

</div>