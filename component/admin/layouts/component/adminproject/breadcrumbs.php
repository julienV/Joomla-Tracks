<?php
/**
 * @package     Redcore
 * @subpackage  Layout
 *
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later, see LICENSE.
 */

defined('JPATH_REDCORE') or die;

$data = $displayData;

$path = $data['view']->breadcrumbs;

$breadcrumbs = array();
foreach ($path as $name => $url)
{
	$breadcrumbs[] = $url ? JHtml::link($url, $name) : $name;
}
?>
<?php echo implode(' / ', $breadcrumbs); ?>
