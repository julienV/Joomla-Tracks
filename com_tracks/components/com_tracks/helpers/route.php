<?php
/**
* @version    $Id$ 
* @package    JoomlaTracks
* @copyright  Copyright (C) 2008 Julien Vonthron. All rights reserved.
* @license    GNU/GPL, see LICENSE.php
* Joomla Tracks is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/

defined('_JEXEC') or die('Restricted access');

class TracksHelperRoute
{	
	/**
	 * return link to details view of specified event
	 * @param int $id
	 * @param int $xref
	 * @return url
	 */
	function getRoundResultRoute($id = 0)
	{
		$parts = array( "option" => "com_tracks",
		                "view"   => "roundresult" );
		if ($id) {
			$parts['pr'] = $id;
		}
		return self::buildUrl( $parts );
	}
	
	/**
	 * return link to details view of specified event
	 * @param int $id
	 * @param int $xref
	 * @return url
	 */
	function getRoundRoute($id = 0)
	{
		$parts = array( "option" => "com_tracks",
		                "view"   => "round" );
		if ($id) {
			$parts['r'] = $id;
		}
		return self::buildUrl( $parts );
	}
	
	
	function buildUrl($parts)
	{		
		if($item = self::_findItem($parts)) {
			$parts['Itemid'] = $item->id;
		};
		
		return 'index.php?'.JURI::buildQuery( $parts );
	}
	
	/**
	 * Determines the Itemid
	 *
	 * searches if a menuitem for this item exists
	 * if not the first match will be returned
	 *
	 * @param array url parameters
	 * @since 0.9
	 *
	 * @return int Itemid
	 */
	function _findItem($query)
	{
		$component =& JComponentHelper::getComponent('com_tracks');
		$menus	= & JSite::getMenu();
		$items	= $menus->getItems('componentid', $component->id);
		$user 	= & JFactory::getUser();
		$access = (int)$user->get('aid');
		
		if ($items) 
		{
			foreach($items as $item)
			{	
				if ((@$item->query['view'] == $query['view']) && ($item->published == 1) && ($item->access <= $access)) 
				{					
					switch ($query['view'])
					{
						case 'roundresult':
							if ((int) @$item->query['pr'] == (int) @$query['pr']) {
								return $item;
							}						
						  break;
						default:
							if (!isset($query['id']) || (int) @$item->query['id'] == (int) @$query['id']) {
								return $item;
							}
					}
				}
			}
		}

		return false;
	}
}
?>