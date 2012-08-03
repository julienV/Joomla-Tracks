<?php
/**
 * @version    $Id: form.php 94 2008-05-02 10:28:05Z julienv $
 * @package    JoomlaTracks
 * @copyright	Copyright (C) 2008 Julien Vonthron. All rights reserved.
 * @license		GNU/GPL, see LICENSE.php
 * Joomla Tracks is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * See COPYRIGHT.php for copyright notices and details.
 */

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.plugin.plugin' );

/**
 * Editor Pagebreak buton
 *
 * @package Editors-xtd
 * @since 1.5
 */
class plgButtonTracks_individual extends JPlugin
{
	/**
	 * Constructor
	 *
	 * @access      protected
	 * @param       object  $subject The object to observe
	 * @param       array   $config  An array that holds the plugin configuration
	 * @since       1.5
	 */
	public function __construct(& $subject, $config)
	{
		parent::__construct($subject, $config);
		$this->loadLanguage();
	}

	/**
	 * Display the button
	 *
	 * @return object button
	 */
	function onDisplayyyyy($name)
	{
		$mainframe = JFactory::getApplication();

		$doc = JFactory::getDocument();
		$template = $mainframe->getTemplate();
		
		$declaration	="
		.button2-left .tracks_individual 	{ background: url(components/com_tracks/assets/images/indiv_editor_button.png) 100% 0 no-repeat; } ";
		
		$doc->addStyleDeclaration($declaration);

		$link = 'index.php?option=com_tracks&amp;controller=individuals&amp;view=individuals&amp;layout=modal&amp;tmpl=component&amp;e_name='.$name;

		JHTML::_('behavior.modal');

		$button = new JObject();
		$button->set('modal', true);
		$button->set('link', $link);
		$button->set('text', JText::_('PLG_TRACKS_INDIVIDUAL_EDITORXTD_BUTTON_LABEL'));
		$button->set('name', 'tracks_individual');
		$button->set('options', "{handler: 'iframe', size: {x: 600, y: 500}}");

		return $button;
	}
	/**
	 * Display the button
	 *
	 * @return object button
	 */
	function onDisplay($name)
	{
		/*
		 * Javascript to insert the link
		 * View element calls jSelectIndividual when an individual is clicked
		 * jSelectIndividual creates the link tag, sends it to the editor,
		 * and closes the select frame.
		 */
		$js = "
		function jSelectIndividual(id, lastname, firstname) {
		  var title = firstname ? firstname+' '+lastname : lastname;
			var tag = '<a href='+'\"index.php?option=com_tracks&amp;view=individual&amp;i='+id+'\">'+title+'</a>';
			jInsertEditorText(tag, '".$name."');
			SqueezeBox.close();
		}";

		$doc = JFactory::getDocument();
		$doc->addScriptDeclaration($js);
		
		$app = JFactory::getApplication();
		if ($app->isAdmin()) {
			$declaration	="
			.button2-left .tracks_individual 	{ background: url(components/com_tracks/assets/images/indiv_editor_button.png) 100% 0 no-repeat; } ";
			$doc->addStyleDeclaration($declaration);
		}
		
		JHtml::_('behavior.modal');

		/*
		 * Use the built-in element view to select the individual.
		 * Currently uses blank class.
		 */
		$link = 'index.php?option=com_tracks&amp;view=individuals&amp;layout=modal&amp;tmpl=component';

		$button = new JObject();
		$button->set('modal', true);
		$button->set('link', $link);
		$button->set('text', JText::_('PLG_TRACKS_INDIVIDUAL_EDITORXTD_BUTTON_LABEL'));
		$button->set('name', 'tracks_individual');
		$button->set('options', "{handler: 'iframe', size: {x: 770, y: 400}}");

		return $button;
	}
}