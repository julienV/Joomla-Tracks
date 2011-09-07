<?php
/**
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
jimport('joomla.plugin.plugin');

class plgTracksCb extends JPlugin {
	
	/**
	* Constructor
	*
	* @access      protected
	* @param       object  $subject The object to observe
	* @param       array   $config  An array that holds the plugin configuration
	* @since       1.5
	*/
	public function __construct(& $subject, $config)
	{
		parent::__construct($subject, $config);
		$this->loadLanguage();
	}

	public function getProfileLink($user_id, &$object, &$attribs = '')
	{
		$user = JFactory::getUser($user_id);
		if (!$user->get('id')) {
			return true;
		}
		
    $link = JRoute::_( 'index.php?option=com_comprofiler&&task=userProfile&user='. $user_id );
		$object->text = JHTML::link($link, JText::_('PLG_TRACKS_CB_VIEW_USER_PROFILE' ), $attribs);
		return true;
	}
}
?>
