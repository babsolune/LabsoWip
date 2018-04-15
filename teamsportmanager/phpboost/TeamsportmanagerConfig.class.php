<?php
  /* ##################################################
   *                             TeamsportmanagerConfigSetup.class.php
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

  class TeamsportmanagerConfig extends AbstractConfigData
  {
      const SEASONS_COLUMNS_NUMBER = 'seasons_columns_number';
      const COMPETITIONS_COLUMNS_NUMBER = 'competitions_columns_number';

      public function get_seasons_columns_number()
      {
		return $this->get_property(self::SEASONS_COLUMNS_NUMBER);
      }

      public function set_seasons_columns_number($number)
      {
		return $this->set_property(self::SEASONS_COLUMNS_NUMBER, $number);
      }

      public function get_competitions_columns_number()
      {
		return $this->get_property(self::COMPETITIONS_COLUMNS_NUMBER);
      }

      public function set_competitions_columns_number($number)
      {
		return $this->set_property(self::COMPETITIONS_COLUMNS_NUMBER, $number);
      }

	public function get_default_values()
	{
		return array(
			self::SEASONS_COLUMNS_NUMBER => 4,
			self::COMPETITIONS_COLUMNS_NUMBER => 2
		);
	}

	/**
	 * Returns the configuration.
	 * @return TeamsportmanagerConfig
	 */
	public static function load()
	{
		return ConfigManager::load(__CLASS__, 'team-sport-manager', 'config');
	}

	/**
	 * Saves the configuration in the database. Has it become persistent.
	 */
	public static function save()
	{
		ConfigManager::save('team-sport-manager', self::load(), 'config');
	}

  }

  ?>
