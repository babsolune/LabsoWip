<?php
/**
 * @copyright 	&copy; 2005-2019 PHPBoost
 * @license 	https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @version   	PHPBoost 5.2 - last update: 2017 11 05
 * @since   	PHPBoost 5.1 - 2017 06 29
*/

class StaffCategoriesCache extends CategoriesCache
{
	public function get_table_name()
	{
		return StaffSetup::$staff_cats_table;
	}

	public function get_category_class()
	{
		return CategoriesManager::RICH_CATEGORY_CLASS;
	}

	public function get_module_identifier()
	{
		return 'staff';
	}

	protected function get_category_elements_number($id_category)
	{
		$now = new Date();
		return StaffService::count('WHERE id_category = :id_category AND publication = 1',
			array(
				'timestamp_now' => $now->get_timestamp(),
				'id_category' => $id_category
			)
		);
	}

	public function get_root_category()
	{
		$root = new RichRootCategory();
		$root->set_authorizations(StaffConfig::load()->get_authorizations());
		$root->set_description(StaffConfig::load()->get_root_category_description());
		return $root;
	}
}
?>
