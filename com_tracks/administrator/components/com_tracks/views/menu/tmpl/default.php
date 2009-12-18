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

<style>
<!--
.tracksmenu-box {
background:#F6F6F6 none repeat scroll 0%;
margin-bottom:10px;
}
.tracksmenu-box .padding {
padding:0px;
}

.tracksmenu {
list-style-image:none;
list-style-position:outside;
list-style-type:none;
margin:0pt;
padding:0pt;
}
.tracksmenu li {
float:left;
margin:0pt;
padding:0pt;
}
.tracksmenu li a, .tracksmenu span.nolink {
border-right:1px solid #CCCCCC;
color:#0B55C4;
cursor:pointer;
font-weight:bold;
height:12px;
line-height:12px;
padding:0px 15px;
}
.tracksmenu span.nolink {
color:#999999;
}
.tracksmenu a.active, .tracksmenu span.nolink.active {
color:#000000;
text-decoration:underline;
}

.tracksmenu {
list-style-image:none;
list-style-position:outside;
list-style-type:none;
margin:0pt;
padding:0pt;
}
.tracksmenu li {
float:left;
margin:0pt;
padding:0pt;
}
.tracksmenu li a, .tracksmenu span.nolink {
border-right:1px solid #CCCCCC;
color:#0B55C4;
cursor:pointer;
font-weight:bold;
height:12px;
line-height:12px;
padding:0px 15px;
}
.tracksmenu span.nolink {
color:#999999;
}
.tracksmenu a.active, .tracksmenu span.nolink.active {
color:#000000;
text-decoration:underline;
}

#project_edit {
font-weight:bold;
}

-->
</style>

<div class="tracksmenu-box">
	<div class="t">
		<div class="t">
		   <div class="t"></div>
	    </div>
	</div>
	<div class="m">
		<?php 
		// Project objects
		if ( $this->project->id )
		{
			?>
			<ul class="tracksmenu">
				<li><span id="project_edit"><?php echo JText::_( 'EDITING ' ).$this->project->name; ?></span></li>
				<li><?php
				$link   = JRoute::_( 'index.php?option=com_tracks&view=projectrounds' );
				?> <a href="<?php echo $link; ?>"
					title="<?php echo JText::_( 'Project Rounds' ); ?>"> <?php echo JText::_( 'Rounds' ); ?></a>
				</li>
				<li><?php
				$link   = JRoute::_( 'index.php?option=com_tracks&view=projectindividuals' );
				?> <a href="<?php echo $link; ?>"
					title="<?php echo JText::_( 'Project Participants' ); ?>"> <?php echo JText::_( 'Participants' ); ?></a>
				</li>
			</ul>
			<?php
		}
		else
		{
			?> No project selected <?php
		}
		?>
	    <div class="clr"></div>
	</div>
	<div class="b">
		<div class="b">
		  <div class="b"></div>
		</div>
	</div>
</div>
