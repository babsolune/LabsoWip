<?php
/**
 * @copyright 	&copy; 2005-2019 PHPBoost
 * @license 	https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version   	PHPBoost 5.2 - last update: 2018 05 25
 * @since   	PHPBoost 4.1 - 2015 06 29
*/

class WikiCategoriesCache extends CategoriesCache
{
	public function get_table_name()
	{
		return WikiSetup::$wiki_cats_table;
	}

	public function get_category_class()
	{
		return 'WikiCategory';
	}

	public function get_module_identifier()
	{
		return 'wiki';
	}

	protected function get_category_elements_number($id_category)
	{
		$now = new Date();
		return WikiService::count('WHERE id_category = :id_category AND (published = 1 OR (published = 2 AND publishing_start_date < :timestamp_now AND (publishing_end_date > :timestamp_now OR publishing_end_date = 0)))',
			array(
				'timestamp_now' => $now->get_timestamp(),
				'id_category' => $id_category
			)
		);
	}

	public function get_root_category()
	{
		$root = new RichRootCategory();
		$root->set_authorizations(WikiConfig::load()->get_authorizations());
		$root->set_description(WikiConfig::load()->get_root_category_description());
		return $root;
	}
}
?>
