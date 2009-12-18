<?php
/**
* @version    $Id: default.php 135 2008-06-08 21:50:12Z julienv $ 
* @package    JoomlaTracks
* @copyright	Copyright (C) 2008 Julien Vonthron. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* Joomla Tracks is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/
defined('_JEXEC') or die('Restricted access'); ?>

<?php require_once(JPATH_COMPONENT.DS.'helpers'.DS.'countries.php'); ?>

<div id="tracks">

<h1><?php echo $this->project->name . ' ' . JText::_( 'Participants' ); ?></h1>

<ul class="individualsGallery">
    <?php
    foreach( $this->individuals AS $obj )
    {
      $link_ind = JRoute::_( 'index.php?option=com_tracks&view=individual&i=' . $obj->slug ); 
      $link_team = JRoute::_( 'index.php?option=com_tracks&view=team&t=' . $obj->teamslug );      
      ?>
      <li>
        <div>
          <a href="<?php echo $link_ind; ?>"
  			   title="<?php echo $obj->first_name . ' ' . $obj->last_name; ?>">
  			   <?php echo $obj->picture; ?>
  			  </a>
	        <p>
		        <span class="individualnumber">#<?php echo $obj->number; ?></span>
		        <a href="<?php echo $link_ind; ?>"
	                title="<?php echo $obj->first_name . ' ' . $obj->last_name;; ?>">
	           <?php
		          // individual name 
		          echo $obj->first_name . ' ' . $obj->last_name;
			        ?>
			      </a>
		        <?php if ($this->projectparams->get('showflag')): ?>
		          <?php if ( $obj->country_code ): ?>
		          <?php echo Countries::getCountryFlag($obj->country_code); ?>
	            <?php endif ?>
	          <?php endif; ?>
          </p>
	        
	        <?php if ( $obj->team_name && $this->projectparams->get('showteams') ) : ?>
	        <div>
						<a href="<?php echo $link_team; ?>"
						   title="<?php echo JText::_( 'Details' ); ?>"> <?php echo $obj->team_name; ?>&nbsp;
						</a>
					</div> 
					<?php endif; ?>
        </div>
      </li>
      <?php     
    }
    ?>
</ul>
<div class=clear></div>
<p class="copyright">
  <?php echo HTMLtracks::footer( ); ?>
</p>
</div>