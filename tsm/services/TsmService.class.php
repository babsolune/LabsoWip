<?php
/*##################################################
 *                        TsmService.class.php
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
class TsmService
{
	private static $db_querier;
	private static $season_manager;

	public static function __static()
	{
		self::$db_querier = PersistenceContext::get_querier();
	}

	public static function get_season($condition, array $parameters)
	{
		$row = self::$db_querier->select('SELECT *
		FROM ' . TsmSetup::$tsm_season . ' tsm_season
		' . $conditions, $parameters);

		$season = new Season();
		$season->set_properties($row);
		return $season;
	}

	public static function add_season(Season $season)
	{
		$result = self::$db_querier->insert(TsmSetup::$tsm_season, $season->get_properties());
		return $result->get_last_inserted_id();
	}

	public static function get_division($condition, array $parameters)
	{
		$row = self::$db_querier->select('SELECT *
		FROM ' . TsmSetup::$tsm_division . ' tsm_division
		' . $conditions, $parameters);

		$division = new Division();
		$division->set_properties($row);
		return $division;
	}

	public static function add_division(Division $division)
	{
		$result = self::$db_querier->insert(TsmSetup::$tsm_division, $division->get_properties());
		return $result->get_last_inserted_id();
	}
}
?>
