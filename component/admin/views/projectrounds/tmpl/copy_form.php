<?php
/**
* @version    $Id: assign.php 142 2008-06-10 17:00:28Z julienv $
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

<?php
$user 	= JFactory::getUser();
$model = $this->getModel();

RHelperAsset::load('tracksbackend.css');

JHTML::_('behavior.tooltip');
?>
<script type="text/javascript">
  Joomla.submitbutton = function (pressbutton) {
    if (pressbutton) {
      document.adminForm.task.value=pressbutton;
    }

    // do field validation
    if (pressbutton == "savecopy" && document.adminForm.project_id.value == "0"){
      alert( "<?php echo JText::_('COM_TRACKS_YOU_MUST_SELECT_A_PROJECT', true ); ?>" );
    } else {
	    if (typeof document.adminForm.onsubmit == "function") {
	      document.adminForm.onsubmit();
	    }
	    document.adminForm.submit();
    }
  }
</script>

<div id="tracksmain">

	<form action="index.php" method="post" name="adminForm" id="adminForm">
		<input type="hidden" name="option" value="com_tracks" />
		<input type="hidden" name="view" value="projectrounds" />
		<input type="hidden" name="task" value="" />
		<input type="hidden" name="boxchecked" value="0" />

	<div id="projectsel">
		<?php echo JText::_('COM_TRACKS_ASSIGN_TO') . JHTML::_('select.genericlist',  $model->getProjectsOptions(), 'project_id', 'class="inputbox"', 'value', 'text'); ?>
	</div>

	<div id="editcell">
		<table class="adminlist">
		<thead>
			<tr>
				<th width="5">
					<?php echo JText::_('COM_TRACKS_Id' ); ?>
				</th>
				<th class="title">
	        <?php echo JText::_('COM_TRACKS_Name' ); ?>
				</th>
			</tr>
		</thead>
		<tbody>
		<?php
		$k = 0;
		for ($i=0, $n = count( $this->items ); $i < $n; $i++)
		{
			$row = $this->items[$i];
			?>
			<tr class="<?php echo "row$k"; ?>">
				<td>
					<?php echo $row->id; ?>
					<input id="cb<?php echo $i; ?>" type="hidden" value="<?php echo $row->id; ?>" name="cid[]"/>
				</td>
				<td>
					<?php
					echo $row->name;
					?>
				</td>
			</tr>
			<?php
			$k = 1 - $k;
		}
		?>
		</tbody>
		</table>
	</div>

</form>
</div>
