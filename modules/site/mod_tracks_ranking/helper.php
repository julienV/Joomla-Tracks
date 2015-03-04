<?php
/**
* @version    $Id: tracks.php 109 2008-05-24 11:05:07Z julienv $
* @package    JoomlaTracks
* @subpackage RankingModule
* @copyright  Copyright (C) 2008 Julien Vonthron. All rights reserved.
* @license    GNU/GPL, see LICENSE.php
* Joomla Tracks is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/

// no direct access
defined('_JEXEC') or die('Restricted access');

//require_once (JPATH_SITE. '/' .'components'. '/' .'com_tracks'. '/' .'helpers'. '/' .'route.php');

class modTracksRanking
{
	/**
	 * instance of the ranking model
	 *
	 * @var object
	 */
  var $_model = null;

  function _getModel($params)
  {
    if ( $this->_model == null )
    {
      require_once (JPATH_SITE. '/' .'components'. '/' .'com_tracks'. '/' .'models'. '/' .'ranking.php');
      $this->_model = new TracksModelRanking();
      $this->_model->setProjectId($params->get('project_id'));
    }
    return $this->_model;
  }

  function getList(&$params)
	{
    $model = $this->_getModel($params);
		return $model->getRankings();
	}

	function getProject(&$params)
	{
	  $model = $this->_getModel($params);
    return $model->getProject();
	}
}
