<?php
/**
* @version    $Id: default.php 101 2008-05-22 08:32:12Z julienv $ 
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
defined('_JEXEC') or die('Restricted access'); ?>

<base target="_parent">


<div id="externalrider">

<!-- Title -->


<div style="width:100%;">
    <h3 class="title" ><?php echo  $this->data->first_name . ' ' . ($this->data->nickname ? "'" . $this->data->nickname . "' " : "")  . $this->data->last_name; ?></h3>
</div>


<!--
<?php if ($this->data->picture): ?>
-->

<div style="clear:both;float:left;width:60px;height:60px;margin-right:-100%;overflow:hidden;">
  <?php echo $this->data->picture_small; ?>
</div>



<!--
<div><?php echo $this->data->nickname; ?></div>
-->
<div style="float:left;width:100%;">

<div style="overflow:hidden;margin-left:60px;">

<!--
<UL style="text-align:center;line-height:16px;"><li style="width:100%;background:#f5f5ff">all info</UL>
-->

</div>

<div style="padding:2px;overflow:hidden;margin-left:60px;">

<div style="float:right"><A HREF="<?php echo 'http://xjetski.com/component/tracks/individual/' . $this->data->id  ?>">full rider info</A></div>
  <?php echo TracksCountries::getCountryFlag($this->data->country_code); ?><br>

  <?php if ($this->data->address): ?>
    Sponsors: <?php echo $this->data->address; ?><br>
  <?php endif; ?>

</div>


<!--
<?php endif; ?>
-->



</div>

<UL style="text-align:center;line-height:16px;"><li style="width:100%;background:#f5f5ff">data by <A HREF="http://xjetski.com"><b>xjetski</b>.com</A></li></UL>

<!--
<div id="individualdetails">
<table cellpadding="5" cellspacing="0" border="0" width="90%">
    <?php if (isset($this->data->projectdata->number) && !empty($this->data->projectdata->number)): ?>
    <tr>
      <td width="100" align="right" class="key">
				<?php echo JText::_( 'COM_TRACKS_INDIVIDUAL_NUMBER' ); ?>:
      </td>
      <td>
        <?php echo $this->data->projectdata->number; ?>
      </td>
    </tr>
    <?php endif; ?>
    <?php if (isset($this->data->projectdata->team_name) && !empty($this->data->projectdata->team_name)): ?>
    <tr>
      <td width="100" align="right" class="key">
				<?php echo JText::_( 'COM_TRACKS_INDIVIDUAL_TEAM' ); ?>:
      </td>
      <td>
        <?php echo JHTML::link(TracksHelperRoute::getTeamRoute($this->data->projectdata->teamslug, $this->data->projectdata->projectslug), $this->data->projectdata->team_name); ?>
      </td>
    </tr>
    <?php endif; ?>
    <?php if ($this->data->nickname): ?>
    <tr>
      <td width="100" align="right" class="key">
        <label for="nickname">
          <?php echo JText::_('COM_TRACKS_Nickname' ); ?>:
        </label>
      </td>
      <td>
        <?php echo $this->data->nickname; ?>
      </td>
    </tr>
    <?php endif; ?>


</table>
</div>
-->



<?php if ($this->params->get('indview_showresults', 1)): ?>
<?php echo $this->loadTemplate('results'); ?>
<?php endif; ?>

</div><!-- end of tracks -->