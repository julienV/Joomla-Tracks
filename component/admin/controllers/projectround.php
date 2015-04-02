<?php
/**
 * @package     Tracks
 * @subpackage  Admin
 * @copyright   Tracks (C) 2008-2015 Julien Vonthron. All rights reserved.
 * @license     GNU General Public License version 2 or later
 */

defined('_JEXEC') or die();

/**
 * Tracks Component project round Controller
 *
 * @package     Tracks
 * @subpackage  Admin
 * @since       3.0
 */
class TracksControllerProjectround extends RControllerForm
{
	public function savecopy()
	{
		$option     = $this->input->getCmd('option', '');
		$cid        = $this->input->get('cid', array(), 'post', 'array');
		$project_id = $this->input->getInt('project_id', 0);
		JArrayHelper::toInteger($cid);

		if (count($cid) < 1)
		{
			throw new Exception(JText::_('COM_TRACKS_Select_an_round_to_copy'), 500);
		}

		$model = $this->getModel('projectrounds');

		if (!$model->assign($cid, $project_id))
		{
			echo "<script> alert('" . $model->getError(true) . "');</script>\n";
		}

		JFactory::getApplication()->setUserState($option . 'project', $project_id);
		$link = 'index.php?option=com_tracks&view=projectrounds';
		$this->setRedirect($link);
	}

	/**
	 * Articles
	 */
	public function element()
	{
		$model = $this->getModel('projectroundElement');
		$view = $this->getView('projectroundElement', 'html');
		$view->setModel($model, true);
		$view->display();
	}

	/**
	 * display the copy form
	 * @return void
	 */
	public function copy()
	{
		$cid = $this->input->get('cid', '', 'array');
		JArrayHelper::toInteger($cid);

		$model = $this->getThisModel();
		$model->setState('cid', $cid);

		$this->layout = 'copy_form';

		return $this->browse();
	}
}
