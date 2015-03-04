<?php
/**
 * @package     Tracks
 * @subpackage  Admin
 * @copyright   Tracks (C) 2008-2014 Julien Vonthron. All rights reserved.
 * @license     GNU General Public License version 2 or later
 */

defined('_JEXEC') or die();

/**
 * HTML View class for Tracks control panel
 *
 * @package     Tracks
 * @subpackage  Admin
 * @since       3.0
 */
class TracksViewCpanel extends TrackslibViewAdmin
{
	/**
	 * Hide sidebar in cPanel
	 *
	 * @var  boolean
	 */
	protected $displaySidebar = false;

	/**
	 * Display the control panel
	 *
	 * @param   string  $tpl  The template file to use
	 *
	 * @return   string
	 *
	 * @since   2.0
	 */
	public function display($tpl = null)
	{
		$this->user = JFactory::getUser();

		parent::display($tpl);
	}

	/**
	 * Get the view title.
	 *
	 * @return  string  The view title.
	 */
	public function getTitle()
	{
		return JText::_('COM_TRACKS_CPANEL');
	}
}
