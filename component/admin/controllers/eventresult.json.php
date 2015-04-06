<?php
/**
 * @package     Tracks
 * @subpackage  Admin
 * @copyright   Tracks (C) 2008-2015 Julien Vonthron. All rights reserved.
 * @license     GNU General Public License version 2 or later
 */

defined('_JEXEC') or die();

/**
 * Tracks Component event result Controller
 *
 * @package     Tracks
 * @subpackage  Admin
 * @since       3.0
 */
class TracksControllerEventresult extends JControllerLegacy
{
	public function update()
	{
		$model = $this->getModel('Eventresult');
		$property = $this->input->get('property');
		$id = $this->input->get('cb');
		$val = $this->input->get('value', '', 'string');

		$res = new stdclass;

		try
		{
			$model->update($id, $property, $val);
			$res->success = $val;
		}
		catch (Exception $e)
		{
			$res->error = $e->getMessage();
		}

		echo json_encode($res);
		JFactory::getApplication()->close();
	}
}
