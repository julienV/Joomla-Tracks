<?php
/**
 * @package     Tracks.Plugin
 * @subpackage  imports
 *
 * @copyright   Copyright (C) 2020 Julien Vonthron. All rights reserved.
 * @license     GNU General Public License version 3 or later, see LICENSE.
 */

namespace TracksF1\Output;

trait TraitHasOutput
{
	/**
	 * @var OutputInterface
	 */
	private $output;

	public function setOutput(OutputInterface $helper)
	{
		$this->output = $helper;
	}

	protected function writeSection($section)
	{
		$this->output->writeSection($section);
	}

	protected function writeText($text)
	{
		$this->output->writeText($text);
	}
}