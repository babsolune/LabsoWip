<?php
/*##################################################
 *                               ClubsConfig.class.php
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

class ClubsConfig extends AbstractConfigData
{
	const ITEMS_NUMBER_PER_PAGE = 'items_number_per_page';
	const CATEGORIES_NUMBER_PER_PAGE = 'categories_number_per_page';
	const COLUMNS_NUMBER_PER_LINE = 'columns_number_per_line';
	const CATEGORY_DISPLAY_TYPE = 'category_display_type';
	const ROOT_CATEGORY_DESCRIPTION = 'root_category_description';
	const AUTHORIZATIONS = 'authorizations';

    const NEW_WINDOW = 'new_window';
    const SPORT_TYPE = 'sport_type';
    const GMAP_API = 'gmap_api';

	const DISPLAY_ALL_CONTENT = 'all_content';
	const DISPLAY_TABLE = 'table';

    const DEFAULT_ADDRESS = "default_address";
    const DEFAULT_LATITUDE = "default_latitude";
    const DEFAULT_LONGITUDE = "default_longitude";

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

	public function is_category_displayed_table()
	{
		return $this->get_property(self::CATEGORY_DISPLAY_TYPE) == self::DISPLAY_TABLE;
	}

	public function get_root_category_description()
	{
		return $this->get_property(self::ROOT_CATEGORY_DESCRIPTION);
	}

	public function set_root_category_description($value)
	{
		$this->set_property(self::ROOT_CATEGORY_DESCRIPTION, $value);
	}

	public function is_gmap_api()
	{
		return ModulesManager::is_module_installed('GoogleMaps') && ModulesManager::is_module_activated('GoogleMaps') && GoogleMapsConfig::load()->get_api_key();
	}

	public function get_sport_type()
	{
		return $this->get_property(self::SPORT_TYPE);
	}

	public function set_sport_type($value)
	{
		$this->set_property(self::SPORT_TYPE, $value);
	}

	public function get_new_window()
	{
		return $this->get_property(self::NEW_WINDOW);
	}

	public function set_new_window($value)
	{
		$this->set_property(self::NEW_WINDOW, $value);
	}

	public function get_default_address()
	{
		return $this->get_property(self::DEFAULT_ADDRESS);
	}

	public function set_default_address($value)
	{
		$this->set_property(self::DEFAULT_ADDRESS, $value);
	}

	public function get_default_latitude()
	{
		return $this->get_property(self::DEFAULT_LATITUDE);
	}

	public function set_default_latitude($value)
	{
		$this->set_property(self::DEFAULT_LATITUDE, $value);
	}

	public function get_default_longitude()
	{
		return $this->get_property(self::DEFAULT_LONGITUDE);
	}

	public function set_default_longitude($value)
	{
		$this->set_property(self::DEFAULT_LONGITUDE, $value);
	}

	public function get_authorizations()
	{
		return $this->get_property(self::AUTHORIZATIONS);
	}

	public function set_authorizations(Array $authorizations)
	{
		$this->set_property(self::AUTHORIZATIONS, $authorizations);
	}

	/**
	 * {@inheritdoc}
	 */
	public function get_default_values()
	{
		return array(
			self::ITEMS_NUMBER_PER_PAGE => 15,
			self::CATEGORIES_NUMBER_PER_PAGE => 10,
			self::COLUMNS_NUMBER_PER_LINE => 3,
			self::CATEGORY_DISPLAY_TYPE => self::DISPLAY_TABLE,
			self::ROOT_CATEGORY_DESCRIPTION => LangLoader::get_message('root_category_description', 'config', 'clubs'),
			self::AUTHORIZATIONS => array('r-1' => 1, 'r0' => 5, 'r1' => 13),
            self::SPORT_TYPE => 0,
            self::NEW_WINDOW => false,
            self::DEFAULT_ADDRESS => '',
            self::DEFAULT_LATITUDE => '',
            self::DEFAULT_LONGITUDE => ''
		);
	}

	/**
	 * Returns the configuration.
	 * @return ClubsConfig
	 */
	public static function load()
	{
		return ConfigManager::load(__CLASS__, 'clubs', 'config');
	}

	/**
	 * Saves the configuration in the database. Has it become persistent.
	 */
	public static function save()
	{
		ConfigManager::save('clubs', self::load(), 'config');
	}
}
?>
