<?php
/*##################################################
 *		                   RadioConfig.class.php
 *                            -------------------
 *   begin                : May, 02, 2017
 *   copyright            : (C) 2017 Sebastien LARTIGUE
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

class RadioConfig extends AbstractConfigData
{
	const RADIO_NAME = 'radio_name';
	const RADIO_URL = 'radio_url';
	const RADIO_IMG = 'radio_img';
	const RADIO_POPUP = 'radio_popup';
	const RADIO_AUTOPLAY = 'radio_autoplay';

	const DOCUMENTATION = 'documentation';

	const AUTHORIZATIONS = 'authorizations';

	const DISPLAY_TYPE = 'display_type';
	const DISPLAY_BLOCK = 'block';
	const DISPLAY_TABLE = 'table';
	const DISPLAY_CALENDAR = 'calendar';

	public function get_radio_name()
	{
		return $this->get_property(self::RADIO_NAME);
	}

	public function set_radio_name($radio_name)
	{
		$this->set_property(self::RADIO_NAME, $radio_name);
	}

	public function get_radio_url()
	{
		return new Url($this->get_property(self::RADIO_URL));
	}

	public function set_radio_url($radio_url)
	{
		$this->set_property(self::RADIO_URL, $radio_url);
	}

	public function get_radio_img()
	{
		return new Url($this->get_property(self::RADIO_IMG));
	}

	public function set_radio_img($radio_img)
	{
		$this->set_property(self::RADIO_IMG, $radio_img);
	}

	public function is_radio_popup()
	{
		return $this->get_property(self::RADIO_POPUP);
	}

	public function set_radio_popup($radio_popup)
	{
		$this->set_property(self::RADIO_POPUP, $radio_popup);
	}

	public function is_radio_autoplay()
	{
		return $this->get_property(self::RADIO_AUTOPLAY);
	}

	public function set_radio_autoplay($radio_autoplay)
	{
		$this->set_property(self::RADIO_AUTOPLAY, $radio_autoplay);
	}

	public function get_display_type()
	{
		return $this->get_property(self::DISPLAY_TYPE);
	}

	public function set_display_type($display_type)
	{
		$this->set_property(self::DISPLAY_TYPE, $display_type);
	}

	public function get_documentation()
    {
        return $this->get_property(self::DOCUMENTATION);
    }

	public function set_documentation($value)
    {
        $this->set_property(self::DOCUMENTATION, $value);
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
			self::RADIO_NAME => 'PHPBoost',
			self::RADIO_URL => 'http://data.babsoweb.com/private/soundofyou.mp3',
			self::RADIO_IMG => '/radio/templates/images/default.jpg',
			self::RADIO_POPUP => false,
			self::RADIO_AUTOPLAY => false,
			self::DISPLAY_TYPE => self::DISPLAY_BLOCK,
			self::DOCUMENTATION => LangLoader::get_message('radio.documentation.content', 'common', 'radio'),
			self::AUTHORIZATIONS => array('r-1' => 3, 'r0' => 3, 'r1' => 7)
		);
	}

	/**
	 * Returns the configuration.
	 * @return RadioConfig
	 */
	public static function load()
	{
		return ConfigManager::load(__CLASS__, 'radio', 'config');
	}

	/**
	 * Saves the configuration in the database. Has it become persistent.
	 */
	public static function save()
	{
		ConfigManager::save('radio', self::load(), 'config');
	}
}
?>
