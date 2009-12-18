<?php
/**
* @version    $Id: round.php 80 2008-04-30 20:17:37Z julienv $ 
* @package    JoomlaTracks
* @copyright	Copyright (C) 2008 Julien Vonthron. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* Joomla Tracks is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/

// no direct access
defined('_JEXEC') or die('Restricted access');

// Include library dependencies
jimport('joomla.filter.input');

/**
* Rounds Table class
* Rounds can be GP for formula one, stages for multi-day bicyle racing, etc...
*
* @package		Tracks
* @since 0.1
*/
class TableRound extends JTable
{
	/**
	 * Primary Key
	 *
	 * @var int
	 */
	var $id = null;
	
  var $name;
  /**
   * alias for SEO
   *
   * @var string
   */
  var $alias;
  var $description;
  var $ordering;
  var $checked_out;
  var $checked_out_time;
  var $published;


	/**
	 * Constructor
	 *
	 * @param object Database connector object
	 * @since 1.0
	 */
	function __construct(& $db) {
		parent::__construct('#__tracks_rounds', 'id', $db);
	}

	/**
	 * Overloaded check method to ensure data integrity
	 *
	 * @access public
	 * @return boolean True on success
	 * @since 1.0
	 */
	function check()
	{
    $alias = JFilterOutput::stringURLSafe($this->name);

    if(empty($this->alias) || $this->alias === $alias ) {
      $this->alias = $alias;
    }
		//should check name unicity
		return true;
	}
}
?>
