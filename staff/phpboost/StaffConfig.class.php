<?php
/**
 * @copyright 	&copy; 2005-2019 PHPBoost
 * @license 	https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @version   	PHPBoost 5.2 - last update: 2017 11 05
 * @since   	PHPBoost 5.1 - 2017 06 29
*/

class StaffConfig extends AbstractConfigData
{
	const ROLE = 'role';
	const AVATARS = 'avatars';
	const SUB_CATEGORIES_NUMBER_PER_LINE = 'sub_categories_number_per_line';
	const ROOT_CATEGORY_DESCRIPTION = 'root_category_description';
	const AUTHORIZATIONS = 'authorizations';


	public function get_role()
	{
		return $this->get_property(self::ROLE);
	}

	public function set_role(Array $role)
	{
		$this->set_property(self::ROLE, $role);
	}

	public function show_avatars()
	{
		$this->set_property(self::AVATARS, true);
	}

	public function hide_avatars()
	{
		$this->set_property(self::AVATARS, false);
	}

	public function are_avatars_shown()
	{
		return $this->get_property(self::AVATARS);
	}

	public function get_sub_categories_nb()
	{
		return $this->get_property(self::SUB_CATEGORIES_NUMBER_PER_LINE);
	}

	public function set_sub_categories_nb($value)
	{
		$this->set_property(self::SUB_CATEGORIES_NUMBER_PER_LINE, $value);
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
		return array(
			self::ROLE => array(LangLoader::get_message('default.role', 'config', 'staff')),
			self::AVATARS => true,
			self::SUB_CATEGORIES_NUMBER_PER_LINE => 4,
			self::ROOT_CATEGORY_DESCRIPTION => LangLoader::get_message('root_category_description', 'config', 'staff'),
			self::AUTHORIZATIONS => array('r-1' => 1, 'r0' => 5, 'r1' => 13)
		);
	}

	/**
	 * Returns the configuration.
	 * @return StaffConfig
	 */
	public static function load()
	{
		return ConfigManager::load(__CLASS__, 'staff', 'config');
	}

	/**
	 * Saves the configuration in the database. Has it become persistent.
	 */
	public static function save()
	{
		ConfigManager::save('staff', self::load(), 'config');
	}
}
?>
