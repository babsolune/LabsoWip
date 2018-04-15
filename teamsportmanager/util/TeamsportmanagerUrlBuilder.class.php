<?php
/*##################################################
 *                       TeamsportmanagerUrlBuilder.class.php
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
 ###################################################*/

/**
 * @author Sebastien LARTIGUE <babsolune@phpboost.com>
 */
class TeamsportmanagerUrlBuilder
{

	private static $dispatcher = '/teamsportmanager';

    // Administration

	/**
	 * @return Url
	 */
	public static function config()
	{
		return DispatchManager::get_url(self::$dispatcher, '/admin/config/');
	}

	/**
	 * @return Url
	 */
	public static function compet_manager()
	{
		return DispatchManager::get_url(self::$dispatcher, '/admin/compet/');
	}

	/**
	 * @return Url
	 */
	public static function results_manager()
	{
		return DispatchManager::get_url(self::$dispatcher, '/admin/results/');
	}

    // Competition

	/**
	 * @return Url
	 */
	public static function display_competition($season_name, $competition_id, $rewrited_competition_name)
	{
		return DispatchManager::get_url(self::$dispatcher, '/' . $season_name . '/' . $competition_id . '-' . $rewrited_competition_name);
	}

    // Divisions

	/**
	 * @return Url
	 */
	public static function display_season($season_name)
	{
		return DispatchManager::get_url(self::$dispatcher, '/' . $season_name);
	}

	//  Home/Seasons
	/**
	 * @return Url
	 */
	public static function home()
	{
		return DispatchManager::get_url(self::$dispatcher, '/');
	}
}
?>
