<?php
/**
* @version    $Id: tsql.php 118 2008-05-29 16:22:55Z julienv $ 
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

class JElementTSql extends JElement
{
    /**
     * Element name
     *
     * @access protected
     * @var string
     */
    var $_name = 'TSQL';

    function fetchElement($name, $value, &$node, $control_name)
    {
        $db = JFactory::getDBO();
        $db->setQuery($node->attributes('query'));
        // return JHTML::_('select.genericlist', $db->loadObjectList(), ''.$control_name.'['.$name.']', 'class="inputbox"', 'value', $name, $value, $control_name.$name);
        return JHTML::_('select.genericlist', $db->loadObjectList(), ''.$control_name.'['.$name.']', 'class="inputbox"', $name, 'text', $value, $control_name.$name);
    }
}
