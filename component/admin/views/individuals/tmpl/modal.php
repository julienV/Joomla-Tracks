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

$function = JFactory::getApplication()->input->getCmd('function', 'jSelectIndividual');
?>
<form action="index.php?option=com_tracks&view=individuals&tmpl=component&function=<?php echo $function; ?>" class="admin" id="adminForm" method="post" name="adminForm" >
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
				<th class="title" width="auto">
					<?php echo JHTML::_('rsearchtools.sort', 'COM_TRACKS_LAST_NAME', 'obj.last_name', $listDirn, $listOrder); ?>
				</th>
				<th class="title" width="auto">
					<?php echo JHTML::_('rsearchtools.sort', 'COM_TRACKS_FIRST_NAME', 'obj.first_name', $listDirn, $listOrder); ?>
				</th>
				<th width="auto">
					<?php echo JHTML::_('rsearchtools.sort', 'COM_TRACKS_ALIAS', 'obj.alias', $listDirn, $listOrder); ?>
				</th>
				<th width="10">
					<?php echo JHTML::_('rsearchtools.sort', 'COM_TRACKS_COUNTRY', 'obj.country_code', $listDirn, $listOrder); ?>
				</th>
				<th width="auto">
					<?php echo JHTML::_('rsearchtools.sort', 'COM_TRACKS_USER', 'u.username', $listDirn, $listOrder); ?>
				</th>
			</tr>
			</thead>
			<tbody>
			<?php $n = count($this->items); ?>
			<?php foreach ($this->items as $i => $row) : ?>
				<?php $link = 'index.php?option=com_tracks&view=individual&id=' . $row->id; ?>
				<tr>
					<td>
						<?php echo $this->pagination->getRowOffset($i); ?>
					</td>
					<td>
						<?php $itemTitle = $row->last_name; ?>
						<a class="pointer" onclick="if (window.parent)
							window.parent.<?php echo $this->escape($function);?>(
							'<?php echo $row->id; ?>',
							'<?php echo $this->escape(addslashes($row->last_name)); ?>',
							'<?php echo $this->escape(addslashes($row->first_name)); ?>',
							'<?php echo $this->escape(addslashes($link)); ?>');">
							<?php echo $itemTitle; ?>
						</a>
					</td>
					<td>
						<?php echo $row->first_name; ?>
					</td>
					<td>
						<?php echo $row->alias; ?>
					</td>
					<td>
						<?php echo TrackslibHelperCountries::getCountryFlag($row->country_code); ?>
					</td>
					<td>
						<?php echo $row->username; ?>
					</td>
				</tr>
			<?php endforeach; ?>
			</tbody>
		</table>
		<?php echo $this->pagination->getPaginationLinks(null, array('showLimitBox' => false)); ?>
	<?php endif; ?>
	<input type="hidden" name="task" value=""/>
	<input type="hidden" name="boxchecked" value="0"/>
	<input type="hidden" name="filter_order" value="<?php echo $listOrder; ?>"/>
	<input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn; ?>"/>
	<?php echo JHtml::_('form.token'); ?>
</form>
