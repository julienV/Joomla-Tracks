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

JHTML::_('behavior.tooltip');

$model = $this->getModel();

$teams_options = array(JHtml::_('select.option', 0, ''));
if ($opt = $model->getTeamsOptions())
{
	$teams_options = array_merge($teams_options, $opt);
}
?>

<div id="tracksmain">

	<form action="index.php" method="post" name="adminForm" id="adminForm">
		<input type="hidden" name="option" value="com_tracks" />
		<input type="hidden" name="view" value="individuals" />
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
		        <?php echo JText::_('COM_TRACKS_Individual' ); ?>
					</th>
					<th class="title">
		        <?php echo JText::_('COM_TRACKS_Team' ); ?>
					</th>
		      <th width="10">
		        <?php echo JText::_('COM_TRACKS_Number' ); ?>
		      </th>
				</tr>
			</thead>
			<tbody>
			<?php
			$k = 0;
			for ($i=0, $n=count( $this->items ); $i < $n; $i++)
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
						echo $row->last_name.', '.$row->first_name;
						?>
					</td>
		      <td>
						<?php echo JHTML::_('select.genericlist',  $teams_options, 'team_id[]', 'class="inputbox" size="1"', 'id', 'name'); ?>
					</td>
					<td>
					  <input name="number[]" id="number<?php echo $row->id; ?>" type="text" value="" size="3" />
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
