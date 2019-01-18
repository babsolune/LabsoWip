<?php
/**
 * @copyright 	&copy; 2005-2019 PHPBoost https://www.phpboost.com
 * @license 	https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE [babsolune@phpboost.com]
 * @version   	PHPBoost 5.2 - last update: 2018 05 25
 * @since   	PHPBoost 5.1 - 2018 05 25
*/

namespace Wiki\services;
use \Wiki\services\ModSetup;
use \Wiki\services\ModCategoriesCache;
use \Wiki\services\ModItem;
use \Wiki\services\ModKeywordsCache;

class ModServices
{
	private static $db_querier;
	private static $categories_manager;
	private static $keywords_manager;

	public static function __static()
	{
		self::$db_querier = PersistenceContext::get_querier();
	}

	 /**
	 * Count items number.
	 * @param string $condition (optional) : Restriction to apply to the list of items
	 */
	public static function count($condition = '', $parameters = array())
	{
		return self::$db_querier->count(ModSetup::$items_table, $condition, $parameters);
	}

	public static function add(ModItem $moditem)
	{
		$result = self::$db_querier->insert(ModSetup::$items_table, $moditem->get_properties());
		return $result->get_last_inserted_id();
	}

	public static function update(ModItem $moditem)
	{
		self::$db_querier->update(ModSetup::$items_table, $moditem->get_properties(), 'WHERE id=:id', array('id', $moditem->get_id()));
	}

	 /**
	 * Update the position of an item.
	 * @param string[] $moditem_id : id of the item to update
	 * @param string[] $position : new item position
	 */
	public static function update_position($moditem_id, $position)
	{
		self::$db_querier->update(ModSetup::$items_table, array('order_id' => $position), 'WHERE id=:id', array('id' => $moditem_id));
	}

	public static function delete($condition, array $parameters)
	{
		self::$db_querier->delete(ModSetup::$items_table, $condition, $parameters);
	}

	public static function get_moditem($condition, array $parameters)
	{
		$row = self::$db_querier->select_single_row_query('SELECT item.*, member.*
		FROM ' . ModSetup::$items_table . ' item
		LEFT JOIN ' . DB_TABLE_MEMBER . ' member ON member.user_id = item.author_user_id
		' . $condition, $parameters);

		$moditem = new ModItem();
		$moditem->set_properties($row);
		return $moditem;
	}

	public static function update_number_view(ModItem $moditem)
	{
		self::$db_querier->update(ModSetup::$items_table, array('number_view' => $moditem->get_number_view()), 'WHERE id=:id', array('id' => $moditem->get_id()));
	}

	public static function get_authorized_categories($current_category_id)
	{
		$search_category_children_options = new SearchCategoryChildrensOptions();
		$search_category_children_options->add_authorizations_bits(Category::READ_AUTHORIZATIONS);

		if (AppContext::get_current_user()->is_guest())
			$search_category_children_options->set_allow_only_member_level_authorizations(ModConfig::load()->are_descriptions_displayed_to_guests());

		$categories = self::get_categories_manager()->get_children($current_category_id, $search_category_children_options, true);
		return array_keys($categories);
	}

	public static function get_categories_manager()
	{
		if (self::$categories_manager === null)
		{
			$categories_items_parameters = new CategoriesItemsParameters();
			$categories_items_parameters->set_table_name_contains_items(ModSetup::$items_table);
			self::$categories_manager = new CategoriesManager(ModCategoriesCache::load(), $categories_items_parameters);
		}
		return self::$categories_manager;
	}

	public static function get_keywords_manager()
	{
		if (self::$keywords_manager === null)
		{
			self::$keywords_manager = new KeywordsManager(ModKeywordsCache::load());
		}
		return self::$keywords_manager;
	}
}
?>
