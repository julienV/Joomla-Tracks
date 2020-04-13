<?php
/**
 * @package     Tracks.Plugin
 * @subpackage  imports
 *
 * @copyright   Copyright (C) 2020 Julien Vonthron. All rights reserved.
 * @license     GNU General Public License version 3 or later, see LICENSE.
 */

namespace TracksF1\Import\Data;

use Aesir\Core\Helper\ModelFinder;
use Joomla\CMS\MVC\Model\AdminModel;
use Joomla\CMS\Table\Table;
use TracksF1\Client\Client;

defined('_JEXEC') or die('Restricted access');

/**
 * Class Race
 */
class Race
{
	public $apiId;
	public $name;
	public $country;
	public $date;
	public $round_number;
}