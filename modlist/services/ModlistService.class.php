<?php
/*##################################################
 *                        ModlistService.class.php
 *                            -------------------
 *   begin                : Month XX, 2017
 *   copyright            : (C) 2017 Firstname LASTNAME
 *   email                : nickname@phpboost.com
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
 * @author Firstname LASTNAME <nickname@phpboost.com>
 */

class ModlistService
{
	private static $db_querier;
	private static $categories_manager;
	private static $keywords_manager;

	public static function __static()
	{
		self::$db_querier = PersistenceContext::get_querier();
	}

	 /**
	 * @desc Count items number.
	 * @param string $condition (optional) : Restriction to apply to the list of items
	 */
	public static function count($condition = '', $parameters = array())
	{
		return self::$db_querier->count(ModlistSetup::$modlist_table, $condition, $parameters);
	}

	public static function add(Itemlist $itemlist)
	{
		$result = self::$db_querier->insert(ModlistSetup::$modlist_table, $itemlist->get_properties());
		return $result->get_last_inserted_id();
	}

	public static function update(Itemlist $itemlist)
	{
		self::$db_querier->update(ModlistSetup::$modlist_table, $itemlist->get_properties(), 'WHERE id=:id', array('id', $itemlist->get_id()));
	}

	public static function delete($condition, array $parameters)
	{
		self::$db_querier->delete(ModlistSetup::$modlist_table, $condition, $parameters);
	}

	public static function get_itemlist($condition, array $parameters)
	{
		$row = self::$db_querier->select_single_row_query('SELECT modlist.*, member.*, notes.average_notes, notes.number_notes, note.note
		FROM ' . ModlistSetup::$modlist_table . ' modlist
		LEFT JOIN ' . DB_TABLE_MEMBER . ' member ON member.user_id = modlist.author_user_id
		LEFT JOIN ' . DB_TABLE_AVERAGE_NOTES . ' notes ON notes.id_in_module = modlist.id AND notes.module_name = \'modlist\'
		LEFT JOIN ' . DB_TABLE_NOTE . ' note ON note.id_in_module = modlist.id AND note.module_name = \'modlist\' AND note.user_id = ' . AppContext::get_current_user()->get_id() . '
		' . $condition, $parameters);

		$itemlist = new Itemlist();
		$itemlist->set_properties($row);
		return $itemlist;
	}

	public static function update_views_number(Itemlist $itemlist)
	{
		self::$db_querier->update(ModlistSetup::$modlist_table, array('views_number' => $itemlist->get_views_number()), 'WHERE id=:id', array('id' => $itemlist->get_id()));
	}

	public static function get_authorized_categories($current_id_category)
	{
		$search_category_children_options = new SearchCategoryChildrensOptions();
		$search_category_children_options->add_authorizations_bits(Category::READ_AUTHORIZATIONS);

		if (AppContext::get_current_user()->is_guest())
			$search_category_children_options->set_allow_only_member_level_authorizations(ModlistConfig::load()->are_descriptions_displayed_to_guests());

		$categories = self::get_categories_manager()->get_children($current_id_category, $search_category_children_options, true);
		return array_keys($categories);
	}

	public static function get_categories_manager()
	{
		if (self::$categories_manager === null)
		{
			$categories_items_parameters = new CategoriesItemsParameters();
			$categories_items_parameters->set_table_name_contains_items(ModlistSetup::$modlist_table);
			self::$categories_manager = new CategoriesManager(ModlistCategoriesCache::load(), $categories_items_parameters);
		}
		return self::$categories_manager;
	}

	public static function get_keywords_manager()
	{
		if (self::$keywords_manager === null)
		{
			self::$keywords_manager = new KeywordsManager('modlist');
		}
		return self::$keywords_manager;
	}
}
?>
