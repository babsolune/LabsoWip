<?php
/*##################################################
 *                       SponsorsUrlBuilder.class.php
 *                            -------------------
 *   begin                : May 20, 2018
 *   copyright            : (C) 2018 Sebastien LARTIGUE
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

class SponsorsUrlBuilder
{

	private static $dispatcher = '/sponsors';

    // Administration

	/**
	 * @return Url
	 */
	public static function configuration()
	{
		return DispatchManager::get_url(self::$dispatcher, '/config/');
	}

	/**
	 * @return Url
	 */
	public static function mini_configuration()
	{
		return DispatchManager::get_url(self::$dispatcher, '/config/mini/');
	}

	/**
	 * @return Url
	 */
	public static function membership_terms_configuration()
	{
		return DispatchManager::get_url(self::$dispatcher, '/config/terms/');
	}

    // Categories

	/**
	 * @return Url
	 */
	public static function manage_categories()
	{
		return DispatchManager::get_url(self::$dispatcher, '/categories/');
	}

	/**
	 * @return Url
	 */
	public static function category_syndication($id)
	{
		return SyndicationUrlBuilder::rss('sponsors', $id);
	}

	/**
	 * @return Url
	 */
	public static function add_category($id_parent = null)
	{
		$id_parent = !empty($id_parent) ? $id_parent . '/' : '';
		return DispatchManager::get_url(self::$dispatcher, '/categories/add/' . $id_parent);
	}

	/**
	 * @return Url
	 */
	public static function edit_category($id)
	{
		return DispatchManager::get_url(self::$dispatcher, '/categories/'. $id .'/edit/');
	}

	/**
	 * @return Url
	 */
	public static function delete_category($id)
	{
		return DispatchManager::get_url(self::$dispatcher, '/categories/'. $id .'/delete/');
	}

	/**
	 * @return Url
	 */
	public static function display_category($id, $rewrited_name)
	{
		$config = SponsorsConfig::load();
		$category = $id > 0 ? $id . '-' . $rewrited_name .'/' : '';
		return DispatchManager::get_url(self::$dispatcher, '/' . $category);
	}

    // Items

	/**
	 * @return Url
	 */
	public static function manage_items()
	{
		return DispatchManager::get_url(self::$dispatcher, '/manage/');
	}

	/**
	 * @return Url
	 */
	public static function print_item($id_partner, $rewrited_title)
	{
		return DispatchManager::get_url(self::$dispatcher, '/print/' . $id_partner . '-' .$rewrited_title . '/');
	}

	/**
	 * @return Url
	 */
	public static function add_item($id_category = null)
	{
		$id_category = !empty($id_category) ? $id_category . '/' : '';
		return DispatchManager::get_url(self::$dispatcher, '/add/' . $id_category);
	}

	/**
	 * @return Url
	 */
	public static function edit_item($id, $page = 1)
	{
		$page = $page !== 1 ? $page . '/' : '';
		return DispatchManager::get_url(self::$dispatcher, '/' . $id . '/edit/' . $page);
	}

	/**
	 * @return Url
	 */
	public static function delete_item($id)
	{
		return DispatchManager::get_url(self::$dispatcher, '/' . $id . '/delete/?' . 'token=' . AppContext::get_session()->get_token());
	}

	/**
	 * @return Url
	 */
	public static function display_item($id_category, $rewrited_name_category, $id_partner, $rewrited_title)
	{
		return DispatchManager::get_url(self::$dispatcher, '/' . $id_category . '-' . $rewrited_name_category . '/' . $id_partner . '-' .$rewrited_title);
	}

	/**
	 * @return Url
	 */
 	public static function display_pending_items()
	{
		$config = SponsorsConfig::load();
		return DispatchManager::get_url(self::$dispatcher, '/pending/');
	}

	/**
	 * @return Url
	 */
	public static function display_member_items()
	{
		return DispatchManager::get_url(self::$dispatcher, '/member/');
	}

	/**
	 * @return Url
	 */
	public static function membership_terms()
	{
		return DispatchManager::get_url(self::$dispatcher, '/terms/');
	}

	/**
	 * @return Url
	 */
	public static function visit($id)
	{
		return DispatchManager::get_url(self::$dispatcher, '/visit/' . $id);
	}

	/**
	 * @return Url
	 */
	public static function dead_link($id)
	{
		return DispatchManager::get_url(self::$dispatcher, '/dead_link/' . $id);
	}

	/**
	 * @return Url
	 */
	public static function home()
	{
		return DispatchManager::get_url(self::$dispatcher, '/');
	}
}
?>