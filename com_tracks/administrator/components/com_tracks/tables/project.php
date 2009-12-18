<?php
/**
 * @version    $Id: project.php 67 2008-04-24 16:41:27Z julienv $
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
 * Project Table class
 *
 * @package		Tracks
 * @since 0.1
 */
class TableProject extends JTable
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
	var $competition_id;
	var $season_id;
	var $admin_id;
	var $project_type;

	var $params;

	var $checked_out;
	var $checked_out_time;
	var $ordering;
	var $published;


	/**
	 * Constructor
	 *
	 * @param object Database connector object
	 * @since 1.0
	 */
	function __construct(& $db) {
		parent::__construct('#__tracks_projects', 'id', $db);
	}

	/**
	 * Overloaded bind function
	 *
	 * @acces public
	 * @param array $hash named array
	 * @return null|string	null is operation was satisfactory, otherwise returns an error
	 * @see JTable:bind
	 * @since 1.5
	 */
	function bind($array, $ignore = '')
	{
		if (key_exists( 'params', $array ) && is_array( $array['params'] )) {
			$registry = new JRegistry();
			$registry->loadArray($array['params']);
			$array['params'] = $registry->toString();
		}
		//print_r($array);exit;
		return parent::bind($array, $ignore);
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
		$result = true;
		if ( $this->name == '' )
		{
			$this->setError( JText::_( ' Name is mandatory ' ) );
			return false;
		}
		
    $alias = JFilterOutput::stringURLSafe($this->name);

    if(empty($this->alias) || $this->alias === $alias ) {
      $this->alias = $alias;
    }
    
		if ( !$this->season_id )
		{
			$this->setError( JText::_( ' Season is mandatory ' ) );
			return false;
		}
		if ( !$this->competition_id )
		{
			$this->setError( JText::_( ' Competition is mandatory ' ) );
			return false;
		}
		return true;
	}
}
?>
