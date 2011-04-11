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

<?php
echo $this->pane->startPane( 'tracksnav' );
if ( $this->project->id )
{
	echo $this->pane->startPanel( $this->project->name, 'projectpanel' );
  ?>
	<table class="adminlist">
    <tr>
      <td>
      <a href="<?php echo JRoute::_( 'index.php?option=com_tracks&view=projectsettings' ); ?>"
        title="<?php echo JText::_( 'SETTINGS' ); ?>"> <?php echo JText::_( 'SETTINGS' ); ?></a>
      </td>
    </tr>
		<tr>
			<td>
      <a href="<?php echo JRoute::_( 'index.php?option=com_tracks&view=projectrounds' ); ?>"
        title="<?php echo JText::_( 'Project Rounds' ); ?>"> <?php echo JText::_( 'Rounds' ); ?></a>
      </td>
		</tr>
		<tr>
			<td>
      <a href="<?php echo JRoute::_( 'index.php?option=com_tracks&view=projectindividuals' ); ?>"
        title="<?php echo JText::_( 'Project Participants' ); ?>"> <?php echo JText::_( 'Participants' ); ?></a>
      </td>
		</tr>
	</table>
  <?php
  echo $this->pane->endPanel();
}
echo $this->pane->startPanel( JTEXT::_('General'), 'generalpanel' );
  ?>
  <table class="adminlist">
    <tr>
      <td>
      <a href="<?php echo JRoute::_( 'index.php?option=com_tracks&view=projects' ); ?>"
        title="<?php echo JText::_( 'Projects' ); ?>"><?php echo JText::_( 'Projects' ); ?></a>
      </td>
    </tr>
    <tr>
      <td>
      <a href="<?php echo JRoute::_( 'index.php?option=com_tracks&view=competitions' ); ?>"
        title="<?php echo JText::_( 'Competitions' ); ?>"><?php echo JText::_( 'Competitions' ); ?></a>
      </td>
    </tr>
    <tr>
      <td>
      <a href="<?php echo JRoute::_( 'index.php?option=com_tracks&view=seasons' ); ?>"
        title="<?php echo JText::_( 'Seasons' ); ?>"><?php echo JText::_( 'Seasons' ); ?></a>
      </td>
    </tr>
    <tr>
      <td>
      <a href="<?php echo JRoute::_( 'index.php?option=com_tracks&view=teams' ); ?>"
        title="<?php echo JText::_( 'Teams' ); ?>"><?php echo JText::_( 'Teams' ); ?></a>
      </td>
    </tr>
    <tr>
      <td>
      <a href="<?php echo JRoute::_( 'index.php?option=com_tracks&view=individuals' ); ?>"
        title="<?php echo JText::_( 'Individuals' ); ?>"><?php echo JText::_( 'Individuals' ); ?></a>
      </td>
    </tr>
    <tr>
      <td>
      <a href="<?php echo JRoute::_( 'index.php?option=com_tracks&view=rounds' ); ?>"
        title="<?php echo JText::_( 'Rounds' ); ?>"><?php echo JText::_( 'Rounds' ); ?></a>
      </td>
    </tr>
    <tr>
      <td>
      <a href="<?php echo JRoute::_( 'index.php?option=com_tracks&view=subroundtypes' ); ?>"
        title="<?php echo JText::_( 'Sub rounds types' ); ?>"><?php echo JText::_( 'Sub rounds types' ); ?></a>
      </td>
    </tr>
    <tr>
      <td>
      <a href="<?php echo JRoute::_( 'index.php?option=com_tracks&view=about' ); ?>"
        title="<?php echo JText::_( 'About tracks' ); ?>"><?php echo JText::_( 'About' ); ?></a>
      </td>
    </tr>
  </table>
  <?php
  echo $this->pane->endPanel(); 
  echo $this->pane->startPanel( JTEXT::_('About'), 'aboutpanel' );
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
        title="<?php echo JText::_('Support forum'); ?>"><?php echo JText::_('Support forum'); ?></a>
      </td>
    </tr>
    <tr>
      <td>
      <a href="https://github.com/julienV/Joomla-Tracks" target="_blank"
        title="<?php echo JText::_('Code and bug reports'); ?>"><?php echo JText::_('Code and bug reports'); ?></a>
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
  echo $this->pane->endPane();
  