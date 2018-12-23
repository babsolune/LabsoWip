<?php

/**
 * @package 	SingleMenu
 * @subpackage 	Phpboost
 * @category 	Modules
 * @copyright 	&copy; 2005-2019 PHPBoost
 * @license 	https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @version   	PHPBoost 5.2 - last update: 2016 04 21
 * @since   	PHPBoost 5.0 - 2016 04 21
 */

class SingleMenuConfig extends AbstractConfigData
{
	const MENU_TITLE = 'menu_title';
	const OPEN_NEW_WINDOW = 'open_new_window';
	const LINK_DATA = 'link_data';
	const AUTHORIZATIONS = 'authorizations';

	public function get_menu_title()
	{
		return $this->get_property(self::MENU_TITLE);
	}

	public function set_menu_title($menu_title)
	{
		$this->set_property(self::MENU_TITLE, $menu_title);
	}

	public function enable_new_window()
	{
		$this->set_property(self::OPEN_NEW_WINDOW, true);
	}

	public function disable_new_window() {
		$this->set_property(self::OPEN_NEW_WINDOW, false);
	}

	public function is_new_window()
	{
		return $this->get_property(self::OPEN_NEW_WINDOW);
	}

	public function get_link_data()
	{
		return $this->get_property(self::LINK_DATA);
	}

	public function set_link_data($link_data)
	{
		$this->set_property(self::LINK_DATA, $link_data);
	}

	 /**
	 * @method Get authorizations
	 */
	public function get_authorizations()
	{
		return $this->get_property(self::AUTHORIZATIONS);
	}

	 /**
	 * @method Set authorizations
	 * @params string[] $array Array of authorizations
	 */
	public function set_authorizations(Array $array)
	{
		$this->set_property(self::AUTHORIZATIONS, $array);
	}

	/**
	 * {@inheritdoc}
	 */
	public function get_default_values()
	{
		return array(
			self::MENU_TITLE => LangLoader::get_message('sgm.module.title', 'common', 'SingleMenu'),
			self::OPEN_NEW_WINDOW => false,
			self::LINK_DATA => array(),
			self::AUTHORIZATIONS => array('r-1' => 3, 'r0' => 3, 'r1' => 7)
		);
	}

	/**
	 * Returns the configuration.
	 * @return SingleMenuConfig
	 */
	public static function load()
	{
		return ConfigManager::load(__CLASS__, 'single-menu', 'config');
	}

	/**
	 * Saves the configuration in the database. Has it become persistent.
	 */
	public static function save()
	{
		ConfigManager::save('single-menu', self::load(), 'config');
	}
}
?>
