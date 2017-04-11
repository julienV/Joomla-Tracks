<?php
/**
 * @package     Tracks
 * @subpackage  Admin
 * @copyright   Tracks (C) 2008-2015 Julien Vonthron. All rights reserved.
 * @license     GNU General Public License version 2 or later
 */

defined('_JEXEC') or die();

/**
 * Tracks Component Eventtypes Controller
 *
 * @package     Tracks
 * @subpackage  Admin
 * @since       3.0
 */
class TracksControllerEventtypes extends RControllerAdmin
{
	public function enableStats()
	{
		$cid = JFactory::getApplication()->input->get('cid', array(), 'array');

		if (empty($cid))
		{
			return;
		}

		$model = $this->getModel('Eventtypes');

		if (!$model->setStatsState($cid, 1))
		{
			$this->setMessage($model->getError(), 'error');
		}
		else
		{
			$total = count($cid);
			$msg = JText::sprintf('COM_TRACKS_N_STATS_ENABLED', $total);

			$this->setMessage($msg);
		}

		// Set redirect
		$this->setRedirect($this->getRedirectToListRoute());
	}

	public function disableStats()
	{
		$cid = JFactory::getApplication()->input->get('cid', array(), 'array');

		if (empty($cid))
		{
			return;
		}

		$model = $this->getModel('Eventtypes');

		if (!$model->setStatsState($cid, 0))
		{
			$this->setMessage($model->getError(), 'error');
		}
		else
		{
			$total = count($cid);
			$msg = JText::sprintf('COM_TRACKS_N_STATS_DISABLED', $total);

			$this->setMessage($msg);
		}

		// Set redirect
		$this->setRedirect($this->getRedirectToListRoute());
	}
}
