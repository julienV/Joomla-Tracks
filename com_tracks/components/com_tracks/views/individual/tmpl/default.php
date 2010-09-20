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
<table class="contentpaneopen">
<tbody>
<tr>
<td class="contentheading" width="100%"><?php echo $this->data->first_name . ' ' . $this->data->last_name; ?></td>
</tr>
</tbody>
</table>

<?php if ($this->show_edit_link): ?>
<div id="editprofile"><a href="<?php echo JRoute::_( TracksHelperRoute::getIndividualRoute($this->data->id).'&task=edit' ); ?>" 
       title ="<?php echo JText::_( 'Edit profile' ) ?>">
          <?php echo JText::_( 'Edit profile' ); ?>
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

<h2><?php echo JText::_('Details'); ?></h2>

<?php if ($this->data->picture): ?>
<div id="individualpic">
<?php echo $this->data->picture; ?>
</div>
<?php endif; ?>
<div id="individualdetails">
<table cellpadding="5" cellspacing="0" border="0" width="90%">
    <?php if ($this->data->nickname): ?>
    <tr>
      <td width="100" align="right" class="key">
        <label for="nickname">
          <?php echo JText::_( 'Nickname' ); ?>:
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
          <?php echo JText::_( 'Height' ); ?>:
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
          <?php echo JText::_( 'Weight' ); ?>:
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
          <?php echo JText::_( 'Country' ); ?>:
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
          <?php echo JText::_( 'Date of birth' ); ?>:
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
          <?php echo JText::_( 'Hometown' ); ?>:
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
          <?php echo JText::_( 'Address' ); ?>:
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
          <?php echo JText::_( 'Postcode' ); ?>:
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
          <?php echo JText::_( 'City' ); ?>:
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
          <?php echo JText::_( 'State' ); ?>:
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
          <?php echo JText::_( 'Country' ); ?>:
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

<p class="copyright">
  <?php echo HTMLtracks::footer( ); ?>
</p>
    
</div><!-- end of tracks -->