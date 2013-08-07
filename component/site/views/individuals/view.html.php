<?php
/**
* @version    $Id: view.html.php 43 2008-02-24 23:47:38Z julienv $
* @package    JoomlaTracks
* @copyright	Copyright (C) 2008 Julien Vonthron. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* Joomla Tracks is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();

jimport( 'joomla.application.component.view');

/**
 * HTML View class for the Tracks component
 *
 * @static
 * @package		Tracks
 * @since 0.1
 */
class TracksViewIndividuals extends JView
{
    function display($tpl = null)
    {
    	$mainframe = JFactory::getApplication();
			$option = JRequest::getCmd('option');
			$params = $mainframe->getParams('com_tracks');

        $model = $this->getModel();
        $rows = $model->getData();

        $document = JFactory::getDocument();
        $document->setTitle( JText::_('COM_TRACKS_All_Individuals' ) );

        $breadcrumbs = $mainframe->getPathWay();
        $breadcrumbs->addItem( JText::_('COM_TRACKS_All_Individuals' ),
            'index.php?option=com_tracks&view=individuals' );

        $this->assignRef( 'rows',    $rows );
        $this->assignRef( 'params',  $params );

        parent::display($tpl);
    }

	/**
	 * returns first letter of word
	 *
	 * @param   string  $word       a word
	 * @param   bool    $uppercase  return uppercase ?
	 *
	 * @return string
	 */
	public function firstLetter($word, $uppercase = true)
	{
		$first = mb_substr($word, 0, 1);

		return $uppercase ? mb_convert_case($first, 1) : $first;
	}
}
