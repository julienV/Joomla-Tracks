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
$model = $this->getModel();

FOFTemplateUtils::addCSS("media://com_tracks/css/tracksbackend.css");

//Ordering allowed ?
$ordering = ($this->lists->order == 'obj.ordering');

JHTML::_('behavior.tooltip');
?>

<script language="javascript" type="text/javascript">
Joomla.submitbutton = function (pressbutton)
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
	<form name="adminForm" id="adminForm" action="index.php" method="post">
		<input type="hidden" name="option" id="option" value="com_tracks" />
		<input type="hidden" name="view" id="view" value="subrounds" />
		<input type="hidden" name="task" id="task" value="browse" />
		<input type="hidden" name="projectround_id" id="projectround_id" value="<?php echo JFactory::getApplication()->input->getInt('projectround_id', 0); ?>" />
		<input type="hidden" name="boxchecked" id="boxchecked" value="0" />
		<input type="hidden" name="hidemainmenu" id="hidemainmenu" value="0" />
		<input type="hidden" name="filter_order" id="filter_order" value="<?php echo $this->lists->order ?>" />
		<input type="hidden" name="filter_order_Dir" id="filter_order_Dir" value="<?php echo $this->lists->order_Dir ?>" />
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
			<th class="title">
				<?php echo JHTML::_('grid.sort',  'COM_TRACKS_Type', 'sr.name', $this->lists->order_Dir, $this->lists->order ); ?>
			</th>
      <th class="title">
        <?php echo JText::_('COM_TRACKS_Results' ); ?>
      </th>
      <th width="5%" nowrap="nowrap">
        <?php echo JHTML::_('grid.sort',  'Published', 'obj.published', $this->lists->order_Dir, $this->lists->order ); ?>
      </th>
      <th width="8%" nowrap="nowrap">
        <?php echo JHTML::_('grid.sort',  'Order', 'obj.ordering', $this->lists->order_Dir, $this->lists->order ); ?>
        <?php echo JHTML::_('grid.order',  $this->items ); ?>
      </th>
			<th width="1%" nowrap="nowrap">
				<?php echo JHTML::_('grid.sort',  'ID', 'obj.id', $this->lists->order_Dir, $this->lists->order ); ?>
			</th>
		</tr>
	</thead>
	<tfoot>
		<tr>
			<td colspan="7">
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

		$link 	= JRoute::_( 'index.php?option=com_tracks&view=subround&id=' . $row->id . '&projectround_id=' . $model->getState('projectround_id'));
        $link_results = JRoute::_( 'index.php?option=com_tracks&view=subroundresults&srid='. $row->id );

		$checked 	= JHTML::_('grid.checkedout',   $row, $i );
		$published  = JHTML::_('grid.published', $row, $i );
		?>
		<tr class="<?php echo "row$k"; ?>">
			<td>
				<?php echo $this->pagination->getRowOffset( $i ); ?>
			</td>
			<td>
				<?php echo $checked; ?>
			</td>
			<td>
				<?php
				if ( JTable::isCheckedOut($user->get ('id'), $row->checked_out ) ) {
					echo $row->name;
				} else {
				  ?>
					<a href="<?php echo $link; ?>" title="<?php echo JText::_('COM_TRACKS_Edit_Subround' ); ?>">
						<?php echo $row->name; ?>
					</a>
				  <?php
				}
				?>
			</td>
      <td>
        <?php
        if (  JTable::isCheckedOut($user->get ('id'), $row->checked_out ) ) {
          echo JText::_('COM_TRACKS_edit_locked' );
        } else {
        ?>
          <a href="<?php echo $link_results; ?>" title="<?php echo JText::_('COM_TRACKS_Edit_results' ); ?>">
            <?php echo JText::_('COM_TRACKS_Edit_results' ); ?></a>
        <?php
        }
        ?>
      </td>
      <td align="center"><?php echo $published;?></td>
      <td class="order">
        <span><?php echo $this->pagination->orderUpIcon( $i, $i > 0 , 'orderup', 'Move Up', $ordering ); ?></span>
        <span><?php echo $this->pagination->orderDownIcon( $i, $n, $i < $n, 'orderdown', 'Move Down', $ordering ); ?></span>
        <?php $disabled = true ?  '' : 'disabled="disabled"'; ?>
        <input type="text" name="order[]" size="5" value="<?php echo $row->ordering;?>" <?php echo $disabled ?> class="text_area" style="text-align: center" />
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
