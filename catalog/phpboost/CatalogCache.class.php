<?php
/*##################################################
 *                               CatalogCache.class.php
 *                            -------------------
 *   begin                : August 24, 2014
 *   copyright            : (C) 2014 Julien BRISWALTER
 *   email                : j1.seth@phpboost.com
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
 * @author Julien BRISWALTER <j1.seth@phpboost.com>
 */

class CatalogCache implements CacheData
{
	private $products = array();

	/**
	 * {@inheritdoc}
	 */
	public function synchronize()
	{
		$this->products = array();

		$now = new Date();
		$config = CatalogConfig::load();
		$oldest_product_date = new Date(date('Y-m-d', strtotime('-' . $config->get_oldest_product_day_in_menu() . ' day')));

		$result = PersistenceContext::get_querier()->select('
			SELECT catalog.*, notes.average_notes, notes.number_notes
			FROM ' . CatalogSetup::$catalog_table . ' catalog
			LEFT JOIN ' . DB_TABLE_AVERAGE_NOTES . ' notes ON notes.id_in_module = catalog.id AND notes.module_name = \'catalog\'
			WHERE (approbation_type = 1 OR (approbation_type = 2 AND start_date < :timestamp_now AND (end_date > :timestamp_now OR end_date = 0)))
			' . ($config->is_limit_oldest_product_day_in_menu_enabled() ? 'AND updated_date > :oldest_product_date' : '') . '
			ORDER BY ' . $config->get_sort_type() . ' DESC
			LIMIT :products_number_in_menu OFFSET 0', array(
				'timestamp_now' => $now->get_timestamp(),
				'products_number_in_menu' => (int)$config->get_products_number_in_menu(),
				'oldest_product_date' => $oldest_product_date->get_timestamp()
		));

		while ($row = $result->fetch())
		{
			$this->products[$row['id']] = $row;
		}
		$result->dispose();
	}

	public function get_products()
	{
		return $this->products;
	}

	public function product_exists($id)
	{
		return array_key_exists($id, $this->productss);
	}

	public function get_product_item($id)
	{
		if ($this->product_exists($id))
		{
			return $this->productss[$id];
		}
		return null;
	}

	public function get_number_products()
	{
		return count($this->product);
	}

	/**
	 * Loads and returns the catalog cached data.
	 * @return CatalogCache The cached data
	 */
	public static function load()
	{
		return CacheManager::load(__CLASS__, 'catalog', 'minimenu');
	}

	/**
	 * Invalidates the current catalog cached data.
	 */
	public static function invalidate()
	{
		CacheManager::invalidate('catalog', 'minimenu');
	}
}
?>
