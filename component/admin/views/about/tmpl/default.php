<?php
/**
* @version    $Id: default.php 20 2008-02-09 01:29:03Z julienv $
* @package    JoomlaTracks
* @copyright	Copyright (C) 2008 Julien Vonthron. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* Joomla Tracks is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/

defined('_JEXEC') or die('Restricted access');

RHelperAsset::load('tracksbackend.css');
?>
<div class="about-tracks">
	<div class="about-tracks__logo">
		  <img src="<?php echo JURI::root() ?>media/com_tracks/images/tracks_logo_250.png"/>
	</div>
	<div class="about-tracks__content">
		<h2>License</h2>
		<p><?php echo JText::_('COM_TRACKS_LICENSE'); ?></p>
		<p>
			Please visit our <a href="https://tracks.jlv-solutions.com/forum" target="_blank">forum for support</a>,
			or <a href="https://www.jlv-solutions.com">our website</a>.
		</p>
		<p>I have been a professional freelance developer for Joomla since 2008, feel free to <a href="https://www.jlv-solutions.com">contact me</a> if you need Joomla custom developements.</p>

		<p>
			Tracks logo by <a href="mailto:info@notethis.nl">Sebastiaan Rozendaal</a>, www.notethis.nl.
		</p>
		<p>
			Thanks!!
		</p>

		<h2><?php echo JText::_('COM_TRACKS_Helping'); ?></h2>

		<h3>Write a review</h3>
		<p>You can help by <a href="http://extensions.joomla.org/index.php?option=com_mtree&task=viewlink&link_id=4738&Itemid=2">voting and reviewing the component on Joomla extension directory</a>, by making donations, or by contributing with code, graphic, translations, etc...</p>

		<h3>Donate</h3>
		<form action="https://www.paypal.com/cgi-bin/webscr" method="post">
			<input type="hidden" name="cmd" value="_s-xclick">
			<input type="hidden" name="hosted_button_id" value="2314656">
			<input type="image" src="https://www.paypal.com/en_US/i/btn/btn_donateCC_LG_global.gif" border="0" name="submit" alt="">
			<img alt="" border="0" src="https://www.paypal.com/fr_FR/i/scr/pixel.gif" width="1" height="1">
		</form>

		<h3>Help translating to your language!</h3>
		<p>You can help by translating Tracks to you language, by using <a href="https://www.transifex.com/JoomlaTranslators/tracks/">transifex.com</a></p>
	</div>
</div>
