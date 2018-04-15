<?php
/*##################################################
 *                        PortfolioCategoriesCache.class.php
 *                            -------------------
 *   begin                : November 29, 2017
 *   copyright            : (C) 2017 Sebastien LARTIGUE
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

class PortfolioCategoriesCache extends CategoriesCache
{
	public function get_table_name()
	{
		return PortfolioSetup::$portfolio_cats_table;
	}

	public function get_category_class()
	{
		return CategoriesManager::RICH_CATEGORY_CLASS;
	}

	public function get_module_identifier()
	{
		return 'portfolio';
	}

	protected function get_category_elements_number($category_id)
	{
		$now = new Date();
		return PortfolioService::count('WHERE category_id = :category_id AND (published = 1 OR (published = 2 AND publication_start_date < :timestamp_now AND (publication_end_date > :timestamp_now OR publication_end_date = 0)))',
			array(
				'timestamp_now' => $now->get_timestamp(),
				'category_id' => $category_id
			)
		);
	}

	public function get_root_category()
	{
		$root = new RichRootCategory();
		$root->set_authorizations(PortfolioConfig::load()->get_authorizations());
		$root->set_description(PortfolioConfig::load()->get_root_category_description());
		return $root;
	}
}
?>
