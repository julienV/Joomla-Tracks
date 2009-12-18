<?php
/**
* @version    $Id: userlist.php 129 2008-06-06 08:08:43Z julienv $ 
* @package    JoomlaTracks
* @copyright	Copyright (C) 2008 Julien Vonthron. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* Joomla Tracks is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/

// Check to ensure this file is within the rest of the framework
defined('JPATH_BASE') or die();

/**
* Renders a SQL element, workaround for bug in joomla 1.5.3
*
* @author Sam Moffatt <sam.moffatt@joomla.org>
* @author Julien Vonthron <jlv@jlv-solutions.com>
* @package Tracks
* @since 0.1
*/

class JElementUserList extends JElement
{
    /**
     * Element name
     *
     * @access protected
     * @var string
     */
    var $_name = 'USERLIST';

    function fetchElement($name, $value, &$node, $control_name)
    {
        $db = & JFactory::getDBO();
        $list = JHTML::_( 'select.option', '0', JTEXT::_('Select user') );
        /* Better not use super admin accounts for users list */
        $query = ' SELECT id AS value, name AS text FROM #__users WHERE gid < 2 ORDER BY name ASC ';
        $db->setQuery($query);
        $list = array_merge($list, $db->loadObjectList());
        return JHTML::_('select.genericlist', $list, ''.$control_name.'['.$name.']', 'class="inputbox"', 'value', 'text', $value, $control_name.$name);
    }
}
