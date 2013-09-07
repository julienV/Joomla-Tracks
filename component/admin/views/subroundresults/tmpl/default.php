<?php
/**
* @version    $Id: default.php 127 2008-06-06 02:43:26Z julienv $
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

//Ordering allowed ?
//$ordering = ($this->lists->order == 'obj.ordering');

JHTML::_('behavior.mootools');
JHTML::_('behavior.tooltip');

$model = $this->getModel();
FOFTemplateUtils::addCSS("media://com_tracks/css/tracksbackend.css");

FOFTemplateUtils::addJS('media://com_tracks/js/autocompleter/1_2/Autocompleter.js');
FOFTemplateUtils::addJS('media://com_tracks/js/autocompleter/1_2/Autocompleter.Local.js');
FOFTemplateUtils::addJS('media://com_tracks/js/autocompleter/1_2/Autocompleter.Request.js');
FOFTemplateUtils::addJS('media://com_tracks/js/autocompleter/1_2/Observer.js');
FOFTemplateUtils::addJS('media://com_tracks/js/quickadd1_2.js');
FOFTemplateUtils::addCSS('media://com_tracks/css/Autocompleter1_2.css');
?>
<style>
.search-item {
    font:normal 11px tahoma, arial, helvetica, sans-serif;
    padding:3px 10px 3px 10px;
    border:1px solid #fff;
    border-bottom:1px solid #eeeeee;
    white-space:normal;
    color:#555;
}
.search-item h3 {
    display:block;
    font:inherit;
    font-weight:bold;
    color:#222;
}

.search-item h3 span {
    float: right;
    font-weight:normal;
    margin:0 0 5px 5px;
    width:100px;
    display:block;
    clear:none;
}
</style>

<script type="text/javascript">
Joomla.submitbutton = function(pressbutton)
{
  if (pressbutton == "saveranks"){
  	checkAll_button( <?php echo count($this->items)-1; ?>, pressbutton );
  }
  else {
	  Joomla.submitform(pressbutton);
  }
}
</script>

<div id="tracksmain">
<form action="index.php?option=com_tracks&view=quickadd&task=add" method="post">
	<input type="hidden" name="subround_id" value="<?php echo $model->getState('subround_id'); ?>" />

	<table>
		<tr>
			<td class="hasTip" title="<?php echo JText::_('COM_TRACKS_QUICK_ADD').'::'.JText::_('COM_TRACKS_QUICK_ADD_TIP'); ?>"><?php echo JText::_('COM_TRACKS_QUICK_ADD'); ?>:</td>
			<td><input type="text" name="quickadd" id="quickadd" /></td>
			<td><input type="hidden" id="individualid" name="individualid" value=""><input type="submit" name="submit2" id="submit2" value="<?php echo JText::_('COM_TRACKS_ADD_PARTICIPANT'); ?>" /></td>
		</tr>
	</table>
</form>

<br />

<form action="<?php echo $this->request_url; ?>" method="post" name="adminForm" id="adminForm">
	<input type="hidden" name="view" value="subroundresults" />
	<input type="hidden" name="subround_id" value="<?php echo $model->getState('subround_id'); ?>" />
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="boxchecked" value="0" />
	<input type="hidden" name="filter_order" value="<?php echo $this->lists->order; ?>" />
	<input type="hidden" name="filter_order_Dir" value="" />
	<input type="hidden" name="<?php echo JFactory::getSession()->getFormToken();?>" value="1" />

	<div id="editcell">
		<table class="adminlist">
		<thead>
			<tr>
				<th width="5">
					<?php echo JText::_('COM_TRACKS_NUM' ); ?>
				</th>
				<th width="20">
					<input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count( $this->items ); ?>);" />
				</th>
	      <th width="5%">
	        <?php echo JHTML::_('grid.sort',  'COM_TRACKS_Number', 'number', $this->lists->order_Dir, $this->lists->order ); ?>
	      </th>
				<th class="title">
					<?php echo JHTML::_('grid.sort',  'COM_TRACKS_Participant', 'participant', $this->lists->order_Dir, $this->lists->order ); ?>
				</th>
				<th class="title">
					<?php echo JHTML::_('grid.sort',  'COM_TRACKS_Team', 'team', $this->lists->order_Dir, $this->lists->order ); ?>
				</th>
	      <th class="title">
	        <?php echo JHTML::_('grid.sort',  'COM_TRACKS_Performance', 'performance', $this->lists->order_Dir, $this->lists->order ); ?>
	      </th>
				<th width="5%">
					<?php echo JHTML::_('grid.sort',  'COM_TRACKS_Rank', 'rank', $this->lists->order_Dir, $this->lists->order ); ?>
				</th>
	      <th width="5%">
					<?php echo JHTML::_('grid.sort',  'COM_TRACKS_Bonus_points', 'bonus_points', $this->lists->order_Dir, $this->lists->order ); ?>
	      </th>
			</tr>
		</thead>
		<tfoot>
			<tr>
				<td colspan="9">
					<?php echo $this->pagination->getListFooter(); ?>
				</td>
			</tr>
		</tfoot>
		<tbody>
		<?php
		$k = 0;

		for ($i=0, $n=count( $this->items ); $i < $n; $i++)
		{
			$row = $this->items[$i];

			$link 	= JRoute::_( 'index.php?option=com_tracks&view=subroundresult&id=' . $row->id . '&subround_id=' . $row->subround_id);

			$checked 	= JHTML::_('grid.checkedout',   $row, $i );
			?>
			<tr class="<?php echo "row$k"; ?>">
				<td>
					<?php echo $this->pagination->getRowOffset( $i ); ?>
					<input type="hidden"
					       id="ind<?php echo $i; ?>" name="individual[]"
					       value="<?php echo $row->individual_id; ?>" />
					<input type="hidden"
					       id="team<?php echo $i; ?>" name="team[]"
					       value="<?php echo $row->team_id; ?>" />
				</td>
				<td>
					<?php echo $checked; ?>
				</td>
	      <td align="center"><?php echo $row->number; ?></td>
				<td>
					<?php
					if ( JTable::isCheckedOut($user->get('id'), $row->checked_out ) ) {
						echo $row->last_name.', '.$row->first_name;
					} else {
					  ?>
						<a href="<?php echo $link; ?>" title="<?php echo JText::_('COM_TRACKS_Edit_Project_Participant' ); ?>">
							<?php echo $row->last_name. ', ' . $row->first_name; ?>
						</a>
					  <?php
					}
					?>
				</td>
				<td><?php echo $row->team_name; ?></td>
				<td align="center">
	        <input type="text" name="performance[]" size="20"
					       value="<?php echo $row->performance;?>" class="text_area"
					       style="text-align: center" />
				</td>
	      <td>
	        <input type="text" name="rank[]" size="3"
					       value="<?php echo $row->rank;?>" class="text_area"
					       style="text-align: center" />
	      </td>
				<td>
	        <input type="text" name="bonus_points[]" size="3"
					       value="<?php echo $row->bonus_points;?>" class="text_area"
					       style="text-align: center" />
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
