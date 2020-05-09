<?php
/**
 * @package     JoomlaTracks
 * @subpackage  Plugins.site
 * @copyright   Copyright (C) 2008-2015 Julien Vonthron. All rights reserved.
 * @license     GNU General Public License version 2 or later
 */

defined('_JEXEC') or die('Restricted access');

// Import library dependencies
jimport('joomla.plugin.plugin');

$tracksLoader = JPATH_LIBRARIES . '/tracks/bootstrap.php';

if (!file_exists($tracksLoader))
{
	throw new Exception(JText::_('COM_TRACKS_LIB_INIT_FAILED'), 404);
}

include_once $tracksLoader;

// Bootstraps Tracks
TrackslibBootstrap::bootstrap();

/**
 * Class PlgUserTracks_autoregister
 *
 * @package     JoomlaTracks
 * @subpackage  Plugins.site
 * @since       1.0
 */
class PlgUserTracks_Autoregister extends JPlugin
{
	/**
	 * Constructor
	 *
	 * @param   object  &$subject  The object to observe
	 * @param   array   $config    An optional associative array of configuration settings.
	 *                             Recognized key values include 'name', 'group', 'params', 'language'
	 *                             (this list is not meant to be comprehensive).
	 */
	public function __construct(&$subject, $config = array())
	{
		parent::__construct($subject, $config);

		// Load language file for frontend
		$this->loadLanguage();
	}

	/**
	 * Creates a tracks individual for the user
	 * Method is called after user data is stored in the database
	 *
	 * @param   array    $user     holds the new user data
	 * @param   boolean  $isnew    true if a new user is stored
	 * @param   boolean  $success  true if user was succesfully stored in the database
	 * @param   string   $msg      message
	 *
	 * @return void
	 */
	public function onUserAfterSave($user, $isnew, $success, $msg)
	{
		$app = JFactory::getApplication();

		// Require tracks individual table
		require_once JPATH_ADMINISTRATOR . '/components/com_tracks/tables/individual.php';

		if ($isnew)
		{
			$table = RTable::getAdminInstance('Individual', array(), 'com_tracks');

			if ($this->params->get('map_name', 0) == 0)
			{
				$split = strpos($user['name'], ' ');

				if ($split && $this->params->get('split_name', 1))
				{
					$table->first_name = substr($user['name'], 0, $split);
					$table->last_name  = substr($user['name'], $split + 1);
				}
				else
				{
					$table->last_name = $user['name'];
				}
			}
			else
			{
				$table->last_name = $user['username'];
			}

			switch ($this->params->get('map_nickname', 2))
			{
				case 0:
					break;

				case 1:
					$table->nickname = $user['name'];
					break;

				case 2:
					$table->nickname = $user['username'];
					break;
			}

			$table->user_id   = $user['id'];

			if ($table->check() && $table->store())
			{
			}
			else
			{
				Jerror::raiseWarning(0, JText::_('Error_while_creating_tracks_individual'));
			}
		}
	}
}
