<?php
/*##################################################
 *                               StaffService.class.php
 *                            -------------------
 *   begin                : June 29, 2017
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
 * @author Seabstien LARTIGUE <babsolune@phpboost.com>
 */

class StaffService
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
		return self::$db_querier->count(StaffSetup::$staff_table, $condition, $parameters);
	}

	 /**
	 * @desc Create a new entry in the database table.
	 * @param string[] $adherent : new Adherent
	 */
	public static function add(Adherent $adherent)
	{
		$result = self::$db_querier->insert(StaffSetup::$staff_table, $adherent->get_properties());

		return $result->get_last_inserted_id();
	}

	 /**
	 * @desc Update an entry.
	 * @param string[] $adherent : Adherent to update
	 */
	public static function update(Adherent $adherent)
	{
		self::$db_querier->update(StaffSetup::$staff_table, $adherent->get_properties(), 'WHERE id=:id', array('id' => $adherent->get_id()));
	}

	 /**
	 * @desc Update the position of a adherent.
	 * @param string[] $course_id : id of the adherent to update
	 * @param string[] $position : new adherent position
	 */
	public static function update_position($adherent_id, $position)
	{
		self::$db_querier->update(StaffSetup::$staff_table, array('order_id' => $position), 'WHERE id=:id', array('id' => $adherent_id));
	}

	 /**
	 * @desc Delete an entry.
	 * @param string $condition : Restriction to apply to the list
	 * @param string[] $parameters : Parameters of the condition
	 */
	public static function delete($condition, array $parameters)
	{
		self::$db_querier->delete(StaffSetup::$staff_table, $condition, $parameters);
	}

	 /**
	 * @desc Return the properties of a adherent.
	 * @param string $condition : Restriction to apply to the list
	 * @param string[] $parameters : Parameters of the condition
	 */
	public static function get_adherent($condition, array $parameters)
	{
		$row = self::$db_querier->select_single_row_query('SELECT staff.*, member.*
		FROM ' . StaffSetup::$staff_table . ' staff
		LEFT JOIN ' . DB_TABLE_MEMBER . ' member ON member.user_id = staff.author_user_id
		' . $condition, $parameters);

		$adherent = new Adherent();
		$adherent->set_properties($row);
		return $adherent;
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
			$categories_items_parameters->set_table_name_contains_items(StaffSetup::$staff_table);
			self::$categories_manager = new CategoriesManager(StaffCategoriesCache::load(), $categories_items_parameters);
		}
		return self::$categories_manager;
	}
}
?>
