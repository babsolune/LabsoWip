<?php
/*##################################################
 *                               CatalogConfig.class.php
 *                            -------------------
 *   begin                : August 24, 2014
 *   copyright            : (C) 2014 Julien BRISWALTER
 *   email                : j1.seth@phpboost.com
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
 * @author Julien BRISWALTER <j1.seth@phpboost.com>
 */

class CatalogConfig extends AbstractConfigData
{
	const PRICE_UNIT = 'price_unit';
	const FLASH_SALES_ENABLED = 'flash_sales_enabled';
	const FLASH_PRODUCTS_NB = 'flash_products_nb';
	const LAST_PRODUCTS_ENABLED = 'last_products_enabled';
	const LAST_PRODUCTS_NB = 'last_products_nb';
	const LAST_PROMOTED_PRODUCTS_ENABLED = 'last_promoted_products_enabled';
	const LAST_PROMOTED_PRODUCTS_NB = 'last_promoted_products_nb';

	const ITEMS_NUMBER_PER_PAGE = 'items_number_per_page';
	const CATEGORIES_NUMBER_PER_PAGE = 'categories_number_per_page';
	const COLUMNS_NUMBER_PER_LINE = 'columns_number_per_line';
	const CATEGORY_DISPLAY_TYPE = 'category_display_type';
	const DESCRIPTIONS_DISPLAYED_TO_GUESTS = 'descriptions_displayed_to_guests';
	const AUTHOR_DISPLAYED = 'author_displayed';
	const NB_VIEW_ENABLED = 'nb_view_enabled';
	const ROOT_CATEGORY_DESCRIPTION = 'root_category_description';
	const SORT_TYPE = 'sort_type';
	const PRODUCTS_NUMBER_IN_MENU = 'products_number_in_menu';
	const LIMIT_OLDEST_FILE_DAY_IN_MENU_ENABLED = 'limit_oldest_product_day_in_menu_enabled';
	const OLDEST_FILE_DAY_IN_MENU = 'oldest_product_day_in_menu';
	const AUTHORIZATIONS = 'authorizations';

	const DISPLAY_SUMMARY = 'summary';
	const DISPLAY_ALL_CONTENT = 'all_content';
	const DISPLAY_TABLE = 'table';

	const DEFERRED_OPERATIONS = 'deferred_operations';

	const GCS_TEXT = 'gcs_text';

	const NUMBER_CARACTERS_BEFORE_CUT = 250;

	public function get_flash_sales_enabled()
	{
		return $this->get_property(self::FLASH_SALES_ENABLED);
	}

	public function set_flash_sales_enabled($flash_sales_enabled)
	{
		$this->set_property(self::FLASH_SALES_ENABLED, $flash_sales_enabled);
	}

	public function get_flash_products_nb()
	{
		return $this->get_property(self::FLASH_PRODUCTS_NB);
	}

	public function set_flash_products_nb($flash_products_nb)
	{
		$this->set_property(self::FLASH_PRODUCTS_NB, $flash_products_nb);
	}

	public function get_last_products_enabled()
	{
		return $this->get_property(self::LAST_PRODUCTS_ENABLED);
	}

	public function set_last_products_enabled($last_products_enabled)
	{
		$this->set_property(self::LAST_PRODUCTS_ENABLED, $last_products_enabled);
	}

	public function get_last_products_nb()
	{
		return $this->get_property(self::LAST_PRODUCTS_NB);
	}

	public function set_last_products_nb($last_products_nb)
	{
		$this->set_property(self::LAST_PRODUCTS_NB, $last_products_nb);
	}

	public function get_last_promoted_products_enabled()
	{
		return $this->get_property(self::LAST_PROMOTED_PRODUCTS_ENABLED);
	}

	public function set_last_promoted_products_enabled($last_promoted_products_enabled)
	{
		$this->set_property(self::LAST_PROMOTED_PRODUCTS_ENABLED, $last_promoted_products_enabled);
	}

	public function get_last_promoted_products_nb()
	{
		return $this->get_property(self::LAST_PROMOTED_PRODUCTS_NB);
	}

	public function set_last_promoted_products_nb($last_promoted_products_nb)
	{
		$this->set_property(self::LAST_PROMOTED_PRODUCTS_NB, $last_promoted_products_nb);
	}

	public function get_items_number_per_page()
	{
		return $this->get_property(self::ITEMS_NUMBER_PER_PAGE);
	}

	public function set_items_number_per_page($value)
	{
		$this->set_property(self::ITEMS_NUMBER_PER_PAGE, $value);
	}

	public function get_categories_number_per_page()
	{
		return $this->get_property(self::CATEGORIES_NUMBER_PER_PAGE);
	}

	public function set_categories_number_per_page($value)
	{
		$this->set_property(self::CATEGORIES_NUMBER_PER_PAGE, $value);
	}

	public function get_columns_number_per_line()
	{
		return $this->get_property(self::COLUMNS_NUMBER_PER_LINE);
	}

	public function set_columns_number_per_line($value)
	{
		$this->set_property(self::COLUMNS_NUMBER_PER_LINE, $value);
	}

	public function get_category_display_type()
	{
		return $this->get_property(self::CATEGORY_DISPLAY_TYPE);
	}

	public function set_category_display_type($value)
	{
		$this->set_property(self::CATEGORY_DISPLAY_TYPE, $value);
	}

	public function is_category_displayed_summary()
	{
		return $this->get_property(self::CATEGORY_DISPLAY_TYPE) == self::DISPLAY_SUMMARY;
	}

	public function is_category_displayed_table()
	{
		return $this->get_property(self::CATEGORY_DISPLAY_TYPE) == self::DISPLAY_TABLE;
	}

	public function display_descriptions_to_guests()
	{
		$this->set_property(self::DESCRIPTIONS_DISPLAYED_TO_GUESTS, true);
	}

	public function hide_descriptions_to_guests()
	{
		$this->set_property(self::DESCRIPTIONS_DISPLAYED_TO_GUESTS, false);
	}

	public function are_descriptions_displayed_to_guests()
	{
		return $this->get_property(self::DESCRIPTIONS_DISPLAYED_TO_GUESTS);
	}

	public function display_author()
	{
		$this->set_property(self::AUTHOR_DISPLAYED, true);
	}

	public function hide_author()
	{
		$this->set_property(self::AUTHOR_DISPLAYED, false);
	}

	public function is_author_displayed()
	{
		return $this->get_property(self::AUTHOR_DISPLAYED);
	}

	public function get_price_unit()
	{
		return $this->get_property(self::PRICE_UNIT);
	}

	public function set_price_unit($price_unit)
	{
		$this->set_property(self::PRICE_UNIT, $price_unit);
	}

	public function get_nb_view_enabled()
	{
		return $this->get_property(self::NB_VIEW_ENABLED);
	}

	public function set_nb_view_enabled($nb_view_enabled)
	{
		$this->set_property(self::NB_VIEW_ENABLED, $nb_view_enabled);
	}

	public function get_root_category_description()
	{
		return $this->get_property(self::ROOT_CATEGORY_DESCRIPTION);
	}

	public function set_root_category_description($value)
	{
		$this->set_property(self::ROOT_CATEGORY_DESCRIPTION, $value);
	}

	public function get_sort_type()
	{
		return $this->get_property(self::SORT_TYPE);
	}

	public function set_sort_type($value)
	{
		$this->set_property(self::SORT_TYPE, $value);
	}

	public function is_sort_type_date()
	{
		return $this->get_property(self::SORT_TYPE) == Product::SORT_DATE || $this->get_property(self::SORT_TYPE) == Product::SORT_UPDATED_DATE;
	}

	public function is_sort_type_number_downloads()
	{
		return $this->get_property(self::SORT_TYPE) == Product::SORT_NUMBER_DOWNLOADS;
	}

	public function is_sort_type_number_views()
	{
		return $this->get_property(self::SORT_TYPE) == Product::SORT_NUMBER_VIEWS;
	}

	public function is_sort_type_notation()
	{
		return $this->get_property(self::SORT_TYPE) == Product::SORT_NOTATION;
	}

	public function get_products_number_in_menu()
	{
		return $this->get_property(self::PRODUCTS_NUMBER_IN_MENU);
	}

	public function set_products_number_in_menu($value)
	{
		$this->set_property(self::PRODUCTS_NUMBER_IN_MENU, $value);
	}

	public function enable_limit_oldest_product_day_in_menu()
	{
		$this->set_property(self::LIMIT_OLDEST_FILE_DAY_IN_MENU_ENABLED, true);
	}

	public function disable_limit_oldest_product_day_in_menu()
	{
		$this->set_property(self::LIMIT_OLDEST_FILE_DAY_IN_MENU_ENABLED, false);
	}

	public function is_limit_oldest_product_day_in_menu_enabled()
	{
		return $this->get_property(self::LIMIT_OLDEST_FILE_DAY_IN_MENU_ENABLED);
	}

	public function get_oldest_product_day_in_menu()
	{
		return $this->get_property(self::OLDEST_FILE_DAY_IN_MENU);
	}

	public function set_oldest_product_day_in_menu($value)
	{
		$this->set_property(self::OLDEST_FILE_DAY_IN_MENU, $value);
	}

	public function get_authorizations()
	{
		return $this->get_property(self::AUTHORIZATIONS);
	}

	public function set_authorizations(Array $authorizations)
	{
		$this->set_property(self::AUTHORIZATIONS, $authorizations);
	}

	public function get_deferred_operations()
	{
		return $this->get_property(self::DEFERRED_OPERATIONS);
	}

	public function set_deferred_operations(Array $deferred_operations)
	{
		$this->set_property(self::DEFERRED_OPERATIONS, $deferred_operations);
	}

	public function get_gcs_text()
	{
		return $this->get_property(self::GCS_TEXT);
	}

	public function set_gcs_text($gcs_text)
	{
		$this->set_property(self::GCS_TEXT, $gcs_text);
	}

	/**
	 * {@inheritdoc}
	 */
	public function get_default_values()
	{
		return array(
			self::PRICE_UNIT => '€',
			self::FLASH_SALES_ENABLED => false,
			self::FLASH_PRODUCTS_NB => 5,
			self::LAST_PRODUCTS_ENABLED => false,
			self::LAST_PRODUCTS_NB => 5,
			self::LAST_PROMOTED_PRODUCTS_ENABLED => false,
			self::LAST_PROMOTED_PRODUCTS_NB => 5,
			self::ITEMS_NUMBER_PER_PAGE => 15,
			self::CATEGORIES_NUMBER_PER_PAGE => 10,
			self::COLUMNS_NUMBER_PER_LINE => 3,
			self::CATEGORY_DISPLAY_TYPE => self::DISPLAY_SUMMARY,
			self::DESCRIPTIONS_DISPLAYED_TO_GUESTS => false,
			self::AUTHOR_DISPLAYED => true,
			self::NB_VIEW_ENABLED => false,
			self::ROOT_CATEGORY_DESCRIPTION => LangLoader::get_message('root_category_description', 'config', 'catalog'),
			self::SORT_TYPE => Product::SORT_NUMBER_DOWNLOADS,
			self::PRODUCTS_NUMBER_IN_MENU => 5,
			self::LIMIT_OLDEST_FILE_DAY_IN_MENU_ENABLED => false,
			self::OLDEST_FILE_DAY_IN_MENU => 30,
			self::AUTHORIZATIONS => array('r-1' => 33, 'r0' => 53, 'r1' => 61),
			self::DEFERRED_OPERATIONS => array(),
			self::GCS_TEXT => LangLoader::get_message('default.gcs', 'gcs', 'catalog'),
		);
	}

	/**
	 * Returns the configuration.
	 * @return CatalogConfig
	 */
	public static function load()
	{
		return ConfigManager::load(__CLASS__, 'catalog', 'config');
	}

	/**
	 * Saves the configuration in the database. Has it become persistent.
	 */
	public static function save()
	{
		ConfigManager::save('catalog', self::load(), 'config');
	}
}
?>
