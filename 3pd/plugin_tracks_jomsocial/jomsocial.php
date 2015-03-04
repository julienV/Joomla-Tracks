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

// include jomsocial core
require_once( JPATH_BASE. '/' .'components'. '/' .'com_community'. '/' .'libraries'. '/' .'core.php');

JPlugin::loadLanguage( 'plg_tracks_jomsocial', JPATH_ADMINISTRATOR );

class plgTracksJomsocial extends JPlugin {

	public function plgTracksJomsocial(&$subject, $config = array())
	{
		parent::__construct($subject, $config);
	}

	public function getProfileLink($user_id, &$object, &$attribs = array())
	{
    $user = CFactory::getUser($user_id);
    //parameters
    $params = $this->params;

		if (!$user || $params->get('view_individual_link', '1') == 0) {
			return true;
		}

    JHTML::_('behavior.tooltip');
    $name = $user->getDisplayName();
    $url = CRoute::_('index.php?option=com_community&view=profile&userid=' . $user_id);

    switch ($params->get('view_individual_link', '1'))
    {
    	case 2:
        $avatar = $user->getThumbAvatar();
        $text = '<img class="hasTip" src="'.$avatar.'" title="'. $name .'"/>';
        break;
    	case 1:
    	default:
    		$text = JText::_('PLG_TRACKS_JOMSOCIAL_VIEW_USER_PROFILE');
    		break;
    }

    $attribs = array_merge($attribs, array('alt' => $user->get('username')));
    $object->text = JHTML::link($url, $text, $attribs);
		return true;
	}
}
?>
