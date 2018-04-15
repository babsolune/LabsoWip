<?php
/*##################################################
 *		                         RadioService.class.php
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

class RadioService
{
	private static $db_querier;

	private static $categories_manager;
	private static $keywords_manager;

	public static function __static()
	{
		self::$db_querier = PersistenceContext::get_querier();
	}

	public static function add(Radio $radio)
	{
		$result = self::$db_querier->insert(RadioSetup::$radio_table, $radio->get_properties());

		return $result->get_last_inserted_id();
	}

	public static function update(Radio $radio)
	{
		self::$db_querier->update(RadioSetup::$radio_table, $radio->get_properties(), 'WHERE id=:id', array('id' => $radio->get_id()));
	}

	public static function delete($condition, array $parameters)
	{
		self::$db_querier->delete(RadioSetup::$radio_table, $condition, $parameters);
	}

	public static function get_radio($condition, array $parameters = array())
	{
		$row = self::$db_querier->select_single_row_query('SELECT radio.*, member.*
		FROM ' . RadioSetup::$radio_table . ' radio
		LEFT JOIN ' . DB_TABLE_MEMBER . ' member ON member.user_id = radio.author_user_id
		' . $condition, $parameters);
		$radio = new Radio();
		$radio->set_properties($row);
		return $radio;
	}

	public static function get_authorized_categories($current_id_category)
	{
		$search_category_children_options = new SearchCategoryChildrensOptions();
		$search_category_children_options->add_authorizations_bits(Category::READ_AUTHORIZATIONS);

		$categories = self::get_categories_manager()->get_children($current_id_category, $search_category_children_options, true);
		return array_keys($categories);
	}

	public static function get_categories_manager()
	{
		if (self::$categories_manager === null)
		{
			$categories_items_parameters = new CategoriesItemsParameters();
			$categories_items_parameters->set_table_name_contains_items(RadioSetup::$radio_table);
			self::$categories_manager = new CategoriesManager(RadioCategoriesCache::load(), $categories_items_parameters);
		}
		return self::$categories_manager;
	}

	public static function get_keywords_manager()
	{
		if (self::$keywords_manager === null)
		{
			self::$keywords_manager = new KeywordsManager('radio');
		}
		return self::$keywords_manager;
	}
}
?>
