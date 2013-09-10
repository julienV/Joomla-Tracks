<?php
/**
 * @version    2.0
 * @package    JoomlaTracks
 * @copyright  Copyright (C) 2008 Julien Vonthron. All rights reserved.
 * @license    GNU/GPL, see LICENSE.php
 *             Joomla Tracks is free software. This version may have been modified pursuant
 *             to the GNU General Public License, and as distributed it includes or
 *             is derivative of works licensed under the GNU General Public License or
 *             other free or open source software licenses.
 *             See COPYRIGHT.php for copyright notices and details.
 */


// no direct access
defined('_JEXEC') or die('Restricted access');

class com_tracksInstallerScript
{
	private $installed_mods = array();

	private $installed_plugs = array();

	/** @var array Obsolete files and folders to remove */
	private $removeFiles = array(
		'files' => array(
			'administrator/components/com_tracks/controllers/competition.php',
			'administrator/components/com_tracks/controllers/projectindividuals.php',
			'administrator/components/com_tracks/controllers/round.php',
			'administrator/components/com_tracks/controllers/season.php',
			'administrator/components/com_tracks/controllers/subroundtype.php',
			'administrator/components/com_tracks/controllers/team.php',

			'administrator/components/com_tracks/models/competition.php',
			'administrator/components/com_tracks/models/competitions.php',
			'administrator/components/com_tracks/models/individual.php',
			'administrator/components/com_tracks/models/project.php',
			'administrator/components/com_tracks/models/projectindividual.php',
			'administrator/components/com_tracks/models/projectround.php',
			'administrator/components/com_tracks/models/round.php',
			'administrator/components/com_tracks/models/season.php',
			'administrator/components/com_tracks/models/seasons.php',
			'administrator/components/com_tracks/models/subround.php',
			'administrator/components/com_tracks/models/subroundresult.php',
			'administrator/components/com_tracks/models/subroundtype.php',
			'administrator/components/com_tracks/models/team.php',

			'administrator/components/com_tracks/tables/projectroundresult.php',

			'administrator/components/com_tracks/views/competition/view.html.php',
			'administrator/components/com_tracks/views/competitions/view.html.php',
			'administrator/components/com_tracks/views/competitions/tmpl/default.php',

			'administrator/components/com_tracks/views/individual/view.html.php',
			'administrator/components/com_tracks/views/individual/tmpl/form.php',
			'administrator/components/com_tracks/views/individuals/view.html.php',
			'administrator/components/com_tracks/views/individuals/tmpl/default.php',

			'administrator/components/com_tracks/views/projectindividual/view.html.php',
			'administrator/components/com_tracks/views/projectindividuals/tmpl/assign.php',

			'administrator/components/com_tracks/views/projectround/view.html.php',

			'administrator/components/com_tracks/views/projects/view.html.php',
			'administrator/components/com_tracks/views/projects/tmpl/default.php',

			'administrator/components/com_tracks/views/round/view.html.php',
			'administrator/components/com_tracks/views/rounds/view.html.php',
			'administrator/components/com_tracks/views/rounds/tmpl/default.php',

			'administrator/components/com_tracks/views/season/view.html.php',
			'administrator/components/com_tracks/views/seasons/view.html.php',
			'administrator/components/com_tracks/views/seasons/tmpl/default.php',

			'administrator/components/com_tracks/views/subround/view.html.php',

			'administrator/components/com_tracks/views/subroundresult/view.html.php',

			'administrator/components/com_tracks/views/subroundtype/view.html.php',

			'administrator/components/com_tracks/views/subroundtypes/view.html.php',
			'administrator/components/com_tracks/views/subroundtypes/tmpl/default.php',

			'administrator/components/com_tracks/views/team/view.html.php',
			'administrator/components/com_tracks/views/teams/view.html.php',
			'administrator/components/com_tracks/views/teams/tmpl/default.php',

			'components/com_tracks/views/individual/tmpl/form.php',
			'components/com_tracks/views/individual/tmpl/form.xml',
		),
		'folders' => array()
	);

	/**
	 * Method to run after an install/update/uninstall method
	 *
	 * @return void
	 */
	public function postflight($type, $parent)
	{
		// Remove obsolete files and folders
		$this->_removeObsoleteFilesAndFolders($this->removeFiles);

		$this->installModsPlugs($parent);
		if (count($this->installed_plugs))
		{
			echo '<div>
                          <table class="adminlist" cellspacing="1">
                            <thead>
                                <tr>
                                    <th>' . JText::_('Plugin') . '</th>
                                    <th>' . JText::_('Group') . '</th>
                                    <th>' . JText::_('Status') . '</th>
                                </tr>
                            </thead>
                            <tfoot>
                                <tr>
                                    <td colspan="3">&nbsp;</td>
                                </tr>
                            </tfoot>
                            <tbody>';
			foreach ($this->installed_plugs as $plugin) :
				$pstatus = ($plugin['upgrade']) ? JHtml::_('image', 'admin/tick.png', '', NULL, true) : JHtml::_('image', 'admin/publish_x.png', '', NULL, true);
				echo '<tr>
                                            <td>' . $plugin['plugin'] . '</td>
                                            <td>' . $plugin['group'] . '</td>
                                            <td style="text-align: center;">' . $pstatus . '</td>
                                          </tr>';
			endforeach;
			echo '   </tbody>
                         </table>
                         </div>';
		}

		if (count($this->installed_mods))
		{
			echo '<div>
                          <table class="adminlist" cellspacing="1">
                            <thead>
                                <tr>
                                    <th>' . JText::_('Module') . '</th>
                                    <th>' . JText::_('Status') . '</th>
                                </tr>
                            </thead>
                            <tfoot>
                                <tr>
                                    <td colspan="2">&nbsp;</td>
                                </tr>
                            </tfoot>
                            <tbody>';
			foreach ($this->installed_mods as $module) :
				$mstatus = ($module['upgrade']) ? JHtml::_('image', 'admin/tick.png', '', NULL, true) : JHtml::_('image', 'admin/publish_x.png', '', NULL, true);
				echo '<tr>
                                            <td>' . $module['module'] . '</td>
                                            <td style="text-align: center;">' . $mstatus . '</td>
                                          </tr>';
			endforeach;
			echo '   </tbody>
            	</table>
			</div>';
		}
	}

	protected function installModsPlugs($parent)
	{
		$manifest = $parent->get("manifest");
		$parent = $parent->getParent();
		$source = $parent->getPath("source");
		$db = JFactory::getDbo();

		//**********************************************************************
		// DO THIS IF WE DECIDE TO AUTOINSTALL PLUGINS/MODULES
		//**********************************************************************
		// install plugins and modules
		$installer = new JInstaller();

		// Install plugins
		foreach ($manifest->plugins->plugin as $plugin)
		{
			$attributes = $plugin->attributes();
			$plg = $source . '/' . $attributes['folder'] . '/' . $attributes['plugin'];

			$new = ($attributes['new']) ? '&nbsp;(<span class="green">New in v.' . $attributes['new'] . '!</span>)' : '';

			if ($installer->install($plg))
			{
				// Autopublish the plugin
				$query = ' UPDATE #__extensions SET enabled = 1 WHERE folder = ' . $db->Quote($attributes['group']) . ' AND element = ' . $db->Quote($attributes['plugin']);
				$db->setQuery($query);
				$db->query();
				$this->installed_plugs[] = array('plugin' => $attributes['plugin'] . $new, 'group' => $attributes['group'], 'upgrade' => true);
			}
			else
			{
				$this->installed_plugs[] = array('plugin' => $attributes['plugin'], 'group' => $attributes['group'], 'upgrade' => false);
				$this->iperror[] = JText::_('Error installing plugin') . ': ' . $attributes['plugin'];
			}
		}
		return true;

		// Install modules
		foreach ($manifest->modules->module as $module)
		{
			$attributes = $module->attributes();
			$mod = $source . '/' . $attributes['folder'] . '/' . $attributes['module'];
			$new = ($attributes['new']) ? '&nbsp;(<span class="green">New in v.' . $attributes['new'] . '!</span>)' : '';
			if ($installer->install($mod))
			{
				$this->installed_mods[] = array('module' => $attributes['module'] . $new, 'upgrade' => true);
			}
			else
			{
				$this->installed_mods[] = array('module' => $attributes['module'], 'upgrade' => false);
				$this->iperror[] = JText::_('Error installing module') . ': ' . $attributes['module'];
			}
		}
	}

	/**
	 * Removes obsolete files and folders
	 *
	 * @param   array $files  files and folders to be removed
	 */
	private function _removeObsoleteFilesAndFolders($files)
	{
		// Remove files
		JLoader::import('joomla.filesystem.file');
		if (!empty($files['files'])) foreach ($files['files'] as $file)
		{
			$f = JPATH_ROOT . '/' . $file;
			if (!JFile::exists($f)) continue;
			JFile::delete($f);
		}

		// Remove folders
		JLoader::import('joomla.filesystem.file');
		if (!empty($files['folders'])) foreach ($files['folders'] as $folder)
		{
			$f = JPATH_ROOT . '/' . $folder;
			if (!JFolder::exists($f)) continue;
			JFolder::delete($f);
		}
	}
}
