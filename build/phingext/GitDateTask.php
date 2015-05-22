<?php
/**
 * @package    tracks.builder
 * @copyright  Copyright (c) 2015 JLV
 * @license    GNU GPL version 2 or, at your option, any later version
 */

require_once 'phing/Task.php';

/**
 * Git latest commit date to Phing property
 *
 * @package  tracks.builder
 * @since    1.0
 */
class GitDateTask extends Task
{
	private $propertyName = "git.date";

	/**
	 * Sets the name of the property to use
	 *
	 * @param   string  $propertyName  property name
	 *
	 * @return void
	 */
	function setPropertyName($propertyName)
	{
		$this->propertyName = $propertyName;
	}

	/**
	 * Returns the name of the property to use
	 *
	 * @return string
	 */
	function getPropertyName()
	{
		return $this->propertyName;
	}

	/**
	 * The main entry point
	 *
	 * @return void
	 *
	 * @throws BuildException
	 */
	function main()
	{
		exec('git log --format=%at -n1 ', $out);
		$this->project->setProperty($this->getPropertyName(), strtoupper(trim($out[0])));
	}
}
