<?php
/**
* @version    $Id: default.php 140 2008-06-10 16:47:22Z julienv $ 
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
$ordering = ($this->lists['order'] == 'obj.ordering');
JHTML::_('behavior.tooltip');

$function	= JRequest::getCmd('function', 'jSelectIndividual');
?>

<form action="<?php echo JRoute::_('index.php?option=com_tracks&view=individuals&layout=modal&tmpl=component&function='.$function);?>" method="post" name="adminForm" id="adminForm">
	<table>
	<tr>
		<td align="left" width="100%">
			<?php echo JText::_('COM_TRACKS_Filter' ); ?>:
			<input type="text" name="search" id="search" value="<?php echo $this->lists['search'];?>" class="text_area" onchange="document.adminForm.submit();" />
			<button onclick="this.form.submit();"><?php echo JText::_('COM_TRACKS_Go' ); ?></button>
			<button onclick="document.getElementById('search').value='';this.form.submit();"><?php echo JText::_('COM_TRACKS_Reset' ); ?></button>
		</td>
	</tr>
	</table>
	
	<div id="editcell">
		<table class="adminlist">
		<thead>
			<tr>
				<th class="title">
					<?php echo JHTML::_('grid.sort',  'COM_TRACKS_LAST_NAME', 'obj.last_name', $this->lists['order_Dir'], $this->lists['order'] ); ?>
				</th>
				<th class="title">
					<?php echo JHTML::_('grid.sort',  'COM_TRACKS_FIRST_NAME', 'obj.first_name', $this->lists['order_Dir'], $this->lists['order'] ); ?>
				</th>
	      <th class="title" nowrap="nowrap"><?php echo JText::_('COM_TRACKS_ALIAS'); ?></th>
				<th width="1%" nowrap="nowrap">
					<?php echo JHTML::_('grid.sort',  'ID', 'obj.id', $this->lists['order_Dir'], $this->lists['order'] ); ?>
				</th>
			</tr>
		</thead>
		<tfoot>
			<tr>
				<td colspan="4">
					<?php echo $this->pagination->getListFooter(); ?>
				</td>
			</tr>
		</tfoot>
		<tbody>
		<?php
		$k = 0;
		for ($i=0, $n=count( $this->items ); $i < $n; $i++)
		{
			$row = &$this->items[$i];
			$json = new stdClass();
			$json->picture_small = $row->picture_small;
			JFactory::getDocument()->addScriptDeclaration('var tracksind'.$i.' = '.json_encode($json).";\n");
			?>
			<tr class="<?php echo "row$k"; ?>">
				<td>
					<a class="pointer" 
					   onclick="if (window.parent) window.parent.<?php echo $this->escape($function);?>('<?php 
					                    echo $row->id; ?>', '<?php 
					                    echo $this->escape(addslashes($row->last_name)); ?>', '<?php 
					                    echo $this->escape(addslashes($row->first_name)); ?>', tracksind<?php echo $i; ?>);"><?php echo $this->escape($row->last_name); ?></a>
				</td>
				<td>
					<?php
					echo $row->first_name;
					?>
				</td>
	      <td align="center"><?php echo $row->alias;?></td>
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
		
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="boxchecked" value="0" />
	<input type="hidden" name="filter_order" value="<?php echo $this->lists['order']; ?>" />
	<input type="hidden" name="filter_order_Dir" value="<?php echo $this->lists['order_Dir']; ?>" />
</form>
</div>