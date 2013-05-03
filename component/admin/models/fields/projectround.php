<?php
// No direct access
defined('_JEXEC') or die('Restricted access');

jimport('joomla.form.formfield');

/**
 * Project round form field class
 */
class JFormFieldProjectround extends JFormField
{
	/**
	 * field type
	 * @var string
	 */
	protected $type = 'projectround';
	
	/**
	* Method to get the field input markup
	*/
	protected function getInput()
	{
		// Load modal behavior
		JHtml::_('behavior.modal', 'a.modal');
	
		// Build the script
		$script = array();
		$script[] = '    function jSelectProjectround_'.$this->id.'(id, title, object) {';
		$script[] = '        document.id("'.$this->id.'_id").value = id;';
		$script[] = '        document.id("'.$this->id.'_name").value = title;';
		$script[] = '        SqueezeBox.close();';
		$script[] = '    }';
	
		// Add to document head
		JFactory::getDocument()->addScriptDeclaration(implode("\n", $script));
	
		// Setup variables for display
		$html = array();
		$link = 'index.php?option=com_tracks&amp;controller=projectround&amp;task=element'.
	                  '&amp;tmpl=component&amp;function=jSelectProjectround_'.$this->id;
		
		if ($this->value)
		{
			$db = JFactory::getDbo();
			$mquery = ' SELECT r.name '
			       . ' FROM #__tracks_projects_rounds AS pr '
			       . ' INNER JOIN #__tracks_rounds AS r ON r.id = pr.round_id '
			       . ' WHERE pr.id = '. (int) $this->value
			       ;
		  $db->setQuery($mquery);
		  $title = $db->loadResult();
		  if (!$title = $db->loadResult()) {
			  JError::raiseWarning(500, $db->getErrorMsg());
		  }
		}
	  if (empty($title)) {
		  $title = JText::_('COM_TRACKS_Select_a_project_round');
	  }
		$title = htmlspecialchars($title, ENT_QUOTES, 'UTF-8');
	
		// The current input field
		$html[] = '<div class="fltlft">';
		$html[] = '  <input type="text" id="'.$this->id.'_name" value="'.$title.'" disabled="disabled" size="35" />';
		$html[] = '</div>';
	
		// The select button
		$html[] = '<div class="button2-left">';
		$html[] = '  <div class="blank">';
		$html[] = '    <a class="modal" title="'.JText::_('COM_TRACKS_Select_a_project_round').'" href="'.$link.
	                         '" rel="{handler: \'iframe\', size: {x:800, y:450}}">'.
		JText::_('COM_TRACKS_Select_a_project_round').'</a>';
		$html[] = '  </div>';
		$html[] = '</div>';
	
		// The active id field
		if (0 == (int)$this->value) {
			$value = '';
		} else {
			$value = (int)$this->value;
		}
	
		// class='required' for client side validation
		$class = '';
		if ($this->required) {
			$class = ' class="required modal-value"';
		}
	
		$html[] = '<input type="hidden" id="'.$this->id.'_id"'.$class.' name="'.$this->name.'" value="'.$value.'" />';
	
		return implode("\n", $html);
	}
}