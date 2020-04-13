<?php
/**
 * @package     Tracks.Plugin
 * @subpackage  imports
 *
 * @copyright   Copyright (C) 2020 Julien Vonthron. All rights reserved.
 * @license     GNU General Public License version 3 or later, see LICENSE.
 */

namespace TracksF1\Import;

trait TraitHasCache
{
	protected $cache;

	public function setCache(&$cache)
	{
		$this->cache = &$cache;
	}
}