<?php
/**
 * @package     Tracks
 * @subpackage  Admin
 * @copyright   Tracks (C) 2008-2015 Julien Vonthron. All rights reserved.
 * @license     GNU General Public License version 2 or later
 */
defined('_JEXEC') or die('Restricted access');

JHtml::_('behavior.keepalive');
JHtml::_('rdropdown.init');
JHtml::_('rbootstrap.tooltip');
JHtml::_('rjquery.chosen', 'select');

$listOrder = $this->escape($this->state->get('list.ordering'));
$listDirn = $this->escape($this->state->get('list.direction'));

$user = JFactory::getUser();
$userId = $user->id;
$search = $this->state->get('filter.search');
?>
<script type="text/javascript">
	Joomla.submitbutton = function (pressbutton)
	{
		submitbutton(pressbutton);
	}
	submitbutton = function (pressbutton)
	{
		var form = document.adminForm;
		if (pressbutton)
		{
			form.task.value = pressbutton;
		}

		if (pressbutton == 'eventtypes.delete')
		{
			var r = confirm('<?php echo JText::_("COM_TRACKS_DELETE_CONFIRM")?>');
			if (r == true)    form.submit();
			else return false;
		}
		form.submit();
	}
</script>
<div class="well"><?php echo JText::_('COM_TRACKS_EVENTTYPES_EXPLANATION'); ?></div>
<form action="index.php?option=com_tracks&view=eventtypes" class="admin" id="adminForm" method="post" name="adminForm">
	<?php
	echo RLayoutHelper::render(
		'searchtools.default',
		array(
			'view' => $this,
			'options' => array(
				'searchField' => 'search',
				'searchFieldSelector' => '#filter_search',
				'limitFieldSelector' => '#list_fields_limit',
				'activeOrder' => $listOrder,
				'activeDirection' => $listDirn
			)
		)
	);
	?>
	<hr />
	<?php if (empty($this->items)) : ?>
		<div class="alert alert-info">
			<button type="button" class="close" data-dismiss="alert">&times;</button>
			<div class="pagination-centered">
				<h3><?php echo JText::_('COM_TRACKS_NOTHING_TO_DISPLAY'); ?></h3>
			</div>
		</div>
	<?php else : ?>
		<table class="table table-striped" id="table-items">
			<thead>
			<tr>
				<th width="10" align="center">
					<?php echo '#'; ?>
				</th>
				<th width="10">
					<?php if (version_compare(JVERSION, '3.0', 'lt')) : ?>
						<input type="checkbox" name="toggle" value="" onclick="Joomla.checkAll(this);" />
					<?php else : ?>
						<?php echo JHTML::_('grid.checkall'); ?>
					<?php endif; ?>
				</th>
				<?php if ($this->canEdit) : ?>
					<th width="1" nowrap="nowrap">
					</th>
				<?php endif; ?>
				<th class="title" width="auto">
					<?php echo JHTML::_('rsearchtools.sort', 'COM_TRACKS_NAME', 'obj.name', $listDirn, $listOrder); ?>
				</th>
				<th width="auto">
					<?php echo JText::_('COM_TRACKS_NOTE'); ?>
				</th>
				<th width="10">
					<?php echo JHTML::_('rsearchtools.sort', 'COM_TRACKS_count_in_rankings', 'obj.count_points', $listDirn, $listOrder); ?>
				</th>
				<th width="auto">
					<?php echo JText::_('COM_TRACKS_POINTS'); ?>
				</th>
				<th width="auto">
					<?php echo JText::_('COM_TRACKS_EVENTTYPE_ENABLE_STATS'); ?>
				</th>
				<th width="10">
					<?php echo JHTML::_('rsearchtools.sort', 'COM_TRACKS_ID', 'obj.id', $listDirn, $listOrder); ?>
				</th>
			</tr>
			</thead>
			<tbody>
			<?php $n = count($this->items); ?>
			<?php foreach ($this->items as $i => $row) : ?>
				<?php $orderkey = array_search($row->id, $this->ordering[0]); ?>
				<tr>
					<td>
						<?php echo $this->pagination->getRowOffset($i); ?>
					</td>
					<td>
						<?php echo JHtml::_('grid.id', $i, $row->id); ?>
					</td>
					<?php if ($this->canEdit) : ?>
						<td>
							<?php if ($row->checked_out) : ?>
								<?php
								$editor = JFactory::getUser($row->checked_out);
								$canCheckin = $row->checked_out == $userId || $row->checked_out == 0;
								echo JHtml::_('rgrid.checkedout', $i, $editor->name, $row->checked_out_time, 'eventtypes.', $canCheckin);
								?>
							<?php endif; ?>
						</td>
					<?php endif; ?>
					<td>
						<?php $itemTitle = JHTML::_('string.truncate', $row->name, 50, true, false); ?>
						<?php if (($row->checked_out) || (!$this->canEdit)) : ?>
							<?php echo $itemTitle; ?>
						<?php else : ?>
							<?php echo JHtml::_('link', 'index.php?option=com_tracks&task=eventtype.edit&id=' . $row->id, $itemTitle); ?>
						<?php endif; ?>
					</td>
					<td>
						<?php echo $row->note; ?>
					</td>
					<td>
						<?php echo $row->count_points; ?>
					</td>
					<td>
						<?php echo $row->points_attribution; ?>
					</td>
					<td>
						<?php echo $row->enable_stats ? JText::_('JYES') : JText::_('JNO'); ?>
					</td>
					<td>
						<?php echo $row->id; ?>
					</td>
				</tr>
			<?php endforeach; ?>
			</tbody>
		</table>
		<?php echo $this->pagination->getPaginationLinks(null, array('showLimitBox' => false)); ?>
	<?php endif; ?>
	<input type="hidden" name="task" value=""/>
	<input type="hidden" name="boxchecked" value="0"/>
	<?php echo JHtml::_('form.token'); ?>
</form>
