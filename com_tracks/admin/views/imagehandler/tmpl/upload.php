<?php
/**
* @version    $Id$ 
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

defined( '_JEXEC' ) or die( 'Restricted access' );
?>

<form method="post" action="<?php echo $this->request_url; ?>" enctype="multipart/form-data" name="adminForm">

<table class="noshow">
  	<tr>
		<td width="50%" valign="top">
		
				<?php if($this->ftp): ?>
				<fieldset class="adminform">
					<legend><?php echo JText::_('COM_TRACKS_FTP_TITLE'); ?></legend>

					<?php echo JText::_('COM_TRACKS_FTP_DESC'); ?>
					
					<?php if(JError::isError($this->ftp)): ?>
						<p><?php echo JText::_($this->ftp->message); ?></p>
					<?php endif; ?>

					<table class="adminform nospace">
						<tbody>
							<tr>
								<td width="120">
									<label for="username"><?php echo JText::_('COM_TRACKS_USERNAME'); ?>:</label>
								</td>
								<td>
									<input type="text" id="username" name="username" class="input_box" size="70" value="" />
								</td>
							</tr>
							<tr>
								<td width="120">
									<label for="password"><?php echo JText::_('COM_TRACKS_PASSWORD'); ?>:</label>
								</td>
								<td>
									<input type="password" id="password" name="password" class="input_box" size="70" value="" />
								</td>
							</tr>
						</tbody>
					</table>
				</fieldset>
			<?php endif; ?>

			<fieldset class="adminform">
			<legend><?php echo JText::_('COM_TRACKS_SELECT_IMAGE_UPLOAD' ); ?></legend>
			<table class="admintable" cellspacing="1">
				<tbody>
					<tr>
	          			<td>
 							<input class="inputbox" name="userfile" id="userfile" type="file" />
							<br /><br />
							<input class="button" type="submit" value="<?php echo JText::_('COM_TRACKS_UPLOAD') ?>" name="adminForm" />
    			       	</td>
      				</tr>
				</tbody>
			</table>
			</fieldset>

		</td>
        <td width="50%" valign="top">

			<fieldset class="adminform">
			<legend><?php echo JText::_('COM_TRACKS_ATTENTION' ); ?></legend>
			<table class="admintable" cellspacing="1">
				<tbody>
					<tr>
	          			<td>
 							<b><?php
 							echo JText::_('COM_TRACKS_TARGET_DIRECTORY' ).':'; ?></b>
							<?php	echo "/media/com_tracks/images/".$this->folder;	?><br />
							<b><?php echo JText::_('COM_TRACKS_IMAGE_FILESIZE' ).':'; ?></b> <?php echo $this->params->get('image_max_size', 120); ?> kb<br />

							<?php
							if ( isset($gd_info) ) {

								if (imagetypes() & IMG_PNG) {
									echo "<br /><font color='green'>".JText::_('COM_TRACKS_PNG_SUPPORT' )."</font>";
								} else {
									echo "<br /><font color='red'>".JText::_('COM_TRACKS_NO_PNG_SUPPORT' )."</font>";
								}
								if (imagetypes() & IMG_JPEG) {
									echo "<br /><font color='green'>".JText::_('COM_TRACKS_JPG_SUPPORT' )."</font>";
								} else {
									echo "<br /><font color='red'>".JText::_('COM_TRACKS_NO_JPG_SUPPORT' )."</font>";
								}
								if (imagetypes() & IMG_GIF) {
									echo "<br /><font color='green'>".JText::_('COM_TRACKS_GIF_SUPPORT' )."</font>";
								} else {
									echo "<br /><font color='red'>".JText::_('COM_TRACKS_NO_GIF_SUPPORT' )."</font>";
								}
							} else {
								echo "<br /><font color='green'>".JText::_('COM_TRACKS_PNG_SUPPORT' )."</font>";
								echo "<br /><font color='green'>".JText::_('COM_TRACKS_JPG_SUPPORT' )."</font>";
								echo "<br /><font color='green'>".JText::_('COM_TRACKS_GIF_SUPPORT' )."</font>";
							}
							?>
    			       	</td>
      				</tr>
				</tbody>
			</table>
			</fieldset>

		</td>
	</tr>
</table>

<?php if ( isset($gd_info) ) { ?>

<table class="noshow">
	<tr>
		<td>

			<fieldset class="adminform">
			<legend><?php echo JText::_('COM_TRACKS_ATTENTION' ); ?></legend>
			<table class="admintable" cellspacing="1">
				<tbody>
					<tr>
	          			<td align="center">
							<?php echo JText::_('COM_TRACKS_GD_WARNING' ); ?>
    			     	 </td>
      				</tr>
				</tbody>
			</table>
			</fieldset>

		</td>
	</tr>
</table>

<?php } ?>

<?php echo JHTML::_( 'form.token' ); ?>
<input type="hidden" name="option" value="com_tracks" />
<input type="hidden" name="controller" value="imagehandler" />
<input type="hidden" name="task" value="upload" />
<input type="hidden" name="folder" value="<?php echo $this->folder;?>" />
</form>