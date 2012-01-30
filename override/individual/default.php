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

<div id="tracks">
<!-- Title -->
<h1><?php echo $this->data->first_name . ' ' . $this->data->last_name; ?></h1>

<?php if ($this->show_edit_link): ?>
<div id="editprofile"><a href="<?php echo JRoute::_( TracksHelperRoute::getEditIndividualRoute($this->data->id).'&task=edit' ); ?>" 
       title ="<?php echo JText::_('COM_TRACKS_Edit_profile' ) ?>">
          <?php echo JText::_('COM_TRACKS_Edit_profile' ); ?>
          </a></div>
<?php endif; ?>

<!-- Content -->
<table class="contentpaneopen">
<tbody>
<?php 
  $link = null;
  $res = $this->dispatcher->trigger( 'getProfileLink', array( $this->data->user_id, &$link ));
  if (!empty($link)): ?>
<tr>
  <td>
    <?php echo $link->text; ?>
  </td>
</tr>
<?php endif; ?>
</tbody>
</table>

<h2><?php echo JText::_('COM_TRACKS_Details'); ?></h2>

<?php if ($this->data->picture): ?>
<div id="individualpic">
<?php echo $this->data->picture; ?>
</div>
<?php endif; ?>
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
    <?php if ($this->data->height): ?>
    <tr>
      <td width="100" align="right" class="key">
        <label for="height">
          <?php echo JText::_('COM_TRACKS_Height' ); ?>:
        </label>
      </td>
      <td>
        <?php echo $this->data->height; ?>
      </td>
    </tr>
    <?php endif; ?>
    <?php if ($this->data->weight): ?>
    <tr>
      <td width="100" align="right" class="key">
        <label for="weight">
          <?php echo JText::_('COM_TRACKS_Weight' ); ?>:
        </label>
      </td>
      <td>
        <?php echo $this->data->weight; ?>
      </td>
    </tr>
    <?php endif; ?>
    <?php if ($this->data->country_code): ?>
    <tr>
      <td width="100" align="right" class="key">
        <label for="country_code">
          <?php echo JText::_('COM_TRACKS_Country' ); ?>:
        </label>
      </td>
      <td>
        <?php echo TracksCountries::getCountryFlag($this->data->country_code); ?>
      </td>
    </tr>
    <?php endif; ?>
    <?php if (strtotime($this->data->dob)): ?>
    <tr>
      <td width="100" align="right" class="key">
        <label for="dob">
          <?php echo JText::_('COM_TRACKS_Date_of_birth' ); ?>:
        </label>
      </td>
      <td>
      <?php echo $this->data->dob; ?>
      </td>
    </tr>
    <?php endif; ?>
    <?php if ($this->data->hometown): ?>
    <tr>
      <td width="100" align="right" class="key">
        <label for="hometown">
          <?php echo JText::_('COM_TRACKS_Hometown' ); ?>:
        </label>
      </td>
      <td>
        <?php echo $this->data->hometown; ?>
      </td>
    </tr>
    <?php endif; ?>
    <?php if ($this->data->address): ?>
    <tr>
      <td width="100" align="right" class="key">
        <label for="address">
          <?php echo JText::_('COM_TRACKS_Address' ); ?>:
        </label>
      </td>
      <td>
        <?php echo $this->data->address; ?>
      </td>
    </tr>
    <?php endif; ?>
    <?php if ($this->data->postcode): ?>
    <tr>
      <td width="100" align="right" class="key">
        <label for="postcode">
          <?php echo JText::_('COM_TRACKS_Postcode' ); ?>:
        </label>
      </td>
      <td>
        <?php echo $this->data->postcode; ?>
      </td>
    </tr>
    <?php endif; ?>
    <?php if ($this->data->city): ?>
    <tr>
      <td width="100" align="right" class="key">
        <label for="city">
          <?php echo JText::_('COM_TRACKS_City' ); ?>:
        </label>
      </td>
      <td>
        <?php echo $this->data->city; ?>
      </td>
    </tr>
    <?php endif; ?>
    <?php if ($this->data->state): ?>
    <tr>
      <td width="100" align="right" class="key">
        <label for="state">
          <?php echo JText::_('COM_TRACKS_State' ); ?>:
        </label>
      </td>
      <td>
        <?php echo $this->data->state; ?>
      </td>
    </tr>
    <?php endif; ?>
    <?php if ($this->data->country): ?>
    <tr>
      <td width="100" align="right" class="key">
        <label for="last_name">
          <?php echo JText::_('COM_TRACKS_Country' ); ?>:
        </label>
      </td>
      <td>
        <?php echo $this->data->country; ?>
      </td>
    </tr>
    <?php endif; ?>
    <?php if ($this->data->description): ?>
    <tr>
      <td colspan="2">
        <?php echo $this->data->description; ?>
      </td>
    </tr>
    <?php endif; ?>
</table>
</div><!-- end of individualdetails -->
<div class="clear"></div>
<?php if ($this->params->get('indview_showresults', 1)): ?>
<?php echo $this->loadTemplate('results'); ?>
<?php endif; ?>


  <script>
	window.addEvent('domready', function(){
		document.id('countryupdate').addEvent('change', function(){
			this.form.submit();
		});
	});
	</script>
		<form name="addcountry" method="post" action="<?php echo JFactory::getURI()->toString(); ?>">
        <?php echo JHTML::_('select.genericlist', TracksCountries::getCountryOptions(), 'countryupdate', '', 'value', 'text', $this->data->country_code ? $this->data->country_code : 0); ?>
		<input type="hidden" name="controller" value="individual" />
		<input type="hidden" name="option" value="com_tracks" />
		<input type="hidden" name="task" value="savecountry" />
		<input type="hidden" name="id" value="<?php echo $this->data->id; ?>" />
		</form>
		
<p class="copyright">
  <?php echo HTMLtracks::footer( ); ?>
</p>
    
</div><!-- end of tracks -->