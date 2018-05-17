<?php
  /* ##################################################
   *                             TsmCompetitionsCache.class.php
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

class TsmCompetitionsCache implements CacheData
{
    private $competition = array();

 	/**
 	 * {@inheritdoc}
 	 */
 	public function synchronize()
 	{
 		$this->competition = array();
 		$now = new Date();

 		$result = PersistenceContext::get_querier()->select('
 			SELECT competition.*
 			FROM ' . TsmSetup::$tsm_competition . ' competition
			WHERE publication = 1
            ORDER BY id DESC');

 		while ($row = $result->fetch())
 		{
 			$this->competition[$row['id']] = $row;
 		}
 		$result->dispose();
 	}

 	public function get_competition()
 	{
 		return $this->competition;
 	}

 	public function competition_exists($id)
 	{
 		return array_key_exists($id, $this->competition);
 	}

 	public function get_competition_item($id)
 	{
 		if ($this->competition_exists($id))
 		{
 			return $this->competition[$id];
 		}
 		return null;
 	}

	public function get_items_number()
	{
		return count($this->competition);
	}

 	/**
 	 * Loads and returns the competition cached data.
 	 * @return TsmCompetitionCache The cached data
 	 */
 	public static function load()
 	{
 		return CacheManager::load(__CLASS__, 'tsm', 'competition');
 	}

 	/**
 	 * Invalidates the current competition cached data.
 	 */
 	public static function invalidate()
 	{
 		CacheManager::invalidate('tsm', 'competition');
 	}

  }

  ?>
