<?php
/*##################################################
 *                               SponsorsConfig.class.php
 *                            -------------------
 *   begin                : September 13, 2017
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

class SponsorsConfig extends AbstractConfigData
{
	const ITEMS_NUMBER_PER_PAGE = 'items_number_per_page';
	const CATEGORIES_NUMBER_PER_PAGE = 'categories_number_per_page';
	const COLUMNS_NUMBER_PER_LINE = 'columns_number_per_line';
	const CATEGORY_DISPLAY_TYPE = 'category_display_type';
	const DESCRIPTIONS_DISPLAYED_TO_GUESTS = 'descriptions_displayed_to_guests';
	const ROOT_CATEGORY_DESCRIPTION = 'root_category_description';
	const AUTHORIZATIONS = 'authorizations';

	const ACTIVITIES = 'activities';
	const TYPES = 'types';

	const DISPLAY_BLOCK = 'block';
	const DISPLAY_TABLE = 'table';

	const DEFERRED_OPERATIONS = 'deferred_operations';

	const NUMBER_CARACTERS_BEFORE_CUT = 250;

	public function get_types()
	{
		return $this->get_property(self::TYPES);
	}

	public function set_types(Array $array)
	{
		$this->set_property(self::TYPES, $array);
	}

	public function get_activities()
	{
		return $this->get_property(self::ACTIVITIES);
	}

	public function set_activities(Array $array)
	{
		$this->set_property(self::ACTIVITIES, $array);
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

	public function is_category_displayed_block()
	{
		return $this->get_property(self::CATEGORY_DISPLAY_TYPE) == self::DISPLAY_BLOCK;
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

	public function get_root_category_description()
	{
		return $this->get_property(self::ROOT_CATEGORY_DESCRIPTION);
	}

	public function set_root_category_description($value)
	{
		$this->set_property(self::ROOT_CATEGORY_DESCRIPTION, $value);
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
		$lang = LangLoader::get('config', 'sponsors');
		return array(
			self::TYPES => array(1 => $lang['types.platinium'], $lang['types.gold'], $lang['types.silver'], $lang['types.bronze']),
			self::ACTIVITIES => array(1 => $lang['activities.food']),
			self::ITEMS_NUMBER_PER_PAGE => 15,
			self::CATEGORIES_NUMBER_PER_PAGE => 10,
			self::COLUMNS_NUMBER_PER_LINE => 3,
			self::CATEGORY_DISPLAY_TYPE => self::DISPLAY_BLOCK,
			self::DESCRIPTIONS_DISPLAYED_TO_GUESTS => false,
			self::ROOT_CATEGORY_DESCRIPTION => LangLoader::get_message('root_category_description', 'config', 'sponsors'),
			self::AUTHORIZATIONS => array('r-1' => 1, 'r0' => 5, 'r1' => 13)
		);
	}

	/**
	 * Returns the configuration.
	 * @return SponsorsConfig
	 */
	public static function load()
	{
		return ConfigManager::load(__CLASS__, 'sponsors', 'config');
	}

	/**
	 * Saves the configuration in the database. Has it become persistent.
	 */
	public static function save()
	{
		ConfigManager::save('sponsors', self::load(), 'config');
	}
}
?>
