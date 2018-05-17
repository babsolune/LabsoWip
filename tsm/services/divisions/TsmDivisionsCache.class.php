<?php
  /* ##################################################
   *                             TsmDivisionsCache.class.php
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

class TsmDivisionsCache implements CacheData
{
    private $division = array();

 	/**
 	 * {@inheritdoc}
 	 */
 	public function synchronize()
 	{
 		$this->division = array();
 		$now = new Date();

 		$result = PersistenceContext::get_querier()->select('
 			SELECT division.*
 			FROM ' . TsmSetup::$tsm_division . ' division
			WHERE publication = 1
            ORDER BY id DESC');

 		while ($row = $result->fetch())
 		{
 			$this->division[$row['id']] = $row;
 		}
 		$result->dispose();
 	}

 	public function get_division()
 	{
 		return $this->division;
 	}

 	public function division_exists($id)
 	{
 		return array_key_exists($id, $this->division);
 	}

 	public function get_division_item($id)
 	{
 		if ($this->division_exists($id))
 		{
 			return $this->division[$id];
 		}
 		return null;
 	}

	public function get_items_number()
	{
		return count($this->division);
	}

 	/**
 	 * Loads and returns the division cached data.
 	 * @return TsmDivisionCache The cached data
 	 */
 	public static function load()
 	{
 		return CacheManager::load(__CLASS__, 'tsm', 'division');
 	}

 	/**
 	 * Invalidates the current division cached data.
 	 */
 	public static function invalidate()
 	{
 		CacheManager::invalidate('tsm', 'division');
 	}

  }

  ?>
