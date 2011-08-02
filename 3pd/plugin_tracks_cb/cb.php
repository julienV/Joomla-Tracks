<?php
/**
* @version    0.2 $Id$ 
* @package    JoomlaTracks
* @copyright  Copyright (C) 2008-2009 Julien Vonthron. All rights reserved.
* @license    GNU/GPL, see LICENSE.php
* Joomla Tracks is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );
 
// Import library dependencies
jimport('joomla.event.plugin');

JPlugin::loadLanguage( 'plg_tracks_cb', JPATH_ADMINISTRATOR );

class plgTracksCb extends JPlugin {
 
	public function plgTracksCb(&$subject, $config = array()) 
	{
		parent::__construct($subject, $config);
	}

	public function getProfileLink($user_id, &$object, &$attribs = '')
	{
		if (!JFactory::getUser($user_id)) {
			return true;
		}
		
    $link = JRoute::_( 'index.php?option=com_comprofiler&&task=userProfile&user='. $user_id );
		$object->text = JHTML::link($link, JText::_('VIEW_USER_PROFILE' ), $attribs);
		return true;
	}
}
?>
