<?php
/**
* @version    $Id: default.php 68 2008-04-24 17:46:02Z julienv $ 
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
$user 	=& JFactory::getUser();

//Ordering allowed ?
$ordering = ($this->lists['order'] == 'p.ordering');

JHTML::_('behavior.tooltip');
?>
<form action="<?php echo $this->request_url; ?>" method="post"
	name="adminForm">
<table>
	<tr>
		<td align="right" width="100%"><?php echo JText::_( 'Filter' ); ?>: <input
			type="text" name="search" id="search"
			value="<?php echo $this->lists['search'];?>" class="text_area"
			onchange="document.adminForm.submit();" />
		<button onclick="this.form.submit();"><?php echo JText::_( 'Go' ); ?></button>
		<button
			onclick="document.getElementById('search').value='';this.form.submit();"><?php echo JText::_( 'Reset' ); ?></button>
		</td>
		<td nowrap="nowrap"><?php
		echo $this->lists['state'];
		?></td>
	</tr>
</table>
<div id="editcell">
<table class="adminlist">
	<thead>
		<tr>
			<th width="5"><?php echo JText::_( 'NUM' ); ?></th>
			<th width="20"><input type="checkbox" name="toggle" value=""
				onclick="checkAll(<?php echo count( $this->items ); ?>);" /></th>
			<th class="title" nowrap="nowrap"><?php echo JHTML::_('grid.sort',  'Name', 'p.name', $this->lists['order_Dir'], $this->lists['order'] ); ?>
			</th>
			<th class="title" nowrap="nowrap"><?php echo JHTML::_('grid.sort',  'Competition', 'l.name', $this->lists['order_Dir'], $this->lists['order'] ); ?>
			</th>
			<th class="title" nowrap="nowrap"><?php echo JHTML::_('grid.sort',  'Season', 's.name', $this->lists['order_Dir'], $this->lists['order'] ); ?>
			</th>
			<th width="5%" nowrap="nowrap"><?php echo JHTML::_('grid.sort',  'Published', 'p.published', $this->lists['order_Dir'], $this->lists['order'] ); ?>
			</th>
			<th width="8%" nowrap="nowrap"><?php echo JHTML::_('grid.sort',  'Order', 'p.ordering', $this->lists['order_Dir'], $this->lists['order'] ); ?>
			<?php echo JHTML::_('grid.order',  $this->items ); ?></th>
			<th width="1%" nowrap="nowrap"><?php echo JHTML::_('grid.sort',  'ID', 'p.id', $this->lists['order_Dir'], $this->lists['order'] ); ?>
			</th>
		</tr>
	</thead>
	<tfoot>
		<tr>
			<td colspan="9"><?php echo $this->pagination->getListFooter(); ?></td>
		</tr>
	</tfoot>
	<tbody>
	<?php
	$k = 0;
	for ($i=0, $n=count( $this->items ); $i < $n; $i++)
	{
		$row = &$this->items[$i];

		$link 	= JRoute::_( 'index.php?option=com_tracks&controller=project&task=edit&cid[]='. $row->id );
		$link_select 	= JRoute::_( 'index.php?option=com_tracks&controller=project&task=select&cid[]='. $row->id );

		$checked 	= JHTML::_('grid.checkedout',   $row, $i );
		$published 	= JHTML::_('grid.published', $row, $i );

		?>
		<tr class="<?php echo "row$k"; ?>">
			<td><?php echo $this->pagination->getRowOffset( $i ); ?></td>
			<td><?php echo $checked; ?></td>
			<td><?php
			if (  JTable::isCheckedOut($this->user->get ('id'), $row->checked_out ) ) {
				echo $row->title;
			} else 
      {
				?> <a href="<?php echo $link; ?>"
				title="<?php echo JText::_( 'Edit Project' ); ?>"> <?php echo $row->name; ?></a> | 
				<a href="<?php echo $link_select; ?>"
				title="<?php echo JText::_( 'Select project' ); ?>"> <?php echo JText::_( 'select' ); ?></a>
				<?php
      }
?></td>
			<td><?php echo $row->competition;?></td>
			<td><?php echo $row->season;?></td>
			<td align="center"><?php echo $published;?></td>
			<td class="order"><span><?php echo $this->pagination->orderUpIcon( $i, $i > 0 , 'orderup', 'Move Up', true ); ?></span>
			<span><?php echo $this->pagination->orderDownIcon( $i, $n, $i < $n, 'orderdown', 'Move Down', true ); ?></span>
			<?php $disabled = true ?  '' : 'disabled="disabled"'; ?> <input
				type="text" name="order[]" size="5"
				value="<?php echo $row->ordering;?>" <?php echo $disabled; ?>
				class="text_area" style="text-align: center" /></td>
			<td align="center"><?php echo $row->id; ?></td>
		</tr>
		<?php
		$k = 1 - $k;
}
?>
	</tbody>
</table>
</div>

<input type="hidden" name="controller" value="project" /> 
<input type="hidden" name="task" value="" />
<input type="hidden" name="boxchecked" value="0" />
<input type="hidden" name="filter_order" value="<?php echo $this->lists['order']; ?>" />
<input type="hidden" name="filter_order_Dir" value="" />
</form>

