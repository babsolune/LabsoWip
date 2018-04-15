<?php
/*##################################################
 *		                   PalmaresConfig.class.php
 *                            -------------------
 *   begin                : February 13, 2013
 *   copyright            : (C) 2013 Kevin MASSY
 *   email                : kevin.massy@phpboost.com
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
 * @author Kevin MASSY <kevin.massy@phpboost.com>
 */
class PalmaresConfig extends AbstractConfigData
{
	const NUMBER_PALMARES_PER_PAGE = 'number_palmares_per_page';
	const NUMBER_COLUMNS_DISPLAY_PALMARES = 'number_columns_display_palmares';
	
	const DISPLAY_CONDENSED_ENABLED = 'display_condensed_enabled';
	const DESCRIPTIONS_DISPLAYED_TO_GUESTS = 'descriptions_displayed_to_guests';
	const NUMBER_CHARACTER_TO_CUT = 'number_character_to_cut';
	
	const PALMARES_SUGGESTIONS_ENABLED = 'palmares_suggestions_enabled';
	const AUTHOR_DISPLAYED = 'author_displayed';
	const NB_VIEW_ENABLED = 'nb_view_enabled';

	const DISPLAY_TYPE = 'display_type';
	const DISPLAY_BLOCK = 'block';
	const DISPLAY_LIST = 'list';
	
	const DEFERRED_OPERATIONS = 'deferred_operations';
	
	const AUTHORIZATIONS = 'authorizations';
	
	public function get_number_palmares_per_page()
	{
		return $this->get_property(self::NUMBER_PALMARES_PER_PAGE);
	}

	public function set_number_palmares_per_page($number_palmares_per_page)
	{
		$this->set_property(self::NUMBER_PALMARES_PER_PAGE, $number_palmares_per_page);
	}
	
	public function get_number_columns_display_palmares()
	{
		return $this->get_property(self::NUMBER_COLUMNS_DISPLAY_PALMARES);
	}

	public function set_number_columns_display_palmares($number_columns_display_palmares)
	{
		$this->set_property(self::NUMBER_COLUMNS_DISPLAY_PALMARES, $number_columns_display_palmares);
	}
	
	public function get_display_condensed_enabled()
	{
		return $this->get_property(self::DISPLAY_CONDENSED_ENABLED);
	}

	public function set_display_condensed_enabled($display_condensed_enabled)
	{
		$this->set_property(self::DISPLAY_CONDENSED_ENABLED, $display_condensed_enabled);
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
	
	public function get_number_character_to_cut()
	{
		return $this->get_property(self::NUMBER_CHARACTER_TO_CUT);
	}

	public function set_number_character_to_cut($number)
	{
		$this->set_property(self::NUMBER_CHARACTER_TO_CUT, $number);
	}
		
	public function get_palmares_suggestions_enabled()
	{
		return $this->get_property(self::PALMARES_SUGGESTIONS_ENABLED);
	}

	public function set_palmares_suggestions_enabled($palmares_suggestions_enabled)
	{
		$this->set_property(self::PALMARES_SUGGESTIONS_ENABLED, $palmares_suggestions_enabled);
	}
	
	public function get_author_displayed()
	{
		return $this->get_property(self::AUTHOR_DISPLAYED);
	}

	public function set_author_displayed($author_displayed)
	{
		$this->set_property(self::AUTHOR_DISPLAYED, $author_displayed);
	}
	
	public function get_nb_view_enabled()
	{
		return $this->get_property(self::NB_VIEW_ENABLED);
	}

	public function set_nb_view_enabled($nb_view_enabled)
	{
		$this->set_property(self::NB_VIEW_ENABLED, $nb_view_enabled);
	}
	
	public function get_display_type()
	{
		return $this->get_property(self::DISPLAY_TYPE);
	}

	public function set_display_type($display_type)
	{
		$this->set_property(self::DISPLAY_TYPE, $display_type);
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

	/**
	 * {@inheritdoc}
	 */
	public function get_default_values()
	{
		return array(
			self::NUMBER_PALMARES_PER_PAGE => 10,
			self::NUMBER_COLUMNS_DISPLAY_PALMARES => 1,
			self::DISPLAY_CONDENSED_ENABLED => false,
			self::DESCRIPTIONS_DISPLAYED_TO_GUESTS => false,
			self::NUMBER_CHARACTER_TO_CUT => 250,
			self::PALMARES_SUGGESTIONS_ENABLED => true,
			self::AUTHOR_DISPLAYED => true,
			self::NB_VIEW_ENABLED => false,
			self::DISPLAY_TYPE => self::DISPLAY_LIST,
			self::AUTHORIZATIONS => array('r-1' => 1, 'r0' => 5, 'r1' => 13),
			self::DEFERRED_OPERATIONS => array()
		);
	}

	/**
	 * Returns the configuration.
	 * @return PalmaresConfig
	 */
	public static function load()
	{
		return ConfigManager::load(__CLASS__, 'palmares', 'config');
	}

	/**
	 * Saves the configuration in the database. Has it become persistent.
	 */
	public static function save()
	{
		ConfigManager::save('palmares', self::load(), 'config');
	}
}
?>