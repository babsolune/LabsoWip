<?php
/*##################################################
 *                               TsmSeasonsCache.class.php
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

class TsmSeasonsCache implements CacheData
{
	private $season = array();

	/**
	 * {@inheritdoc}
	 */
	public function synchronize()
	{
		$this->season = array();

		$now = new Date();

		$result = PersistenceContext::get_querier()->select('
			SELECT season.id, season.season_date
			FROM ' . TsmSetup::$tsm_season . ' season
			WHERE season.publication = 1
			ORDER BY season.id DESC', array(
				'timestamp_now' => $now->get_timestamp()
		));

		while ($row = $result->fetch())
		{
			$this->season[$row['id']] = $row;
		}
		$result->dispose();
	}

	public function get_season()
	{
		return $this->season;
	}

	public function season_exists($id)
	{
		return array_exists($id, $this->season);
	}

	public function get_season_item($id)
	{
		if ($this->season_exists($id))
		{
			return $this->season[$id];
		}
		return null;
	}

	public function get_number_season()
	{
		return count($this->season);
	}

	/**
	 * Loads and returns the tsms cached data.
	 * @return TsmSeasonsCache The cached data
	 */
	public static function load()
	{
		return CacheManager::load(__CLASS__, 'tsm', 'season');
	}

	/**
	 * Invalidates the current tsms cached data.
	 */
	public static function invalidate()
	{
		CacheManager::invalidate('tsm', 'season');
	}
}
?>
