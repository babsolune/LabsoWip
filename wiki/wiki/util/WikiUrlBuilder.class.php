<?php
/**
 * @copyright 	&copy; 2005-2019 PHPBoost
 * @license 	https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version   	PHPBoost 5.2 - last update: 2018 05 25
 * @since   	PHPBoost 4.0 - 2014 02 02
 * @contributor xela <xela@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class WikiUrlBuilder
{
	private static $dispatcher = '/wiki';

	public static function configuration()
	{
		return DispatchManager::get_url(self::$dispatcher, '/admin/config/');
	}

	public static function manage_categories()
	{
		return DispatchManager::get_url(self::$dispatcher, '/categories/');
	}

	public static function category_syndication($id)
	{
		return SyndicationUrlBuilder::rss('wiki', $id);
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

	public static function display_category($id, $rewrited_name, $page = 1, $subcategories_page = 1)
	{
		$config = WikiConfig::load();
		$category = $id > 0 ? $id . '-' . $rewrited_name .'/' : '';
		$page = $page !== 1 || $subcategories_page !== 1 ? $page . '/': '';
		$subcategories_page = $subcategories_page !== 1 ? $subcategories_page . '/': '';
		return DispatchManager::get_url(self::$dispatcher, '/' . $category . $page . $subcategories_page);
	}

	/**
	 * @return Url
	 */
	public static function reorder_items($id_category, $rewrited_name)
	{
		$category = $id_category > 0 ? $id_category . '-' . $rewrited_name . '/' : '';
		return DispatchManager::get_url(self::$dispatcher, '/reorder/' . $category);
	}

	public static function manage_items()
	{
		return DispatchManager::get_url(self::$dispatcher, '/manage/');
	}

	public static function print_item($id_document, $rewrited_title)
	{
		return DispatchManager::get_url(self::$dispatcher, '/print/' . $id_document . '-' .$rewrited_title . '/');
	}

	public static function add_item($id_category = null)
	{
		$id_category = !empty($id_category) ? $id_category . '/' : '';
		return DispatchManager::get_url(self::$dispatcher, '/add/' . $id_category);
	}

	public static function edit_item($id, $page = 1)
	{
		$page = $page !== 1 ? $page . '/' : '';
		return DispatchManager::get_url(self::$dispatcher, '/' . $id . '/edit/' . $page);
	}

	public static function delete_item($id)
	{
		return DispatchManager::get_url(self::$dispatcher, '/' . $id . '/delete/?' . 'token=' . AppContext::get_session()->get_token());
	}

	public static function display_item($id_category, $rewrited_name_category, $id_document, $rewrited_title, $page = 1)
	{
		$page = $page !== 1 ? $page . '/' : '';
		return DispatchManager::get_url(self::$dispatcher, '/' . $id_category . '-' . $rewrited_name_category . '/' . $id_document . '-' . $rewrited_title . '/' . $page);
	}

	public static function change_history($id)
	{
		return DispatchManager::get_url(self::$dispatcher, '/' . $id . '/history/');
	}

	public static function display_pending_items($page = 1)
	{
		$config = WikiConfig::load();
		$page = $page !== 1 ? $page . '/': '';
		return DispatchManager::get_url(self::$dispatcher, '/pending/' . $page);
	}

	public static function display_tag($rewrited_name, $page = 1)
	{
		$config = WikiConfig::load();
		$page = $page !== 1 ? $page . '/' : '';
		return DispatchManager::get_url(self::$dispatcher, '/tag/'. $rewrited_name . '/' . $page);
	}

	/**
	 * @return Url
	 */
	public static function display_favorites()
	{
		return DispatchManager::get_url(self::$dispatcher, '/favorites/');
	}

	/**
	 * @return Url
	 */
	public static function display_member_items()
	{
		return DispatchManager::get_url(self::$dispatcher, '/member/');
	}

	public static function home()
	{
		return DispatchManager::get_url(self::$dispatcher, '/');
	}
}
?>
