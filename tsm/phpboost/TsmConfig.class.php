<?php
  /* ##################################################
   *                             TsmConfigSetup.class.php
   *                            -------------------
   *   begin                : February 13, 2018
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
    ################################################### */

  /**
   * @author Sebastien LARTIGUE <babsolune@phpboost.com>
   */

class TsmConfig extends AbstractConfigData
{
    const SEASONS_COLS_NB = 'seasons_cols_nb';
    const COMPETITIONS_COLS_NB = 'competitions_cols_nb';
	const MOSAIC_DISPLAY = 'mosaic';
	const LIST_DISPLAY = 'list';
	const TABLE_DISPLAY = 'table';

    // Clubs
    const DEFAULT_ADDRESS = "default_address";
    const DEFAULT_LATITUDE = "default_latitude";
    const DEFAULT_LONGITUDE = "default_longitude";
    const NEW_WINDOW = 'new_window';
    const CLUBS_COLS_NB = 'clubs_cols_nb';
    const CLUBS_DISPLAY = 'clubs_display';
    const SEASON_AUTH = 'season_auth';
    const CLUB_AUTH = 'club_auth';

    public function get_seasons_cols_nb()
    {
        return $this->get_property(self::SEASONS_COLS_NB);
    }

    public function set_seasons_cols_nb($number)
    {
        return $this->set_property(self::SEASONS_COLS_NB, $number);
    }

    public function get_competitions_cols_nb()
    {
        return $this->get_property(self::COMPETITIONS_COLS_NB);
    }

    public function set_competitions_cols_nb($number)
    {
        return $this->set_property(self::COMPETITIONS_COLS_NB, $number);
    }

    // Clubs
    public function is_gmap_active()
	{
		return ModulesManager::is_module_installed('GoogleMaps') && ModulesManager::is_module_activated('GoogleMaps') && GoogleMapsConfig::load()->get_api_key();
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

	public function get_clubs_display()
	{
		return $this->get_property(self::CLUBS_DISPLAY);
	}

	public function set_clubs_display($value)
	{
		$this->set_property(self::CLUBS_DISPLAY, $value);
	}

	public function get_clubs_cols_nb()
	{
		return $this->get_property(self::CLUBS_COLS_NB);
	}

	public function set_clubs_cols_nb($value)
	{
		$this->set_property(self::CLUBS_COLS_NB, $value);
	}

	public function get_new_window()
	{
		return $this->get_property(self::NEW_WINDOW);
	}

	public function set_new_window($value)
	{
		$this->set_property(self::NEW_WINDOW, $value);
	}

	public function get_season_auth()
	{
		return $this->get_property(self::SEASON_AUTH);
	}

	public function set_season_auth(Array $season_auth)
	{
		$this->set_property(self::SEASON_AUTH, $season_auth);
	}

	public function get_club_auth()
	{
		return $this->get_property(self::CLUB_AUTH);
	}

	public function set_club_auth(Array $club_auth)
	{
		$this->set_property(self::CLUB_AUTH, $club_auth);
	}

	public function get_default_values()
	{
		return array(
			self::SEASONS_COLS_NB => 4,
			self::COMPETITIONS_COLS_NB => 2,

            // Clubs
            self::CLUBS_COLS_NB => 4,
			self::CLUBS_DISPLAY => self::TABLE_DISPLAY,
            self::NEW_WINDOW => false,
            self::DEFAULT_ADDRESS => '',
            self::DEFAULT_LATITUDE => '',
            self::DEFAULT_LONGITUDE => '',
			self::SEASON_AUTH => array(),
			self::CLUB_AUTH => array()
		);
	}

	/**
	 * Returns the configuration.
	 * @return TsmConfig
	 */
	public static function load()
	{
		return ConfigManager::load(__CLASS__, 'tsm', 'config');
	}

	/**
	 * Saves the configuration in the database. Has it become persistent.
	 */
	public static function save()
	{
		ConfigManager::save('tsm', self::load(), 'config');
	}

  }

  ?>
