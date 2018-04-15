<?php
/*##################################################
 *		                   NetworkLinksConfig.class.php
 *                            -------------------
 *   begin                : March 26, 2017
 *   copyright            : (C) 2017 Julien BRISWALTER
 *   email                : j1.seth@phpboost.com
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
 * @author Julien BRISWALTER <j1.seth@phpboost.com>
 */
class NetworkLinksConfig extends AbstractConfigData
{
	const OPEN_NEW_WINDOW = 'open_new_window';
	const LINK_DATA = 'link_data';

	public function get_open_new_window()
	{
		return $this->get_property(self::OPEN_NEW_WINDOW);
	}

	public function set_open_new_window($open_new_window)
	{
		$this->set_property(self::OPEN_NEW_WINDOW, $open_new_window);
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
	 * {@inheritdoc}
	 */
	public function get_default_values()
	{
		return array(
			self::OPEN_NEW_WINDOW => false,
			self::LINK_DATA => array(),
		);
	}

	/**
	 * Returns the configuration.
	 * @return GoogleMapsConfig
	 */
	public static function load()
	{
		return ConfigManager::load(__CLASS__, 'network-links', 'config');
	}

	/**
	 * Saves the configuration in the database. Has it become persistent.
	 */
	public static function save()
	{
		ConfigManager::save('network-links', self::load(), 'config');
	}
}
?>
