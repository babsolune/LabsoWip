<?php
/*##################################################
 *                       TsmUrlBuilder.class.php
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
class TsmUrlBuilder
{

	private static $dispatcher = '/tsm';

	// Seasons
	public static function seasons_manager()          { return DispatchManager::get_url(self::$dispatcher, '/seasons/manager/'); }
	public static function add_season()               { return DispatchManager::get_url(self::$dispatcher, '/season/add/'); }
	public static function edit_season($id)           { return DispatchManager::get_url(self::$dispatcher, '/season/' . $id . '/edit/'); }
	public static function delete_season($id)         { return DispatchManager::get_url(self::$dispatcher, '/season/' . $id . '/delete/?token=' . AppContext::get_session()->get_token()); }
	public static function display_season($id, $name) { return DispatchManager::get_url(self::$dispatcher, '/' . $id . '-' . $name . '/'); }

	// Divisions
	public static function divisions_manager()          { return DispatchManager::get_url(self::$dispatcher, '/divisions/manager/'); }
	public static function add_division()               { return DispatchManager::get_url(self::$dispatcher, '/division/add/'); }
	public static function edit_division($id)           { return DispatchManager::get_url(self::$dispatcher, '/division/' . $id . '/edit/'); }
	public static function delete_division($id)         { return DispatchManager::get_url(self::$dispatcher, '/division/' . $id . '/delete/?token=' . AppContext::get_session()->get_token()); }

	// Clubs
	public static function clubs_config()                    { return DispatchManager::get_url(self::$dispatcher, '/admin/clubs/'); }
	public static function clubs_manager()                   { return DispatchManager::get_url(self::$dispatcher, '/clubs/manager/'); }
	public static function add_club()                        { return DispatchManager::get_url(self::$dispatcher, '/club/add/'); }
	public static function edit_club($id)                    { return DispatchManager::get_url(self::$dispatcher, '/club/' . $id . '/edit/'); }
	public static function delete_club($id)                  { return DispatchManager::get_url(self::$dispatcher, '/club/' . $id . '/delete/?token=' . AppContext::get_session()->get_token()); }
	public static function display_club($id, $rewrited_name) { return DispatchManager::get_url(self::$dispatcher, '/club/' . $id . '-' . $rewrited_name . '/'); }
	public static function visit_club($id)                   { return DispatchManager::get_url(self::$dispatcher, '/club/visit/' . $id); }
	public static function dead_link_club($id)               { return DispatchManager::get_url(self::$dispatcher, '/club/dead_link/' . $id); }
	public static function home_club()                       { return DispatchManager::get_url(self::$dispatcher, '/clubs/'); }

	// Seasons
	public static function competitions_manager()        { return DispatchManager::get_url(self::$dispatcher, '/competitions/manager/'); }
	public static function add_competition()             { return DispatchManager::get_url(self::$dispatcher, '/competition/add/'); }
	public static function edit_competition($id)         { return DispatchManager::get_url(self::$dispatcher, '/competition/' . $id . '/edit/'); }
	public static function edit_competition_params($id)  { return DispatchManager::get_url(self::$dispatcher, '/competition/' . $id . '/params/'); }
	public static function edit_competition_teams($id)   { return DispatchManager::get_url(self::$dispatcher, '/competition/' . $id . '/teams/'); }
	public static function edit_competition_days($id)    { return DispatchManager::get_url(self::$dispatcher, '/competition/' . $id . '/days/'); }
	public static function edit_competition_matches($id) { return DispatchManager::get_url(self::$dispatcher, '/competition/' . $id . '/matches/'); }
	public static function edit_competition_results($id) { return DispatchManager::get_url(self::$dispatcher, '/competition/' . $id . '/results/'); }
	public static function delete_competition($id)       { return DispatchManager::get_url(self::$dispatcher, '/competition/' . $id . '/delete/?token=' . AppContext::get_session()->get_token()); }
	public static function display_competition($season_id, $season_name, $id, $division_rewrited_name)
	{
		return DispatchManager::get_url(self::$dispatcher, '/' . $season_id . '/' . $season_name . '/' . $id . '-' . $division_rewrited_name);
	}


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
