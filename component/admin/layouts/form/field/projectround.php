<?php
/**
 * @package     Tracks
 * @subpackage  Admin
 * @copyright   Tracks (C) 2008-2015 Julien Vonthron. All rights reserved.
 * @license     GNU General Public License version 2 or later
 */

defined('_JEXEC') or die();

$data = $displayData;

// Load modal behavior
JHtml::_('behavior.modal', 'a.modal');

// Build the script
$script = array();
$script[] = '    function jSelectProjectround_' . $data['id'] . '(id, title) {';
$script[] = '        document.id("' . $data['id'] . '_id").value = id;';
$script[] = '        document.id("' . $data['id'] . '_name").value = title;';
$script[] = '        SqueezeBox.close();';
$script[] = '    }';

// Add to document head
JFactory::getDocument()->addScriptDeclaration(implode("\n", $script));

$url = 'index.php?option=com_tracks&view=projectroundelement&tmpl=component&function=jSelectProjectround_' . $data['id'];
$link = JHtml::link(
	$url,
	JText::_('COM_TRACKS_Select_a_project_round'),
	array(
		'class' => 'modal',
		'title' => JText::_('COM_TRACKS_Select_a_project_round'),
		'rel' => "{handler: 'iframe', size: {x:800, y:450}}"
	)
);

$class = $data['required'] ? 'required modal-value' : 'modal-value';
?>
<div class="fltlft">
	<input type="text"
	       id="<?php echo $data['id']; ?>_name"
	       value="<?php echo $data['title']; ?>"
	       disabled="disabled" size="35" />
</div>

<div class="button2-left">
	<div class="blank">
		<?php echo $link; ?>
	</div>
</div>

<input type="hidden"
       id="<?php echo $data['id']; ?>_id"
       class="<?php echo $data['id']; ?>"
       name="<?php echo $data['name']; ?>"
       value="<?php echo $data['value']; ?>" />
