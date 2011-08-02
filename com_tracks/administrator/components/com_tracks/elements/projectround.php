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

class JElementProjectround extends JElement
{
    /**
     * Element name
     *
     * @access protected
     * @var string
     */
    var $_name = 'PROJECTROUND';

    function fetchElement($name, $value, &$node, $control_name)
    {
	    $db     =& JFactory::getDBO();
	    $doc    =& JFactory::getDocument();
	    // $template   = $mainframe->getTemplate();
	    $fieldName  = $control_name.'['.$name.']';
	    
      JTable::addIncludePath(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_tracks'.DS.'tables');
    
	    $projectround =& JTable::getInstance('Projectround','Table');
	    if ($value && $projectround->load($value)) {	      
	      $roundname = $projectround->getName();
	    } else {
	      $roundname = JText::_('COM_TRACKS_Select_a_project_round');
	    }
	
	    $js = "
	    function jSelectProjectround(id, title, object) {
	      document.getElementById('".$name."' + '_id').value = id;
	      document.getElementById('".$name."' + '_name').value = title;
	      document.getElementById('sbox-window').close();
	    }
	    
	    function jRoundReset() {
	      document.getElementById('".$name."' + '_id').value = 0;
        document.getElementById('".$name."' + '_name').value = '".JText::_('COM_TRACKS_Select_a_project_round')."';
      }
	    ";
	    $doc->addScriptDeclaration($js);
	
	    $link = 'index.php?option=com_tracks&amp;controller=projectround&amp;task=element&amp;tmpl=component';
	
	    JHTML::_('behavior.modal', 'a.modal');   	    
      $html = "\n".'<div style="float: left;"><input style="background: #ffffff;" type="text" id="'.$name.'_name" value="'.$roundname.'" disabled="disabled" /></div>';
      $html .= "<div class=\"button2-left\"><div class=\"blank\"><a class=\"modal\" title=\"".JText::_('COM_TRACKS_Select')."\"  href=\"$link\" rel=\"{handler: 'iframe', size: {x: 650, y: 375}}\">".JText::_('COM_TRACKS_Select')."</a></div></div>\n";
      $html .= "<div class=\"button2-left\"><div class=\"blank\"><a title=\"".JText::_('COM_TRACKS_Reset')."\" onClick=\"jRoundReset();return false;\" >".JText::_('COM_TRACKS_Reset')."</a></div></div>\n";
      $html .= "\n".'<input type="hidden" id="'.$name.'_id" name="'.$fieldName.'" value="'.(int)$value.'" />';
	
	    return $html;
  }
}
