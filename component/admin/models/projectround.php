<?php
/**
 * @package     Tracks
 * @subpackage  Admin
 * @copyright   Tracks (C) 2008-2015 Julien Vonthron. All rights reserved.
 * @license     GNU General Public License version 2 or later
 */

defined('_JEXEC') or die();

/**
 * Tracks Component projectrounds Model
 *
 * @package     Tracks
 * @subpackage  Admin
 * @since       3.0
 */
class TracksModelProjectround extends RModelAdmin
{
	/**
	 * Method to validate the form data.
	 * Each field error is stored in session and can be retrieved with getFieldError().
	 * Once getFieldError() is called, the error is deleted from the session.
	 *
	 * @param   JForm   $form   The form to validate against.
	 * @param   array   $data   The data to validate.
	 * @param   string  $group  The name of the field group to validate.
	 *
	 * @return  mixed  Array of filtered data if valid, false otherwise.
	 */
	public function validate($form, $data, $group = null)
	{
		if (!isset($data['project_id']))
		{
			$data['project_id'] = TrackslibHelperTools::getCurrentProjectId();
		}

		return parent::validate($form, $data, $group);
	}

	/**
	 * Copy rounds from one project to the other
	 *
	 * @param   array  $cid          ids of rounds to copy
	 * @param   int    $destination  destination project id
	 *
	 * @return void
	 */
	public function copy($cid, $destination)
	{
		if (empty($cid))
		{
			return;
		}

		foreach ($cid as $projectRoundId)
		{
			$projectRound = RTable::getAdminInstance('Projectround');
			$projectRound->load($projectRoundId);

			$new = clone $projectRound;
			$new->id = null;
			$new->project_id = $destination;
			$new->store();

			$this->copyEvents($projectRoundId, $new->id);
		}
	}

	/**
	 * Copy project round events from one project round to the other
	 *
	 * @param   int  $source       source project round id
	 * @param   int  $destination  destination project round id
	 *
	 * @return void
	 */
	protected function copyEvents($source, $destination)
	{
		$query = $this->_db->getQuery(true)
			->select('id')
			->from('#__tracks_events')
			->where('projectround_id = ' . $source);

		$this->_db->setQuery($query);
		$eventIds = $this->_db->loadColumn();

		if ($eventIds)
		{
			$model = RModel::getAdminInstance('Event');
			$model->copy($eventIds, $destination);
		}
	}
}
