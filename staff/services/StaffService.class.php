<?php
/**
 * @copyright 	&copy; 2005-2019 PHPBoost
 * @license 	https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @version   	PHPBoost 5.2 - last update: 2017 11 05
 * @since   	PHPBoost 5.1 - 2017 06 29
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
	 * @desc Return the properties of roles.
	 */
	public static function get_options_config()
	{
		$row = self::$db_querier->select_single_row_query('SELECT config.*
		FROM ' . StaffSetup::$staff_config_table . ' config
		');

		$options_config = new OptionsConfig();
		$options_config->set_properties($row);
		return $options_config;
	}

	public static function update_options_config($roles)
	{
		self::$db_querier->update(StaffSetup::$staff_config_table, array('roles' => $roles), 'WHERE id = 1');
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
