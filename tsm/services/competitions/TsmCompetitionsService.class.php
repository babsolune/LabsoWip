<?php
/*##################################################
 *                               TsmCompetitionsService.class.php
 *                            -------------------
 *   begin                : February 13, 2018
 *   copyright            : (C) 2018 Sebastien LARTIGUE
 *   email                : babsolune@phpboost.com
 *
 *
 ###################################################
 *
 * This program is a free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
 ###################################################*/

 /**
 * @author Sebastien LARTIGUE <babsolune@phpboost.com>
 */

class TsmCompetitionsService
{
	private static $db_querier;

	public static function __static()
	{
		self::$db_querier = PersistenceContext::get_querier();
	}

	 /**
	 * @desc Count items number.
	 * @param string $condition (optional) : Restriction to apply to the list of items
	 */
	public static function count($condition = '', $parameters = array())
	{
		return self::$db_querier->count(TsmSetup::$tsm_competition, $condition, $parameters);
	}

	 /**
	 * @desc Create a new entry in the database table.
	 * @param string[] $competition : new competition
	 */
	public static function add_competition(Competition $competition)
	{
		$result = self::$db_querier->insert(TsmSetup::$tsm_competition, $competition->get_properties());

		return $result->get_last_inserted_id();
	}

	 /**
	 * @desc Update an entry.
	 * @param string[] $competition : competition to update
	 */
	public static function update_competition(Competition $competition)
	{
		self::$db_querier->update(TsmSetup::$tsm_competition, $competition->get_properties(), 'WHERE id=:id', array('id' => $competition->get_id()));
	}

	 /**
	 * @desc Delete an entry.
	 * @param string $condition : Restriction to apply to the list
	 * @param string[] $parameters : Parameters of the condition
	 */
	public static function delete_competition($condition, array $parameters)
	{
		self::$db_querier->delete(TsmSetup::$tsm_competition, $condition, $parameters);
	}

	 /**
	 * @desc Return the properties of a competition.
	 * @param string $condition : Restriction to apply to the list
	 * @param string[] $parameters : Parameters of the condition
	 */
	public static function get_competition($condition, array $parameters)
	{
		$row = self::$db_querier->select_single_row_query('SELECT competitions.*, member.*
		FROM ' . TsmSetup::$tsm_competition . ' competitions
		LEFT JOIN ' . DB_TABLE_MEMBER . ' member ON member.user_id = competitions.author_user_id
		' . $condition, $parameters);

		$competition = new Competition();
		$competition->set_properties($row);
		return $competition;
	}
}
?>
