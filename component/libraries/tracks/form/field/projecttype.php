<?php
/**
 * @package     Tracks
 * @subpackage  Library
 * @copyright   Tracks (C) 2008-2017 Julien Vonthron. All rights reserved.
 * @license     GNU General Public License version 2 or later
 */

defined('_JEXEC') or die;

JFormHelper::loadFieldClass('list');

/**
 * Tracks Project type Field
 *
 * @package     Tracks
 * @subpackage  Library
 * @since       __deploy_version__
 */
class TracksFormFieldProjecttype extends JFormFieldList
{
	/**
	 * The form field type.
	 *
	 * @var  string
	 */
	public $type = 'projecttype';

	/**
	 * Method to get the field options.
	 *
	 * @return  array  The field option objects.
	 */
	protected function getOptions()
	{
		$types = array();

		JPluginHelper::importPlugin('tracks_projecttype');
		RFactory::getDispatcher()->trigger('onTracksGetProjectTypes', array(&$types));

		$options = array_map(
			function($type)
			{
				return JHtml::_('select.option', $type['name'], $type['label']);
			},
			$types
		);

		return array_merge(parent::getOptions(), $options);
	}
}
