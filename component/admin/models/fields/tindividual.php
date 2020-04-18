<?php
/**
 * @package     Tracks
 * @subpackage  Admin
 * @copyright   Tracks (C) 2008-2015 Julien Vonthron. All rights reserved.
 * @license     GNU General Public License version 2 or later
 */

defined('_JEXEC') or die();

jimport('joomla.form.formfield');

/**
 * Individual form field class
 *
 * @package  Tracks
 * @since    1.0
 */
class JFormFieldTIndividual extends JFormField
{
	/**
	 * field type
	 * @var string
	 */
	protected $type = 'TIndividual';

	/**
	 * Method to get the field input markup
	 *
	 * @return void
	 */
	protected function getInput()
	{
		// Load modal behavior
		JHtml::_('behavior.modal', 'a.modal');

		// Build the script
		$script   = array();
		$script[] = '    function jSelectIndividual_' . $this->id . '(id, lastname, firstname) {';
		$script[] = '        var title = firstname ? firstname+" "+lastname : lastname;';
		$script[] = '        document.id("' . $this->id . '_id").value = id;';
		$script[] = '        document.id("' . $this->id . '_name").value = title;';
		$script[] = '        SqueezeBox.close();';
		$script[] = '    }';

		// Add to document head
		JFactory::getDocument()->addScriptDeclaration(implode("\n", $script));

		// Setup variables for display
		$html  = array();
		$link  = 'index.php?option=com_tracks&amp;view=individuals&amp;layout=modal&amp;tmpl=component&amp;function=jSelectIndividual_' . $this->id;
		$title = null;

		if ($this->value)
		{
			$db    = JFactory::getDbo();
			$query = $db->getQuery(true);

			$query->select('i.last_name, i.first_name, i.nickname');
			$query->from('#__tracks_individuals AS i');
			$query->where('i.id = ' . (int) $this->value);
			$db->setQuery($query);

			if (!$title = $db->loadObject())
			{
				JError::raiseWarning(500, $db->getErrorMsg());
			}
		}

		if (!$title)
		{
			$title = JText::_('COM_TRACKS_Select_a_person');
		}
		else
		{
			$title = htmlspecialchars($title->last_name . ', ' . $title->first_name, ENT_QUOTES, 'UTF-8');
		}

		// The current input field
		$html[] = '<div class="fltlft">';
		$html[] = '  <input type="text" id="' . $this->id . '_name" value="' . $title . '" disabled="disabled" size="35" />';
		$html[] = '</div>';

		// The select button
		$html[] = '<div class="button2-left">';
		$html[] = '  <div class="blank">';
		$html[] = '    <a class="modal" title="' . JText::_('COM_TRACKS_Select_a_person') . '" href="' . $link .
			'" rel="{handler: \'iframe\', size: {x:800, y:450}}">' .
			JText::_('COM_TRACKS_Select_a_person') . '</a>';
		$html[] = '  </div>';
		$html[] = '</div>';

		// The active id field
		if (0 == (int) $this->value)
		{
			$value = '';
		}
		else
		{
			$value = (int) $this->value;
		}

		$class = '';

		if ($this->required)
		{
			$class = ' class="required modal-value"';
		}

		$html[] = '<input type="hidden" id="' . $this->id . '_id"' . $class . ' name="' . $this->name . '" value="' . $value . '" />';

		return implode("\n", $html);
	}
}
