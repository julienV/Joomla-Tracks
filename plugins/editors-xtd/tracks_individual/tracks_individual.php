<?php
/**
 * @package     JoomlaTracks
 * @subpackage  Plugins.site
 * @copyright   Copyright (C) 2008-2015 Julien Vonthron. All rights reserved.
 * @license     GNU General Public License version 2 or later
 */

defined('_JEXEC') or die('Restricted access');

$tracksLoader = JPATH_LIBRARIES . '/tracks/bootstrap.php';

if (!file_exists($tracksLoader))
{
	throw new Exception(JText::_('COM_TRACKS_LIB_INIT_FAILED'), 404);
}

include_once $tracksLoader;

// Bootstraps Tracks
TrackslibBootstrap::bootstrap();

jimport( 'joomla.plugin.plugin' );

/**
 * Editor Tracks individual buton
 *
 * @package     JoomlaTracks
 * @subpackage  Plugins.site
 * @since       1.0
 */
class plgButtonTracks_individual extends JPlugin
{
	/**
	 * Constructor
	 *
	 * @param   object  &$subject  The object to observe
	 * @param   array   $config    An array that holds the plugin configuration
	 */
	public function __construct(&$subject, $config)
	{
		parent::__construct($subject, $config);
		$this->loadLanguage();
	}

	/**
	 * Display the button
	 *
	 * @param   string  $name  The name of the button to add
	 *
	 * @return object
	 */
	public function onDisplay($name)
	{
		if (version_compare(JVERSION, '3.0', '<'))
		{
			return $this->displayJ2($name);
		}
		else
		{
			return $this->displayJ3($name);
		}
	}

	/**
	 * Display the button
	 *
	 * @param   string  $name  The name of the button to add
	 *
	 * @return object
	 */
	public function displayJ2($name)
	{
		$js = "
		function jSelectIndividual(id, lastname, firstname) {
		  var title = firstname ? firstname+' '+lastname : lastname;
			var tag = '<a href='+'\"index.php?option=com_tracks&amp;view=individual&amp;id='+id+'\">'+title+'</a>';
			jInsertEditorText(tag, '" . $name . "');
			SqueezeBox.close();
		}";

		$doc = JFactory::getDocument();
		$doc->addScriptDeclaration($js);

		$app = JFactory::getApplication();

		if ($app->isAdmin())
		{
			$declaration = "
			.button2-left .tracks_individual 	{ background: url(media/com_tracks/images/indiv_editor_button.png) 100% 0 no-repeat; } ";
			$doc->addStyleDeclaration($declaration);
		}

		JHtml::_('behavior.modal');

		/*
		 * Use the built-in element view to select the individual.
		 * Currently uses blank class.
		 */
		$link = 'index.php?option=com_tracks&amp;view=individuals&amp;layout=modal&amp;tmpl=component';

		$button = new JObject;
		$button->set('modal', true);
		$button->set('link', $link);
		$button->set('text', JText::_('PLG_TRACKS_INDIVIDUAL_EDITORXTD_BUTTON_LABEL'));
		$button->set('name', 'tracks_individual');
		$button->set('options', "{handler: 'iframe', size: {x: 770, y: 400}}");

		return $button;
	}

	/**
	 * Display the button
	 *
	 * @param   string  $name  The name of the button to add
	 *
	 * @return object
	 */
	protected function displayJ3($name)
	{
		$js = "
		function jSelectIndividual(id, lastname, firstname, link)
		{
			var title = firstname ? firstname+' '+lastname : lastname;
			var tag = '<a' + ' href=\"' + link + '\">' + title + '</a>';
			jInsertEditorText(tag, '" . $name . "');
			jModalClose();
		}";

		$doc = JFactory::getDocument();
		$doc->addScriptDeclaration($js);

		JHtml::_('behavior.modal');

		/*
		 * Use the built-in element view to select the article.
		 * Currently uses blank class.
		 */
		$link = 'index.php?option=com_tracks&amp;view=individuals&amp;layout=modal&amp;tmpl=component&amp;' . JSession::getFormToken() . '=1';

		$button = new JObject;
		$button->modal = true;
		$button->class = 'btn';
		$button->link = $link;
		$button->text = JText::_('PLG_TRACKS_INDIVIDUAL_EDITORXTD_BUTTON_LABEL');
		$button->name = 'tracks-individual-add';
		$button->options = "{handler: 'iframe', size: {x: 800, y: 500}}";

		return $button;
	}
}
