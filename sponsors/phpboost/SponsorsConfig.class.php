<?php
/*##################################################
 *		                   SponsorsConfig.class.php
 *                            -------------------
 *   begin                : May 20, 2018
 *   copyright            : (C) 2018 Sebastien LARTIGUE
 *   email                : babsolune@phpboost.com
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
 * @author Sebastien LARTIGUE <babsolune@phpboost.com>
 */

class SponsorsConfig extends AbstractConfigData
{
	// Configuration
	const NEW_WINDOW = 'new_window';
	const ITEMS_NUMBER_PER_LINE = 'items_number_per_line';
	const PARTNERSHIP_LEVELS = 'partnership_levels';
	const ROOT_CATEGORY_DESCRIPTION = 'root_category_description';
	const DEFERRED_OPERATIONS = 'deferred_operations';
	const AUTHORIZATIONS = 'authorizations';

	// Mini Menu
	const MINI_MENU_ITEMS_NB = 'mini_menu_items_nb';
	const MINI_MENU_ANIMATION_SPEED = 'mini_menu_animation_speed';
	const MINI_MENU_AUTOPLAY = 'mini_menu_autoplay';
	const MINI_MENU_AUTOPLAY_SPEED = 'mini_menu_autoplay_speed';
	const MINI_MENU_AUTOPLAY_HOVER = 'mini_menu_autoplay_hover';

	// Membership terms
	const MEMBERSHIP_TERMS = 'membership_terms';

	// Configuration

	public function open_new_window()
	{
		$this->set_property(self::NEW_WINDOW, true);
	}

	public function no_new_window()
	{
		$this->set_property(self::NEW_WINDOW, false);
	}

	public function is_new_window()
	{
		return $this->get_property(self::NEW_WINDOW);
	}

	public function get_items_number_per_line()
	{
		return $this->get_property(self::ITEMS_NUMBER_PER_LINE);
	}

	public function set_items_number_per_line($number)
	{
		$this->set_property(self::ITEMS_NUMBER_PER_LINE, $number);
	}

	public function get_root_category_description()
	{
		return $this->get_property(self::ROOT_CATEGORY_DESCRIPTION);
	}

	public function set_root_category_description($value)
	{
		$this->set_property(self::ROOT_CATEGORY_DESCRIPTION, $value);
	}

	public function get_partnership_levels()
	{
		return $this->get_property(self::PARTNERSHIP_LEVELS);
	}

	public function set_partnership_levels(Array $partnership_levels)
	{
		$this->set_property(self::PARTNERSHIP_LEVELS, $partnership_levels);
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

	// Mini Menu
	public function get_mini_menu_items_nb()
	{
		return $this->get_property(self::MINI_MENU_ITEMS_NB);
	}

	public function set_mini_menu_items_nb($number)
	{
		$this->set_property(self::MINI_MENU_ITEMS_NB, $number);
	}

	public function get_mini_menu_animation_speed()
	{
		return $this->get_property(self::MINI_MENU_ANIMATION_SPEED);
	}

	public function set_mini_menu_animation_speed($number)
	{
		$this->set_property(self::MINI_MENU_ANIMATION_SPEED, $number);
	}

	public function play_mini_menu_autoplay()
	{
		$this->set_property(self::MINI_MENU_AUTOPLAY, true);
	}

	public function stop_mini_menu_autoplay()
	{
		$this->set_property(self::MINI_MENU_AUTOPLAY, false);
	}

	public function is_slideshow_autoplayed()
	{
		return $this->get_property(self::MINI_MENU_AUTOPLAY);
	}

	public function get_mini_menu_autoplay_speed()
	{
		return $this->get_property(self::MINI_MENU_AUTOPLAY_SPEED);
	}

	public function set_mini_menu_autoplay_speed($number)
	{
		$this->set_property(self::MINI_MENU_AUTOPLAY_SPEED, $number);
	}

	public function play_mini_menu_autoplay_hover()
	{
		$this->set_property(self::MINI_MENU_AUTOPLAY_HOVER, true);
	}

	public function stop_mini_menu_autoplay_hover()
	{
		$this->set_property(self::MINI_MENU_AUTOPLAY_HOVER, false);
	}

	public function is_slideshow_hover_enabled()
	{
		return $this->get_property(self::MINI_MENU_AUTOPLAY_HOVER);
	}

	// Membership terms
	public function get_membership_terms()
	{
		return $this->get_property(self::MEMBERSHIP_TERMS);
	}

	public function set_membership_terms($value)
	{
		$this->set_property(self::MEMBERSHIP_TERMS, $value);
	}

	public function get_default_values()
	{
		$config_lang = LangLoader::get('install', 'sponsors');
		return array(
			// Categories
			self::NEW_WINDOW => true,
			self::ITEMS_NUMBER_PER_LINE => 4,
			self::PARTNERSHIP_LEVELS => array(1 => LangLoader::get_message('default.level', 'config', 'sponsors')),
			self::ROOT_CATEGORY_DESCRIPTION => LangLoader::get_message('root_category_description', 'config', 'sponsors'),
			self::DEFERRED_OPERATIONS => array(),
			self::AUTHORIZATIONS => array('r-1' => 1, 'r0' => 5, 'r1' => 13),

			// Mini Menu
			self::MINI_MENU_ITEMS_NB => 5,
			self::MINI_MENU_ANIMATION_SPEED => '1000',
			self::MINI_MENU_AUTOPLAY => true,
			self::MINI_MENU_AUTOPLAY_SPEED => '3000',
			self::MINI_MENU_AUTOPLAY_HOVER => true,

			// Usage Terms
			self::MEMBERSHIP_TERMS => LangLoader::get_message('config.membership.terms.conditions', 'install', 'sponsors'),
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
