<?php
/**
 * @package     Tracks.Plugin
 * @subpackage  imports
 *
 * @copyright   Copyright (C) 2020 Julien Vonthron. All rights reserved.
 * @license     GNU General Public License version 3 or later, see LICENSE.
 */

namespace TracksF1\Output;

interface OutputInterface
{
	/**
	 * Write section
	 *
	 * @param   string  $section  section to write
 	 *
	 * @return void
	 */
	public function writeSection($section);

	/**
	 * Write text
	 *
	 * @param   string  $text  text to write
	 *
	 * @return void
	 */
	public function writeText($text);

	/**
	 * Get content
	 *
	 * @return string;
	 */
	public function output();
}