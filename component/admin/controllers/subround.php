<?php
/**
* @version    0.2 $Id$
* @package    JoomlaTracks
* @copyright  Copyright (C) 2008 Julien Vonthron. All rights reserved.
* @license    GNU/GPL, see LICENSE.php
* Joomla Tracks is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();

/**
 * Joomla Tracks Component Controller
 *
 * @package  Tracks
 * @since    0.2
 */
class TracksControllerSubround extends FOFController
{
	public function onBeforeBrowse()
	{
		// Do I have a form?
		$model = $this->getThisModel();

		$prid = $this->input->getInt('prid', 0);

		if (!$prid)
		{
			throw new Exception('prid is required', 500);
		}

		$model->setState('prid', $prid);

		return true;
	}

	public function back()
	{
		$this->setRedirect('index.php?option=com_tracks&view=projectrounds');
	}
}
