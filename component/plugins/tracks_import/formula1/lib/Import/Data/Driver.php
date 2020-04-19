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
 * Class Driver
 */
class Driver
{
	public $apiId;
	public $first_name;
	public $last_name;
	public $dob;
	public $country_code;
	public $number;
}