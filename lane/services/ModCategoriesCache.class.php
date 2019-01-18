<?php
/**
 * @copyright 	&copy; 2005-2019 PHPBoost https://www.phpboost.com
 * @license 	https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER [j1.seth@phpboost.com]
 * @version   	PHPBoost 5.2 - last update: 2018 05 25
 * @since   	PHPBoost 4.1 - 2015 06 29
*/

namespace Wiki\services;
use \Wiki\services\ModServices;
use \Wiki\services\ModSetup;
use \Wiki\phpboost\ModConfig;

class ModCategoriesCache extends CategoriesCache
{
	public function get_table_name()
	{
		return ModSetup::$categories_table;
	}

	public function get_category_class()
	{
		return 'Category';
	}

	public function get_module_identifier()
	{
		return 'wiki';
	}

	protected function get_category_elements_number($category_id)
	{
		$now = new Date();
		return ModServices::count('WHERE category_id = :category_id AND (published = 1 OR (published = 2 AND publishing_start_date < :timestamp_now AND (publishing_end_date > :timestamp_now OR publishing_end_date = 0)))',
			array(
				'timestamp_now' => $now->get_timestamp(),
				'category_id' => $category_id
			)
		);
	}

	public function get_root_category()
	{
		$root = new RichRootCategory();
		$root->set_authorizations(ModConfig::load()->get_authorizations());
		$root->set_description(ModConfig::load()->get_root_category_description());
		return $root;
	}
}
?>
