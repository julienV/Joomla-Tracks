<?php
/**
 * @package     Tracks.Plugin
 * @subpackage  imports
 *
 * @copyright   Copyright (C) 2020 Julien Vonthron. All rights reserved.
 * @license     GNU General Public License version 3 or later, see LICENSE.
 */

namespace TracksF1\Output;

class Html implements OutputInterface
{
	private $content = [];

	public function writeSection($section)
	{
		$this->content[] = '<h2>' . $section . '</h2>';
	}

	/**
	 * Write text
	 *
	 * @param   string  $text  text to write
	 *
	 * @return void
	 */
	public function writeText($text)
	{
		$this->content[] = "<div>$text</div>";
	}

	public function output()
	{
		return implode("\n", $this->content);
	}
}