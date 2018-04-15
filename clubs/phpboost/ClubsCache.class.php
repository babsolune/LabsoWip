<?php
/*##################################################
 *                               ClubsCache.class.php
 *                            -------------------
 *   begin                : June 23, 2017
 *   copyright            : (C) 2017 Sebastien LARTIGUE
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

class ClubsCache implements CacheData
{
	private $partners_clubs = array();

	/**
	 * {@inheritdoc}
	 */
	public function synchronize()
	{
		$this->partners_clubs = array();

		$now = new Date();
		$config = ClubsConfig::load();

		$result = PersistenceContext::get_querier()->select('
			SELECT clubs.id, clubs.name
			FROM ' . ClubsSetup::$clubs_table . ' clubs
			LEFT JOIN ' . DB_TABLE_COMMENTS_TOPIC . ' com ON com.id_in_module = clubs.id AND com.module_id = \'clubs\'
			LEFT JOIN ' . DB_TABLE_AVERAGE_NOTES . ' notes ON notes.id_in_module = clubs.id AND notes.module_name = \'clubs\'
			WHERE clubs.approbation_type = 1
			ORDER BY clubs.name DESC', array(
				'timestamp_now' => $now->get_timestamp()
		));

		while ($row = $result->fetch())
		{
			$this->partners_clubs[$row['id']] = $row;
		}
		$result->dispose();
	}

	public function get_partners_clubs()
	{
		return $this->partners_clubs;
	}

	public function partner_club_exists($id)
	{
		return array_exists($id, $this->partners_clubs);
	}

	public function get_partner_club_item($id)
	{
		if ($this->partner_club_exists($id))
		{
			return $this->partners_clubs[$id];
		}
		return null;
	}

	public function get_number_partners_clubs()
	{
		return count($this->partners_clubs);
	}

	/**
	 * Loads and returns the clubs cached data.
	 * @return ClubsCache The cached data
	 */
	public static function load()
	{
		return CacheManager::load(__CLASS__, 'clubs', 'minimenu');
	}

	/**
	 * Invalidates the current clubs cached data.
	 */
	public static function invalidate()
	{
		CacheManager::invalidate('clubs', 'minimenu');
	}
}
?>
