<?php
/*##################################################
 *		                RadioUrlBuilder.class.php
 *                            -------------------
 *   begin                : May, 02, 2017
 *   copyright            : (C) 2017 Sebastien LARTIGUE
 *   email                : babsolune@phpboost.com
 *
 *
 ###################################################
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
 ###################################################*/

/**
 * @author Sebastien LARTIGUE <babsolune@phpboost.com>
 */

class RadioUrlBuilder
{
	const DEFAULT_SORT_FIELD = 'date';
	const DEFAULT_SORT_MODE = 'desc';

	private static $dispatcher = '/radio';

	public static function configuration()
	{
		return DispatchManager::get_url(self::$dispatcher, '/admin/config/');
	}

	public static function add_category($id_parent = null)
	{
		$id_parent = !empty($id_parent) ? $id_parent . '/' : '';
		return DispatchManager::get_url(self::$dispatcher, '/categories/add/' . $id_parent);
	}

	public static function edit_category($id)
	{
		return DispatchManager::get_url(self::$dispatcher, '/categories/'. $id .'/edit/');
	}

	public static function delete_category($id)
	{
		return DispatchManager::get_url(self::$dispatcher, '/categories/'. $id .'/delete/');
	}

	public static function manage_categories()
	{
		return DispatchManager::get_url(self::$dispatcher, '/categories/');
	}

	public static function manage_radio()
	{
		return DispatchManager::get_url(self::$dispatcher, '/manage/');
	}

	public static function category_syndication($id)
	{
		return SyndicationUrlBuilder::rss('radio', $id);
	}

	public static function display_radio($id_category, $rewrited_name_category, $id_radio, $rewrited_title)
	{
		return DispatchManager::get_url(self::$dispatcher, '/' . $id_category . '-' . $rewrited_name_category . '/' . $id_radio . '-' . $rewrited_title . '/');
	}

	public static function display_category($id, $rewrited_name, $page = 1)
	{
		$category = $id > 0 ? $id . '-' . $rewrited_name .'/' : '';
		$page = $page !== 1 ? $page . '/' : '';
		return DispatchManager::get_url(self::$dispatcher, '/' . $category . $page);
	}

	public static function display_pending_radio($page = 1)
	{
		$page = $page !== 1 ? $page . '/' : '';
		return DispatchManager::get_url(self::$dispatcher, '/pending/' . $page);
	}

	public static function add_radio($id_category = null)
	{
		$id_category = !empty($id_category) ? $id_category . '/': '';
		return DispatchManager::get_url(self::$dispatcher, '/add/' . $id_category);
	}

	public static function edit_radio($id)
	{
		return DispatchManager::get_url(self::$dispatcher, '/' . $id . '/edit/');
	}

	public static function delete_radio($id)
	{
		return DispatchManager::get_url(self::$dispatcher, '/' . $id . '/delete/?' . 'token=' . AppContext::get_session()->get_token());
	}

	/**
	* @return Url
	*/
   public static function documentation()
   {
	   return DispatchManager::get_url(self::$dispatcher, '/documentation/');
   }
   
	public static function home()
	{
		return DispatchManager::get_url(self::$dispatcher, '/');
	}
}
?>
