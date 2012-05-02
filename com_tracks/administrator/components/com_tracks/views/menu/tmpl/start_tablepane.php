<?php
/**
* @version    $Id: default.php 69 2008-04-24 18:11:26Z julienv $ 
* @package    JoomlaTracks
* @copyright    Copyright (C) 2008 Julien Vonthron. All rights reserved.
* @license      GNU/GPL, see LICENSE.php
* Joomla Tracks is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/

defined('_JEXEC') or die('Restricted access'); ?>

<form name="tracksnav" id="tracksnav" action="index.php" method="post">
	<?php echo $this->lists['project']; ?>
	<input name="option" type="hidden" value="com_tracks"> 
	<input name="controller" type="hidden" value="menu"> 
	<input name="task" type="hidden" value=""> 
	<input name="referer" type="hidden" value="<?php echo $this->referer; ?>"> 
</form>

<table class="tracks-main">

<td class="main-row tracks-main-menu">
<?php
echo $this->pane->startPane( 'tracksnav' );
if ( $this->project->id )
{
	echo $this->pane->startPanel( $this->project->name, 'projectpanel' );
  ?>
	<table class="adminlist">
	<!-- 
    <tr>
      <td>
      <a href="<?php echo JRoute::_( 'index.php?option=com_tracks&view=projectsettings' ); ?>"
        title="<?php echo JText::_('COM_TRACKS_SETTINGS' ); ?>"> <?php echo JText::_('COM_TRACKS_SETTINGS' ); ?></a>
      </td>
    </tr>
     -->
		<tr>
			<td>
      <a href="<?php echo JRoute::_( 'index.php?option=com_tracks&view=projectrounds' ); ?>"
        title="<?php echo JText::_('COM_TRACKS_Project_Rounds' ); ?>"> <?php echo JText::_('COM_TRACKS_Rounds' ); ?></a>
      </td>
		</tr>
		<tr>
			<td>
      <a href="<?php echo JRoute::_( 'index.php?option=com_tracks&view=projectindividuals' ); ?>"
        title="<?php echo JText::_('COM_TRACKS_Project_Participants' ); ?>"> <?php echo JText::_('COM_TRACKS_Participants' ); ?></a>
      </td>
		</tr>
	</table>
  <?php
  echo $this->pane->endPanel();
}
echo $this->pane->startPanel( JText::_('COM_TRACKS_General'), 'generalpanel' );
  ?>
  <table class="adminlist">
    <tr>
      <td>
      <a href="<?php echo JRoute::_( 'index.php?option=com_tracks&view=projects' ); ?>"
        title="<?php echo JText::_('COM_TRACKS_Projects' ); ?>"><?php echo JText::_( 'COM_TRACKS_Projects' ); ?></a>
      </td>
    </tr>
    <tr>
      <td>
      <a href="<?php echo JRoute::_( 'index.php?option=com_tracks&view=competitions' ); ?>"
        title="<?php echo JText::_('COM_TRACKS_Competitions' ); ?>"><?php echo JText::_('COM_TRACKS_Competitions' ); ?></a>
      </td>
    </tr>
    <tr>
      <td>
      <a href="<?php echo JRoute::_( 'index.php?option=com_tracks&view=seasons' ); ?>"
        title="<?php echo JText::_('COM_TRACKS_Seasons' ); ?>"><?php echo JText::_('COM_TRACKS_Seasons' ); ?></a>
      </td>
    </tr>
    <tr>
      <td>
      <a href="<?php echo JRoute::_( 'index.php?option=com_tracks&view=teams' ); ?>"
        title="<?php echo JText::_('COM_TRACKS_Teams' ); ?>"><?php echo JText::_('COM_TRACKS_Teams' ); ?></a>
      </td>
    </tr>
    <tr>
      <td>
      <a href="<?php echo JRoute::_( 'index.php?option=com_tracks&view=individuals' ); ?>"
        title="<?php echo JText::_('COM_TRACKS_Individuals' ); ?>"><?php echo JText::_('COM_TRACKS_Individuals' ); ?></a>
      </td>
    </tr>
    <tr>
      <td>
      <a href="<?php echo JRoute::_( 'index.php?option=com_tracks&view=rounds' ); ?>"
        title="<?php echo JText::_('COM_TRACKS_Rounds' ); ?>"><?php echo JText::_('COM_TRACKS_Rounds' ); ?></a>
      </td>
    </tr>
    <tr>
      <td>
      <a href="<?php echo JRoute::_( 'index.php?option=com_tracks&view=subroundtypes' ); ?>"
        title="<?php echo JText::_('COM_TRACKS_Sub_rounds_types' ); ?>"><?php echo JText::_('COM_TRACKS_Sub_rounds_types' ); ?></a>
      </td>
    </tr>
    <tr>
      <td>
      <a href="<?php echo JRoute::_( 'index.php?option=com_tracks&view=about' ); ?>"
        title="<?php echo JText::_('COM_TRACKS_About_tracks' ); ?>"><?php echo JText::_('COM_TRACKS_About' ); ?></a>
      </td>
    </tr>
  </table>
  <?php
  echo $this->pane->endPanel(); 
  echo $this->pane->startPanel( JText::_('COM_TRACKS_About'), 'aboutpanel' );
  ?>
  <table class="adminlist">
    <tr>
      <td>
      <a href="http://www.jlv-solutions.com" target="_blank"
        title="jlv-solutions.com">jlv-solutions.com</a>
      </td>
    </tr>
    <tr>
      <td>
      <a href="http://tracks.jlv-solutions.com/forum" target="_blank"
        title="<?php echo JText::_('COM_TRACKS_Support_forum'); ?>"><?php echo JText::_('COM_TRACKS_Support_forum'); ?></a>
      </td>
    </tr>
    <tr>
      <td>
      <a href="https://github.com/julienV/Joomla-Tracks" target="_blank"
        title="<?php echo JText::_('COM_TRACKS_Code_and_bug_reports'); ?>"><?php echo JText::_('COM_TRACKS_Code_and_bug_reports'); ?></a>
      </td>
    </tr>
    <tr>
      <td>  
      <form action="https://www.paypal.com/cgi-bin/webscr" method="post" class="tracksdonate" target="_blank">
		  <input type="hidden" name="cmd" value="_s-xclick">
		  <input type="hidden" name="hosted_button_id" value="2314656">
		  <input type="image" src="https://www.paypal.com/en_US/i/btn/btn_donateCC_LG_global.gif" border="0" name="submit" alt="">
		  <img alt="" border="0" src="https://www.paypal.com/fr_FR/i/scr/pixel.gif" width="1" height="1">
		  </form>
      </td>
    </tr>
  </table>
  <?php
  echo $this->pane->endPanel(); 
  echo $this->pane->endPane(); ?>
  </td>
  <td class="main-row">