<?php
/**
* @version    $Id: form.php 128 2008-06-06 08:08:04Z julienv $ 
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

<?php JHTML::_('behavior.tooltip'); ?>






<style type="text/css">
div#bd {
    position: relative;
}
div#gmap {
    margin-left:0px;
    width: 880px;
    height: 300px;
}
</style>
<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false"></script>
<script type="text/javascript">
var map;
var marker=false;
function initialize() {
  var myLatlng = new google.maps.LatLng(<?php echo ($this->object->lat?$this->object->lat.','.$this->object->long:'0,0'); ?>);

  var myOptions = {
    zoom: 3,
    center: myLatlng,
    zoomControl: false,
     zoomControlOptions: {
      style: google.maps.ZoomControlStyle.LARGE
        },
    streetViewControl: false,
    mapTypeControl: false,
    mapTypeId: google.maps.MapTypeId.ROADMAP
  }

  map = new google.maps.Map(document.getElementById("gmap"), myOptions);

  marker = new google.maps.Marker({
      	position: myLatlng,
      	map: map
  	});

  google.maps.event.addListener(map, 'center_changed', function() {
  	var location = map.getCenter();
	document.getElementById("lat").value = location.lat();
	document.getElementById("long").value = location.lng();
    placeMarker(location);
  });
  google.maps.event.addListener(map, 'zoom_changed', function() {
  	zoomLevel = map.getZoom();
	document.getElementById("zoom_level").innerHTML = zoomLevel;
  });
  google.maps.event.addListener(marker, 'dblclick', function() {
    zoomLevel = map.getZoom()+1;
    if (zoomLevel == 20) {
     zoomLevel = 10;
   	}
//	document.getElementById("zoom_level").innerHTML = zoomLevel;
	map.setZoom(zoomLevel);

  });

//  document.getElementById("zoom_level").innerHTML = 7;
//  document.getElementById("lat").innerHTML = 38.909017951243754;
//  document.getElementById("lon").innerHTML = 1.4319777488708496;
}

function placeMarker(location) {
  var clickedLocation = new google.maps.LatLng(location);
  marker.setPosition(location);
}
window.onload = function(){initialize();};

</script>









<script language="javascript" type="text/javascript">
	Joomla.submitbutton = function (pressbutton) {

		var form = document.individualform;
		if (pressbutton == 'cancel') {
			Joomla.submitform( pressbutton );
			return;
		}

		// do field validation
		if (form.last_name.value == ""){
			alert( "<?php echo JText::_( 'COM_TRACKS_VIEW_INDIVIDUAL_MUST_HAVE_A_LASTNAME', true ); ?>" );
		} else if (document.id('pic_s-preview') && document.id('pic_s-preview').width > 100) {
			alert("Face photo is too big, please crop using the edit button");
			return false;
		} else if (document.id('pic-preview') && document.id('pic-preview').width > 250) {
			alert("Tall photo is too big, please crop using the edit button");
			return false;
		} else if (document.id('pic_b-preview') && document.id('pic_b-preview').width > 960) {
			alert("Action photo is too big, please crop using the edit button");
			return false;
		}		
		else {
			Joomla.submitform( pressbutton );
		}
	}
</script>
<style type="text/css">
	table.paramlist td.paramlist_key {
		width: 92px;
		text-align: left;
		height: 30px;
	}
</style>


<div id="loading" style="visibility:hidden;position:fixed;top:0;left:50%;z-index:200;margin-left:-480px;width:960px;height:100%;text-align:center;" class="transparent"><div style="padding:10px;height:100%;" class="transparent"><div style="padding:10px;height:100%;" class="transparent"><h1 style="line-height:500px;" class="transparent">UPLOADING</h1></div></div></div>
  
  
<div class="transparent blackbackground" style="padding:10px;">


<div style="float:right;">
<a class="uibutton" href="<?php echo JRoute::_( TracksHelperRoute::getIndividualRoute($this->object->id) ); ?>">Back to your profile</a>
</div>
<p>No fields are mandatory, fill out as much as you'd like your fans to see.<br><p><br>

<form enctype="multipart/form-data" action="index.php" method="post" name="individualform" id="individualform">

<h1 class="transparent">First name:
       <input type="hidden" name="first_name" id="first_name" size="32" maxlength="40" value="<?php echo $this->object->first_name; ?>" />
       <?php echo $this->object->first_name; ?>
</h1>

<h1 class="transparent">Last name:
       <input type="hidden" name="last_name" id="first_name" size="32" maxlength="40" value="<?php echo $this->object->last_name; ?>" />
        <?php echo $this->object->last_name; ?>
</h1>

<h1 class="transparent">Nickname
        <input class="text_area" type="text" name="nickname" id="nickname" size="20" maxlength="20" value="<?php echo $this->object->nickname; ?>" />
</h1>

<h1 class="transparent">
        <input class="" type="radio" name="gender" id="gender" value="1" <?php echo ($this->object->gender == "1" ? "CHECKED" : ""); ?> /> male
        <input class="" type="radio" name="gender" id="gender" value="2" <?php echo ($this->object->gender == "2" ? "CHECKED" : ""); ?> /> female
</h1>

<h1 class="transparent">Country you were born in
        <?php echo $this->lists['countries']; ?>
</h1>

<h1 class="transparent">City you were you born in
        <input class="text_area" type="text" name="hometown" id="hometown" size="30" maxlength="40" value="<?php echo $this->object->hometown; ?>" />
</h1>

<h1 class="transparent">Center map to where you live - so in the future you can find nearby competitions and riders<br>(use mouse wheel to zoom in/out)<br>
        <input class="lat" type="hidden" name="lat" id="lat" size="20" maxlength="20" value="<?php echo $this->object->lat; ?>" />
        <input class="long" type="hidden" name="long" id="long" size="20" maxlength="20" value="<?php echo $this->object->long; ?>" />

<!--  GOOGLE MAPS -->
    <div id="bd">
        <div id="gmap"></div>
    </div>
</h1>

<p>

<h1 class="transparent">Team
        <input class="text_area" type="text" name="team_other" id="team_other" size="20" maxlength="20" value="<?php echo $this->object->team_other; ?>" />
</h1>

<h1 class="transparent">Racing number
        <input class="text_area" type="text" name="racing_number" id="racing_number" size="3" maxlength="3" value="<?php echo $this->object->racing_number; ?>" />
</h1>

<h1 class="transparent">Video channel (YouTube, Vimeo, etc.)
        <input class="text_area" type="text" name="video_site" id="video_site" size="40" maxlength="60" value="<?php echo $this->object->video_site; ?>" />
</h1>

    <?php if ($this->user->authorise('core.manage', 'com_tracks')): ?>
        User: <?php echo $this->lists['users']; ?>
    <?php endif; ?>

<h1 class="transparent">Official site (not personal facebook profile)
        <input class="text_area" type="text" name="web_site" id="web_site" size="40" maxlength="60" value="<?php echo $this->object->web_site; ?>" />
</h1>


<h1 class="transparent">Your hull</h1>
        <input class="" type="radio" name="hull" id="hull" value="Blowsion"  <?php echo ($this->object->hull == "Blowsion" ? "CHECKED" : ""); ?>  />Blowsion<br>
        <input class="" type="radio" name="hull" id="hull" value="Benelli"  <?php echo ($this->object->hull == "Benelli" ? "CHECKED" : ""); ?>  />Benelli<br>
        <input class="" type="radio" name="hull" id="hull" value="B.O.B."  <?php echo ($this->object->hull == "B.O.B." ? "CHECKED" : ""); ?>  />B.O.B.<br>
        <input class="" type="radio" name="hull" id="hull" value="Bun"  <?php echo ($this->object->hull == "Bun" ? "CHECKED" : ""); ?>  />Bun<br>
        <input class="" type="radio" name="hull" id="hull" value="EME"  <?php echo ($this->object->hull == "EME" ? "CHECKED" : ""); ?>  />EME<br>
        <input class="" type="radio" name="hull" id="hull" value="Force"  <?php echo ($this->object->hull == "Force" ? "CHECKED" : ""); ?>  />Force<br>
        <input class="" type="radio" name="hull" id="hull" value="Hydrospace"  <?php echo ($this->object->hull == "Hydrospace" ? "CHECKED" : ""); ?>  />Hydrospace<br>
        <input class="" type="radio" name="hull" id="hll" value="Kawasaki"  <?php echo ($this->object->hull == "Kawasaki" ? "CHECKED" : ""); ?>  />Kawasaki<br>
        <input class="" type="radio" name="hull" id="hull" value="Krash"  <?php echo ($this->object->hull == "Krash" ? "CHECKED" : ""); ?>  />Krash<br>
        <input class="" type="radio" name="hull" id="hull" value="Lenzi"  <?php echo ($this->object->hull == "Lenzi" ? "CHECKED" : ""); ?>  />Lenzi<br>
        <input class="" type="radio" name="hull" id="hull" value="Lightweight"  <?php echo ($this->object->hull == "Lightweight" ? "CHECKED" : ""); ?>  />Lightweight<br>
        <input class="" type="radio" name="hull" id="hull" value="Polaris"  <?php echo ($this->object->hull == "Polaris" ? "CHECKED" : ""); ?>  />Polaris<br>
        <input class="" type="radio" name="hull" id="hull" value="RRP"  <?php echo ($this->object->hull == "RRP" ? "CHECKED" : ""); ?>  />Rickter<br>
        <input class="" type="radio" name="hull" id="hull" value="Sea-Doo"  <?php echo ($this->object->hull == "Sea-Doo" ? "CHECKED" : ""); ?>  />Sea-Doo<br>
        <input class="" type="radio" name="hull" id="hull" value="SuperFreak"  <?php echo ($this->object->hull == "SuperFreak" ? "CHECKED" : ""); ?>  />SuperFreak<br>
        <input class="" type="radio" name="hull" id="hull" value="Trinity"  <?php echo ($this->object->hull == "Trinity" ? "CHECKED" : ""); ?>  />Trinity<br>
        <input class="" type="radio" name="hull" id="hull" value="Wamiltons"  <?php echo ($this->object->hull == "Wamiltons" ? "CHECKED" : ""); ?>  />Wamiltons<br>
        <input class="" type="radio" name="hull" id="hull" value="Water Craft Factory"  <?php echo ($this->object->hull == "Water Craft Factory" ? "CHECKED" : ""); ?>  />Water Craft Factory<br>
        <input class="" type="radio" name="hull" id="hull" value="Water Dawg Kustomz"  <?php echo ($this->object->hull == "Water Dawg Kustomz" ? "CHECKED" : ""); ?> />Water Dawg Kustomz<br>
        <input class="" type="radio" name="hull" id="hull" value="XFT"  <?php echo ($this->object->hull == "XFT" ? "CHECKED" : ""); ?>  />XFT<br>
        <input class="" type="radio" name="hull" id="hull" value="XScream"  <?php echo ($this->object->hull == "XScream" ? "CHECKED" : ""); ?>  />XScream<br>
        <input class="" type="radio" name="hull" id="hull" value="Yamaha"  <?php echo ($this->object->hull == "Yamaha" ? "CHECKED" : ""); ?> />Yamaha<br>
        <input class="" type="radio" name="hull" id="hull" value="Zapata"  <?php echo ($this->object->hull == "Zapata" ? "CHECKED" : ""); ?> />Zapata<br>
<p style="clear:both">

<h1 class="transparent">Your motor</h1>
        <input class="" type="radio" name="motor" id="motor" value="Benelli"  <?php echo ($this->object->motor == "Benelli" ? "CHECKED" : ""); ?>  />Benelli<br>
        <input class="" type="radio" name="motor" id="motor" value="DASA"  <?php echo ($this->object->motor == "DASA" ? "CHECKED" : ""); ?>  />DASA<br>
        <input class="" type="radio" name="motor" id="motor" value="EME"  <?php echo ($this->object->motor == "EME" ? "CHECKED" : ""); ?>  />EME<br>
        <input class="" type="radio" name="motor" id="motor" value="Hydrospace"  <?php echo ($this->object->motor == "Hydrospace" ? "CHECKED" : ""); ?>  />Hydrospace<br>
        <input class="" type="radio" name="motor" id="motor" value="Kawasaki"  <?php echo ($this->object->motor == "Kawasaki" ? "CHECKED" : ""); ?>  />Kawasaki<br>
        <input class="" type="radio" name="motor" id="motor" value="Polaris"  <?php echo ($this->object->motor == "Polaris" ? "CHECKED" : ""); ?>  />Polaris<br>
        <input class="" type="radio" name="motor" id="motor" value="Webber"  <?php echo ($this->object->motor == "Webber" ? "CHECKED" : ""); ?>  />Webber<br>
        <input class="" type="radio" name="motor" id="motor" value="XScream"  <?php echo ($this->object->motor == "XScream" ? "CHECKED" : ""); ?>  />XScream<br>
        <input class="" type="radio" name="motor" id="motor" value="Yamaha"  <?php echo ($this->object->motor == "Yamaha" ? "CHECKED" : ""); ?>  />Yamaha<br>
<p style="clear:both">


<h1 class="transparent">Your sponsors</h1>


				<?php foreach ((array) $this->lists['sponsor'] as $opt): ?>

        <div style="float:left;width:225px;">
				<input class="inputbox checkbox" type="checkbox" name="sponsors[]"
				       value="<?php echo $opt->value; ?>"
				       <?php echo (in_array($opt->value, $this->sponsors) ? ' checked="checked"' : ''); ?>/> <label><?php echo $opt->text; ?></label>
				</div>

				<?php endforeach; ?>

        <div style="float:left;clear:both;">
				<input class="inputbox checkbox" type="checkbox" name="has_other_sponsor"
				       value="-1" id="has_other_sponsor"
				       <?php echo ($this->object->sponsor_other ? ' checked="checked"' : ' onClick="showDiv()"'); ?>/> <label><?php echo JText::_('COM_TRACKS_SPONSOR_OTHER'); ?></label>


                <SCRIPT>
                function showDiv(name) {
                name="otherfield";
                if (document.getElementById) {  document.getElementById(name).style.visibility = 'visible'; }    // DOM3 = IE5, NS6
                  else
                  {  if (document.layers) { document.eval(name).visibility = 'visible'; }      // Netscape 4
                    else  { document.all.eval(name).style.visibility = 'visible'; }        // IE 4
                  }
                }
                </SCRIPT>
                <div id="otherfield" style="visibility:hidden;clear:both;">				<input name="sponsor_other" id="sponsor_other" size=50
            				       value="<?php echo $this->object->sponsor_other; ?>"
            				       <?php /* echo (empty($this->object->sponsor_other) ? ' disabled="disabled"' : ''); */?>/>
                </div>
            </div>
<p style="clear:both">



<h1 class="transparent">Face photo
        <input class="inputbox" name="picture_small" id="picture_small" type="file" onChange="document.getElementById('loading').style.visibility='visible';form.submit();" />
<script>
  var picture_small='<?php echo $this->object->picture_small ?>';
  picture_small = picture_small.slice(29,picture_small.search('.jpg')) + ".jpg";
//  document.write('<a target="_blank" class="uibutton" href="http://xjetski.com/plugins/system/phpimageeditor/index.php?isadmin=true&imagesrc=../../../' + picture_small + '&language=en-GB">Edit</a>');
  document.write('<a target="_blank" class="uibutton" href="http://xjetski.com/phpimageeditor/index.php?c=<?php echo substr($this->object->first_name,-2); ?>&imagesrc=../' + picture_small + '&s=1&u=<?php echo $this->object->first_name; ?>">Edit</a>');
</script>
</h1>

<IMG width=100 height=12 SRC="http://xjetski.com/templates/sainttropez-fjt/images/measure_100.png"><br>
        <?php echo $this->object->picture_small; ?>
        <p>

<h1 class="transparent">Tall (full-body) photo
        <input class="inputbox" name="picture" id="picture" type="file"  onChange="document.getElementById('loading').style.visibility='visible';form.submit();"/>
<script>
  var picture='<?php echo $this->object->picture ?>';
  picture = picture.slice(29,picture.search('.jpg')) + ".jpg";
//  document.write('<a target="_blank" class="uibutton" href="http://xjetski.com/plugins/system/phpimageeditor/index.php?isadmin=true&imagesrc=../../../' + picture + '&language=en-GB">Edit</a>');
  document.write('<a target="_blank" class="uibutton" href="http://xjetski.com/phpimageeditor/index.php?c=<?php echo substr($this->object->first_name,-2); ?>&imagesrc=../' + picture + '&s=2&u=<?php echo $this->object->first_name; ?>">Edit</a>');
</script>
</h1>

<IMG width=250 height=12 SRC="http://xjetski.com/templates/sainttropez-fjt/images/measure_250.png"><br>
        <?php echo $this->object->picture; ?>
        <p>
        
<h1 class="transparent">Action photo (for background)
        <input class="inputbox" name="picture_background" id="picture_background" type="file"  onChange="document.getElementById('loading').style.visibility='visible';form.submit();"/>
<script>
  var personalbgd='<?php echo $this->object->picture_background ?>';
  personalbgd = personalbgd.slice(29,personalbgd.search('.jpg')) + ".jpg";
//  document.write('<a target="_blank" class="uibutton" href="http://xjetski.com/plugins/system/phpimageeditor/index.php?isadmin=true&imagesrc=../../../' + personalbgd + '&language=en-GB">Edit</a>');
  document.write('<a target="_blank" class="uibutton" href="http://xjetski.com/phpimageeditor/index.php?c=<?php echo substr($this->object->first_name,-2); ?>&imagesrc=../' + personalbgd + '&s=3&u=<?php echo $this->object->first_name; ?>">Edit</a>');
</script>
</h1>

<IMG width=930 height=12 SRC="http://xjetski.com/templates/sainttropez-fjt/images/measure_960.png"><br>
        <?php echo $this->object->picture_background; ?>
        <p>





<div style="text-align:center;clear:both;margin-top:20px;">
<button class="uibutton" type="submit" onclick="Joomla.submitbutton( this.form );return false;">SAVE PROFILE</button>

<input type="hidden" name="option" value="com_tracks" />
<input type="hidden" name="controller" value="individual" />
<input type="hidden" name="i" value="<?php echo $this->object->id; ?>" />    
<input type="hidden" name="task" value="save" />
<?php if (!$this->user->authorise('core.manage', 'com_tracks')): ?>
<input type="hidden" name="user_id" value="<?php echo $this->user->id; ?>" />  
<?php endif; ?>

<?php echo JHTML::_( 'form.token' ); ?>
</form>
</div>

</div>




