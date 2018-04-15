<?php
/*##################################################
 *                               ClubsService.class.php
 *                            -------------------
 *   begin                : June 23, 2017
 *   copyright            : (C) 2017 Sebastien LARTIGUE
 *   email                : babsolune@phpboost.com
 *
 *
 ###################################################
 *
 * This program is a free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
 ###################################################*/

 /**
 * @author Sebastien LARTIGUE <babsolune@phpboost.com>
 */

class ClubsService
{
	private static $db_querier;

	private static $categories_manager;

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
		return self::$db_querier->count(ClubsSetup::$clubs_table, $condition, $parameters);
	}

	 /**
	 * @desc Create a new entry in the database table.
	 * @param string[] $club : new Club
	 */
	public static function add(Club $club)
	{
		$result = self::$db_querier->insert(ClubsSetup::$clubs_table, $club->get_properties());

		return $result->get_last_inserted_id();
	}

	 /**
	 * @desc Update an entry.
	 * @param string[] $club : Club to update
	 */
	public static function update(Club $club)
	{
		self::$db_querier->update(ClubsSetup::$clubs_table, $club->get_properties(), 'WHERE id=:id', array('id' => $club->get_id()));
	}

	 /**
	 * @desc Update the number of views of a link.
	 * @param string[] $club : Club to update
	 */
	public static function update_number_views(Club $club)
	{
		self::$db_querier->update(ClubsSetup::$clubs_table, array('number_views' => $club->get_number_views()), 'WHERE id=:id', array('id' => $club->get_id()));
	}

	 /**
	 * @desc Delete an entry.
	 * @param string $condition : Restriction to apply to the list
	 * @param string[] $parameters : Parameters of the condition
	 */
	public static function delete($condition, array $parameters)
	{
		self::$db_querier->delete(ClubsSetup::$clubs_table, $condition, $parameters);
	}

	 /**
	 * @desc Return the properties of a club.
	 * @param string $condition : Restriction to apply to the list
	 * @param string[] $parameters : Parameters of the condition
	 */
	public static function get_club($condition, array $parameters)
	{
		$row = self::$db_querier->select_single_row_query('SELECT clubs.*, member.*, notes.average_notes, notes.number_notes, note.note
		FROM ' . ClubsSetup::$clubs_table . ' clubs
		LEFT JOIN ' . DB_TABLE_MEMBER . ' member ON member.user_id = clubs.author_user_id
		LEFT JOIN ' . DB_TABLE_AVERAGE_NOTES . ' notes ON notes.id_in_module = clubs.id AND notes.module_name = \'clubs\'
		LEFT JOIN ' . DB_TABLE_NOTE . ' note ON note.id_in_module = clubs.id AND note.module_name = \'clubs\' AND note.user_id = ' . AppContext::get_current_user()->get_id() . '
		' . $condition, $parameters);

		$club = new Club();
		$club->set_properties($row);
		return $club;
	}

	 /**
	 * @desc Return the authorized categories.
	 */
	public static function get_authorized_categories($current_id_category)
	{
		$search_category_children_options = new SearchCategoryChildrensOptions();
		$search_category_children_options->add_authorizations_bits(Category::READ_AUTHORIZATIONS);

		$categories = self::get_categories_manager()->get_children($current_id_category, $search_category_children_options, true);
		return array_keys($categories);
	}

	 /**
	 * @desc Return the categories manager.
	 */
	public static function get_categories_manager()
	{
		if (self::$categories_manager === null)
		{
			$categories_items_parameters = new CategoriesItemsParameters();
			$categories_items_parameters->set_table_name_contains_items(ClubsSetup::$clubs_table);
			self::$categories_manager = new CategoriesManager(ClubsCategoriesCache::load(), $categories_items_parameters);
		}
		return self::$categories_manager;
	}
}
?>
