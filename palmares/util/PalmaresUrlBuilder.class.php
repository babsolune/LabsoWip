<?php
/*##################################################
 *		                PalmaresUrlBuilder.class.php
 *                            -------------------
 *   begin                : April 13, 2016
 *   copyright            : (C) 2016 Sebastien Lartigue
 *   email                : babso@web33.fr
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
 * @author Sebastien Lartigue <babso@web33.fr>
 */

class PalmaresUrlBuilder
{
	const DEFAULT_SORT_FIELD = 'date';
	const DEFAULT_SORT_MODE = 'desc';

	private static $dispatcher = '/palmares';

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

	public static function manage_palmares()
	{
		return DispatchManager::get_url(self::$dispatcher, '/manage/');
	}

	public static function category_syndication($id)
	{
		return SyndicationUrlBuilder::rss('palmares', $id);
	}

	public static function display_palmares($id_category, $rewrited_name_category, $id_palmares, $rewrited_title)
	{
		return DispatchManager::get_url(self::$dispatcher, '/' . $id_category . '-' . $rewrited_name_category . '/' . $id_palmares . '-' . $rewrited_title . '/');
	}

	public static function display_comments_palmares($id_category, $rewrited_name_category, $id_palmares, $rewrited_title)
	{
		return DispatchManager::get_url(self::$dispatcher, '/' . $id_category . '-' . $rewrited_name_category . '/' . $id_palmares . '-' . $rewrited_title . '/#comments-list');
	}

	public static function display_category($id, $rewrited_name, $page = 1)
	{
		$category = $id > 0 ? $id . '-' . $rewrited_name .'/' : '';
		$page = $page !== 1 ? $page . '/' : '';
		return DispatchManager::get_url(self::$dispatcher, '/' . $category . $page);
	}

	public static function display_tag($rewrited_name, $page = 1)
	{
		$page = $page !== 1 ? $page . '/' : '';
		return DispatchManager::get_url(self::$dispatcher, '/tag/'. $rewrited_name .'/' . $page);
	}

	public static function display_pending_palmares($page = 1)
	{
		$page = $page !== 1 ? $page . '/' : '';
		return DispatchManager::get_url(self::$dispatcher, '/pending/' . $page);
	}

	public static function add_palmares($id_category = null)
	{
		$id_category = !empty($id_category) ? $id_category . '/': '';
		return DispatchManager::get_url(self::$dispatcher, '/add/' . $id_category);
	}

	public static function edit_palmares($id)
	{
		return DispatchManager::get_url(self::$dispatcher, '/' . $id . '/edit/');
	}

	public static function delete_palmares($id)
	{
		return DispatchManager::get_url(self::$dispatcher, '/' . $id . '/delete/?' . 'token=' . AppContext::get_session()->get_token());
	}

	public static function home()
	{
		return DispatchManager::get_url(self::$dispatcher, '/');
	}
}
?>
