<?php
/**
* @version    $Id: default.php 101 2008-05-22 08:32:12Z julienv $
* @package    JoomlaTracks
* @copyright  Copyright (C) 2008 Julien Vonthron. All rights reserved.
* @license    GNU/GPL, see LICENSE.php
* Joomla Tracks is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/
 // no direct access
defined('_JEXEC') or die('Restricted access');

$socials = array(
	'url',
	'facebook',
	'twitter',
	'googleplus',
	'youtube',
	'instagram',
	'pinterest',
	'vimeo',
);

$show = false;

foreach ($socials as $s)
{
	if ($this->data->{$s})
	{
		$show = true;
		break;
	}
}
if ($show):
?>
<div class="team-social">
	<h3><?php echo JTExt::_('COM_TRACKS_VIEW_TEAM_INDIVIDUALS'); ?></h3>
	<ul>
		<?php if ($this->data->url): ?>
			<li><span class="social-lbl"><?php echo JText::_('COM_TRACKS_TEAM_SOCIAL_URL'); ?></span>
			<a href="<?php echo $this->data->url; ?>"><?php echo $this->data->url; ?></a>
			</li>
		<?php endif ;?>

		<?php if ($this->data->facebook): ?>
			<li><span class="social-lbl"><?php echo JText::_('COM_TRACKS_TEAM_SOCIAL_facebook'); ?></span>
				<?php echo JHtml::link('http://www.facebook.com/' . $this->data->facebook, $this->data->facebook);?>
			</li>
		<?php endif ;?>

		<?php if ($this->data->twitter): ?>
			<li><span class="social-lbl"><?php echo JText::_('COM_TRACKS_TEAM_SOCIAL_twitter'); ?></span>
				<?php echo JHtml::link('http://www.twitter.com/' . $this->data->twitter, $this->data->twitter);?>
			</li>
		<?php endif ;?>

		<?php if ($this->data->googleplus): ?>
			<li><span class="social-lbl"><?php echo JText::_('COM_TRACKS_TEAM_SOCIAL_googleplus'); ?></span>
				<?php echo JHtml::link('http://plus.google.com/' . $this->data->googleplus, $this->data->googleplus);?>
			</li>
		<?php endif ;?>

		<?php if ($this->data->youtube): ?>
			<li><span class="social-lbl"><?php echo JText::_('COM_TRACKS_TEAM_SOCIAL_youtube'); ?></span>
				<?php echo JHtml::link('http://www.youtube.com/user/' . $this->data->youtube, $this->data->youtube);?>
			</li>
		<?php endif ;?>

		<?php if ($this->data->instagram): ?>
			<li><span class="social-lbl"><?php echo JText::_('COM_TRACKS_TEAM_SOCIAL_instagram'); ?></span>
				<?php echo JHtml::link('http://www.instagram.com/' . $this->data->instagram, $this->data->instagram);?>
			</li>
		<?php endif ;?>

		<?php if ($this->data->pinterest): ?>
			<li><span class="social-lbl"><?php echo JText::_('COM_TRACKS_TEAM_SOCIAL_pinterest'); ?></span>
				<?php echo JHtml::link('http://www.pinterest.com/' . $this->data->pinterest, $this->data->pinterest);?>
			</li>
		<?php endif ;?>

		<?php if ($this->data->vimeo): ?>
			<li><span class="social-lbl"><?php echo JText::_('COM_TRACKS_TEAM_SOCIAL_vimeo'); ?></span>
				<?php echo JHtml::link('http://www.vimeo.com/' . $this->data->pinterest, $this->data->vimeo);?>
			</li>
		<?php endif ;?>
	</ul>
</div>
<?php endif;
