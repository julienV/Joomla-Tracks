<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
 
// import Joomla view library
jimport('joomla.application.component.view');
 
/**
 * Sponsor View
 */
class TracksViewSponsor extends JView
{
	/**
	 * display method of Hello view
	 * @return void
	 */
	public function display($tpl = null) 
	{
		// get the Data
		$form = $this->get('Form');
		$item = $this->get('Item');
 
		// Check for errors.
		if (count($errors = $this->get('Errors'))) 
		{
			JError::raiseError(500, implode('<br />', $errors));
			return false;
		}
		// Assign the Data
		$this->form = $form;
		$this->item = $item;
 
		// Set the toolbar
		$this->addToolBar();
 
		// Set the document
		$this->setDocument();

		// Display the template
		parent::display($tpl);
	}
 
	/**
	 * Setting the toolbar
	 */
	protected function addToolBar() 
	{
		JRequest::setVar('hidemainmenu', true);
		$isNew = ($this->item->id == 0);
		JToolBarHelper::title($isNew ? JText::_('COM_TRACKS_MANAGER_SPONSOR_NEW')
		                             : JText::_('COM_TRACKS_MANAGER_SPONSOR_EDIT'));
		JToolBarHelper::apply('sponsor.apply');
		JToolBarHelper::save('sponsor.save');
		JToolBarHelper::cancel('sponsor.cancel', $isNew ? 'JTOOLBAR_CANCEL'
		                                                   : 'JTOOLBAR_CLOSE');
	}
	
	/**
	* Method to set up the document properties
	*
	* @return void
	*/
	protected function setDocument()
	{
		JHTML::_('behavior.framework');
		$isNew = ($this->item->id < 1);
		$document = JFactory::getDocument();
		$document->setTitle($isNew ? JText::_('COM_TRACKS_MANAGER_SPONSOR_NEW')
		                           : JText::_('COM_TRACKS_MANAGER_SPONSOR_EDIT'));
		$document->addScript(JURI::root() . "/administrator/components/com_tracks"
				                                  . "/views/sponsor/submitbutton.js");
		JText::script('COM_TRACKS_ERROR_UNACCEPTABLE');
	}
}