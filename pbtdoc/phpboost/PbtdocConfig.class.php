<?php
/*##################################################
 *		                   PbtdocConfig.class.php
 *                            -------------------
 *   begin                : February 27, 2013
 *   copyright            : (C) 2013 Patrick DUBEAU
 *   email                : daaxwizeman@gmail.com
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
 * @author Patrick DUBEAU <daaxwizeman@gmail.com>
 */
class PbtdocConfig extends AbstractConfigData
{
	const NUMBER_ITEMS_PER_PAGE = 'number_items_per_page';
	const NUMBER_CATEGORIES_PER_PAGE = 'number_categories_per_page';
	const NUMBER_COLS_DISPLAY_PER_LINE = 'number_cols_display_per_line';
	const NUMBER_CHARACTER_TO_CUT = 'number_character_to_cut';

	const CATS_ICON_ENABLED = 'cats_icon_enabled';
	const DESCRIPTIONS_DISPLAYED_TO_GUESTS = 'descriptions_displayed_to_guests';
	const DATE_UPDATED_DISPLAYED = 'date_updated_displayed';
	const ROOT_CATEGORY_DESCRIPTION = 'root_category_description';

	const DISPLAY_TYPE   = 'display_type';
	const DISPLAY_MOSAIC = 'mosaic';
	const DISPLAY_LIST   = 'list';
	const DISPLAY_TABLE  = 'table';

	const DEFERRED_OPERATIONS = 'deferred_operations';

	const AUTHORIZATIONS = 'authorizations';

	public function get_number_items_per_page()
	{
		return $this->get_property(self::NUMBER_ITEMS_PER_PAGE);
	}

	public function set_number_items_per_page($number)
	{
		$this->set_property(self::NUMBER_ITEMS_PER_PAGE, $number);
	}

	public function get_number_categories_per_page()
	{
		return $this->get_property(self::NUMBER_CATEGORIES_PER_PAGE);
	}

	public function set_number_categories_per_page($number)
	{
		$this->set_property(self::NUMBER_CATEGORIES_PER_PAGE, $number);
	}

	public function get_number_cols_display_per_line()
	{
		return $this->get_property(self::NUMBER_COLS_DISPLAY_PER_LINE);
	}

	public function set_number_cols_display_per_line($number)
	{
		$this->set_property(self::NUMBER_COLS_DISPLAY_PER_LINE, $number);
	}

	public function get_number_character_to_cut()
	{
		return $this->get_property(self::NUMBER_CHARACTER_TO_CUT);
	}

	public function set_number_character_to_cut($number)
	{
		$this->set_property(self::NUMBER_CHARACTER_TO_CUT, $number);
	}

	public function get_display_type()
	{
		return $this->get_property(self::DISPLAY_TYPE);
	}

	public function set_display_type($display_type)
	{
		$this->set_property(self::DISPLAY_TYPE, $display_type);
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

	public function enable_cats_icon()
	{
		$this->set_property(self::CATS_ICON_ENABLED, true);
	}

	public function disable_cats_icon() {
		$this->set_property(self::CATS_ICON_ENABLED, false);
	}

	public function are_cats_icon_enabled()
	{
		return $this->get_property(self::CATS_ICON_ENABLED);
	}

	public function get_date_updated_displayed()
	{
		return $this->get_property(self::DATE_UPDATED_DISPLAYED);
	}

	public function set_date_updated_displayed($date_updated_displayed)
	{
		$this->set_property(self::DATE_UPDATED_DISPLAYED, $date_updated_displayed);
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

	public function set_authorizations(Array $array)
	{
		$this->set_property(self::AUTHORIZATIONS, $array);
	}

	public function get_deferred_operations()
	{
		return $this->get_property(self::DEFERRED_OPERATIONS);
	}

	public function set_deferred_operations(Array $deferred_operations)
	{
		$this->set_property(self::DEFERRED_OPERATIONS, $deferred_operations);
	}

	public function get_default_values()
	{
		return array(
			self::NUMBER_ITEMS_PER_PAGE => 10,
			self::NUMBER_CATEGORIES_PER_PAGE => 10,
			self::NUMBER_COLS_DISPLAY_PER_LINE => 2,
			self::NUMBER_CHARACTER_TO_CUT => 128,
			self::CATS_ICON_ENABLED => false,
			self::DESCRIPTIONS_DISPLAYED_TO_GUESTS => false,
			self::DATE_UPDATED_DISPLAYED => false,
			self::DISPLAY_TYPE => self::DISPLAY_MOSAIC,
			self::ROOT_CATEGORY_DESCRIPTION => LangLoader::get_message('root_category_description', 'config', 'pbtdoc'),
			self::AUTHORIZATIONS => array('r-1' => 1, 'r0' => 5, 'r1' => 13),
			self::DEFERRED_OPERATIONS => array()
		);
	}

	/**
	 * Returns the configuration.
	 * @return PbtdocConfig
	 */
	public static function load()
	{
		return ConfigManager::load(__CLASS__, 'pbtdoc', 'config');
	}

	/**
	 * Saves the configuration in the database. Has it become persistent.
	 */
	public static function save()
	{
		ConfigManager::save('pbtdoc', self::load(), 'config');
	}
}
?>
