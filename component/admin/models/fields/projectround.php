<?php
/**
 * @package     Tracks
 * @subpackage  Admin
 * @copyright   Tracks (C) 2008-2015 Julien Vonthron. All rights reserved.
 * @license     GNU General Public License version 2 or later
 */

defined('_JEXEC') or die();

jimport('joomla.form.formfield');

$redcoreLoader = JPATH_LIBRARIES . '/redcore/bootstrap.php';

if (!file_exists($redcoreLoader) || !JPluginHelper::isEnabled('system', 'redcore'))
{
	throw new Exception(JText::_('COM_TRACKS_REDCORE_INIT_FAILED'), 404);
}

include_once $redcoreLoader;

/**
 * Tracks project round Field
 *
 * @package     Tracks
 * @subpackage  Library
 * @since       3.0
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
	 *
	 * @return string
	 *
	 * @throws Exception
	 */
	protected function getInput()
	{
		// Setup variables for display

		if ($this->value)
		{
			$db = JFactory::getDbo();
			$query = $db->getQuery(true);

			$query->select('r.name')
				->from('#__tracks_projects_rounds AS pr')
				->join('INNER', '#__tracks_rounds AS r ON r.id = pr.round_id')
				->where('pr.id = ' . (int) $this->value);
			$db->setQuery($query);

			$title = $db->loadResult();

			if (!$title = $db->loadResult())
			{
				throw new Exception($db->getErrorMsg(), 500);
			}
		}

		if (empty($title))
		{
			$title = JText::_('COM_TRACKS_Select_a_project_round');
		}

		$title = htmlspecialchars($title, ENT_QUOTES, 'UTF-8');

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

		return RLayoutHelper::render(
			'form.field.projectround',
			array(
				'id' => $this->id,
				'name' => $this->name,
				'title' => $title,
				'value' => $this->value,
				'required' => $this->required
			),
			'',
			array('component' => 'com_tracks')
		);
	}
}
