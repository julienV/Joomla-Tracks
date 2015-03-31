<?php
/**
 * @package     Tracks
 * @subpackage  Admin
 * @copyright   Tracks (C) 2008-2015 Julien Vonthron. All rights reserved.
 * @license     GNU General Public License version 2 or later
 */

defined('_JEXEC') or die();

/**
 * Tracks Component participants Model
 *
 * @package     Tracks
 * @subpackage  Admin
 * @since       3.0
 */
class TracksModelParticipant extends RModelAdmin
{
	public function validate($form, $data, $group = null)
	{
		if (!isset($data['project_id']))
		{
			$data['project_id'] = TrackslibHelperTools::getCurrentProjectId();
		}

		return parent::validate($form, $data, $group);
	}

}
