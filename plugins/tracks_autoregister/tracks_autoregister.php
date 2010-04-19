<?php
/**
* @version    $Id$ 
* @package    Tracks
* @subpackage Autoregister
* @copyright  Copyright (C) 2010 Julien Vonthron. All rights reserved.
* @license    GNU/GPL, see LICENSE.php
* Tracks is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.txt for copyright notices and details.
*/

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );
 
// Import library dependencies
jimport('joomla.event.plugin');

// load language file for frontend
JPlugin::loadLanguage( 'plg_user_autoregister', JPATH_ADMINISTRATOR );

class plgUserTracks_autoregister extends JPlugin {
 
	function plgUserTracks_autoregister(&$subject, $config = array()) 
	{
		parent::__construct($subject, $config);
	}
	
	/**
	 * Creates a tracks individual for the user
	 * Method is called after user data is stored in the database
	 *
	 * @param array		holds the new user data
	 * @param boolean		true if a new user is stored
	 * @param	boolean		true if user was succesfully stored in the database
	 * @param	string		message
	 */
	function onAfterStoreUser($user, $isnew, $success, $msg)
	{
		$app = &JFactory::getApplication();
				
		// Require tracks individual table
		require_once (JPATH_ADMINISTRATOR.DS.'components'.DS.'com_tracks'.DS.'tables'.DS.'individual.php');
		if ($isnew) {
			$table = &Jtable::getInstance('Individual', 'Table');
			$split = strpos($user['name'], ' ');
			if ($split) {
				$table->first_name = substr($user['name'], 0, $split);
				$table->last_name  = substr($user['name'], $split + 1);				
			}
			else {
				$table->last_name = $user['name'];
			}
			$table->user_id   = $user['id'];
			if ($table->check() && $table->store()) {
				
			}
			else {
				Jerror::raiseWarning(0, JText::_('Error while creating tracks individual'));
			}
		}
	}
}
?>
