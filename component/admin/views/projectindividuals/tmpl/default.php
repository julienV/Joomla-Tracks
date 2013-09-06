<?php
/**
* @version    $Id: default.php 33 2008-02-15 15:41:48Z julienv $
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

//Ordering allowed ?
$ordering = ($this->lists->order == 'obj.ordering');

JHTML::_('behavior.tooltip');
FOFTemplateUtils::addCSS('media://com_tracks/css/tracksbackend.css');

JToolBarHelper::title('Tracks &ndash; ' . JText::_('COM_TRACKS_Project_Participants' ), 'generic.png');
?>
<div id="tracksmain">

<form action="index.php" method="post">
	<input type="hidden" name="p" value="<?php echo $this->project_id; ?>" />
	<table>
		<tr>
			<td class="hasTip" title="<?php echo JText::_('COM_TRACKS_QUICK_ADD').'::'.JText::_('COM_TRACKS_QUICK_ADD_TIP'); ?>"><?php echo JText::_('COM_TRACKS_QUICK_ADD'); ?>:</td>
			<td><input type="text" name="quickadd" id="quickadd" /></td>
			<td><input type="hidden" id="individualid" name="individualid" value=""><input type="submit" name="submit2" id="submit2" value="<?php echo JText::_('COM_TRACKS_ADD_PARTICIPANT'); ?>" /></td>
		</tr>
	</table>
	<input name="option" type="hidden" value="com_tracks"/>
	<input name="controller" type="hidden" value="quickadd"/>
	<input name="task" type="hidden" value="addpi"/>
</form>

<form action="<?php echo $this->request_url; ?>" method="post" name="adminForm" id="adminForm">
	<input type="hidden" name="view" value="projectindividuals" />
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="boxchecked" value="0" />
	<input type="hidden" name="filter_order" value="<?php echo $this->lists->order; ?>" />
	<input type="hidden" name="filter_order_Dir" value="" />
	<input type="hidden" name="<?php echo JFactory::getSession()->getFormToken();?>" value="1" />

	<table>
		<tr>
			<td align="left" width="100%">
				<?php echo JText::_('COM_TRACKS_Filter' ); ?>:
				<input type="text" name="search" id="search" value="<?php echo $model->getState('search'); ?>" class="text_area" onchange="document.adminForm.submit();" />
				<button onclick="this.form.submit();"><?php echo JText::_('COM_TRACKS_Go' ); ?></button>
				<button onclick="document.getElementById('search').value='';this.form.submit();"><?php echo JText::_('COM_TRACKS_Reset' ); ?></button>
			</td>
		</tr>
	</table>
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
	      <th width="5">
	        <?php echo JHTML::_('grid.sort',  'COM_TRACKS_Number', 'number', $this->lists->order_Dir, $this->lists->order ); ?>
	      </th>
				<th class="title">
					<?php echo JHTML::_('grid.sort',  'COM_TRACKS_Name', 'name', $this->lists->order_Dir, $this->lists->order ); ?>
				</th>
				<th class="title">
					<?php echo JHTML::_('grid.sort',  'COM_TRACKS_Team', 'team', $this->lists->order_Dir, $this->lists->order ); ?>
				</th>
				<th width="1%" nowrap="nowrap">
					<?php echo JHTML::_('grid.sort',  'ID', 'id', $this->lists->order_Dir, $this->lists->order ); ?>
				</th>
			</tr>
		</thead>
		<tfoot>
			<tr>
				<td colspan="6">
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

			$link 	= JRoute::_( 'index.php?option=com_tracks&view=projectindividual&id='. $row->id );

			$checked 	= JHTML::_('grid.checkedout',   $row, $i );

			?>
			<tr class="<?php echo "row$k"; ?>">
				<td>
					<?php echo $this->pagination->getRowOffset( $i ); ?>
				</td>
				<td>
					<?php echo $checked; ?>
				</td>
	      <td>
	        <?php echo $row->number; ?>
	      </td>
				<td>
					<?php
					if (  JTable::isCheckedOut($user->get('id'), $row->checked_out ) ) {
						echo $row->last_name.', '.$row->first_name;
					} else {
					?>
						<a href="<?php echo $link; ?>" title="<?php echo JText::_('COM_TRACKS_Edit_Project_Participant' ); ?>">
							<?php echo $row->last_name. ', ' . $row->first_name; ?></a>
					<?php
					}
					?>
				</td>
	      <td>
					<?php echo $row->team_name; ?>
				</td>
				<td align="center">
					<?php echo $row->id; ?>
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
