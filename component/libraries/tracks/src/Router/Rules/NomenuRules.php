<?php
/**
 * Joomla! Content Management System
 *
 * @copyright  Copyright (C) 2005 - 2020 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace Tracks\Router\Rules;

defined('JPATH_PLATFORM') or die;

use Joomla\CMS\Component\Router\RouterView;

/**
 * Rule to process URLs without a menu item
 *
 * @since  3.4
 */
class NomenuRules extends \Joomla\CMS\Component\Router\Rules\NomenuRules
{
	/**
	 * Parse a menu-less URL
	 *
	 * @param   array  &$segments  The URL segments to parse
	 * @param   array  &$vars      The vars that result from the segments
	 *
	 * @return  void
	 *
	 * @since   3.4
	 */
	public function parse(&$segments, &$vars)
	{
		$active = $this->router->menu->getActive();

		if (!is_object($active))
		{
			$views = $this->router->getViews();

			if (isset($views[$segments[0]]))
			{
				$vars['view'] = array_shift($segments);

				if (isset($views[$vars['view']]->key) && isset($segments[0]))
				{
					if (is_callable(array($this->router, 'get' . ucfirst($views[$vars['view']]->name) . 'Id')))
					{
						$vars[$views[$vars['view']]->key] = call_user_func_array(array($this->router, 'get' . ucfirst($views[$vars['view']]->name) . 'Id'), array($segments[0], $vars));
					}
					else
					{
						$vars[$views[$vars['view']]->key] = preg_replace('/-/', ':', array_shift($segments), 1);
					}
				}
			}
		}
	}
}
