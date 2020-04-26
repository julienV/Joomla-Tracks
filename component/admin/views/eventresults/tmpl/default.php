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

RHelperAsset::load('eventresults.js');
RHelperAsset::load('tracksbackend.css');
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

		if (pressbutton == 'eventresults.delete')
		{
			var r = confirm('<?php echo JText::_("COM_TRACKS_DELETE_CONFIRM")?>');
			if (r == true)    form.submit();
			else return false;
		}
		form.submit();
	}
</script>
<form action="index.php?option=com_tracks&view=eventresults&event_id=<?php echo $this->state->get('event_id'); ?>" class="admin eventresults" id="adminForm" method="post" name="adminForm">
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
				<th width="30" nowrap="nowrap">
					<?php echo JHTML::_('rsearchtools.sort', 'COM_TRACKS_NUMBER', 'number', $listDirn, $listOrder); ?>
				</th>
				<th class="title" width="auto">
					<?php echo JHTML::_('rsearchtools.sort', 'COM_TRACKS_PARTICIPANT', 'participant', $listDirn, $listOrder); ?>
				</th>
				<th class="title" width="auto">
					<?php echo JHTML::_('rsearchtools.sort', 'COM_TRACKS_TEAM', 'team', $listDirn, $listOrder); ?>
				</th>
				<th width="auto">
					<?php echo JHTML::_('rsearchtools.sort', 'COM_TRACKS_PERFORMANCE', 'performance', $listDirn, $listOrder); ?>
				</th>
				<th width="auto">
					<?php echo JHTML::_('rsearchtools.sort', 'COM_TRACKS_RANK', 'rank', $listDirn, $listOrder); ?>
				</th>
				<th width="auto">
					<?php echo JHTML::_('rsearchtools.sort', 'COM_TRACKS_BONUS_POINTS', 'bonus_points', $listDirn, $listOrder); ?>
				</th>
			</tr>
			</thead>
			<tbody>
			<?php $n = count($this->items); ?>
			<?php foreach ($this->items as $i => $row) : ?>
				<tr>
					<td>
						<?php echo $this->pagination->getRowOffset($i); ?>
					</td>
					<td>
						<?php echo JHtml::_('grid.id', $i, $row->id); ?>
					</td>
					<td class="result result-number">
						<input type="text" name="number[<?= $row->id ?>]" value="<?php echo $row->number; ?>" size="5"/>
					</td>
					<td>
						<?php $itemTitle = $row->first_name ? $row->last_name . ', ' . $row->first_name : $row->last_name; ?>
						<?php echo JHtml::_('link', 'index.php?option=com_tracks&task=eventresult.edit&id=' . $row->id . '&event_id=' . $row->event_id, $itemTitle); ?>
						<?php if ($row->nickname): ?>
							<br><small><?= $row->nickname ?></small>
						<?php endif; ?>
					</td>
					<td>
						<?php echo $row->team; ?>
					</td>
					<td class="result result-performance">
						<input type="text" name="performance[<?= $row->id ?>]" value="<?php echo $row->performance; ?>"/>
					</td>
					<td class="result result-rank">
						<input type="text" name="rank[<?= $row->id ?>]" value="<?php echo $row->rank; ?>"/>
					</td>
					<td class="result result-bonus">
						<input type="text" name="bonus_points[<?= $row->id ?>]" value="<?php echo $row->bonus_points; ?>"/>
					</td>
				</tr>
			<?php endforeach; ?>
			</tbody>
		</table>
		<?php echo $this->pagination->getPaginationLinks(null, array('showLimitBox' => false)); ?>
	<?php endif; ?>
	<input type="hidden" name="task" value=""/>
	<input type="hidden" name="boxchecked" value="0"/>
	<input type="hidden" name="projectround_id" value="<?php echo $this->state->get('projectround_id'); ?>"/>
	<?php echo JHtml::_('form.token'); ?>
</form>
