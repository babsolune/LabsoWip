<?php
/*##################################################
 *                        PortfolioService.class.php
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

class PortfolioService
{
	private static $db_querier;
	private static $categories_manager;
	private static $keywords_manager;

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
		return self::$db_querier->count(PortfolioSetup::$portfolio_table, $condition, $parameters);
	}

	public static function add(Work $work)
	{
		$result = self::$db_querier->insert(PortfolioSetup::$portfolio_table, $work->get_properties());
		return $result->get_last_inserted_id();
	}

	public static function update(Work $work)
	{
		self::$db_querier->update(PortfolioSetup::$portfolio_table, $work->get_properties(), 'WHERE id=:id', array('id', $work->get_id()));
	}

	public static function delete($condition, array $parameters)
	{
		self::$db_querier->delete(PortfolioSetup::$portfolio_table, $condition, $parameters);
	}

	public static function get_work($condition, array $parameters)
	{
		$row = self::$db_querier->select_single_row_query('SELECT portfolio.*, member.*, notes.average_notes, notes.number_notes, note.note
		FROM ' . PortfolioSetup::$portfolio_table . ' portfolio
		LEFT JOIN ' . DB_TABLE_MEMBER . ' member ON member.user_id = portfolio.author_user_id
		LEFT JOIN ' . DB_TABLE_AVERAGE_NOTES . ' notes ON notes.id_in_module = portfolio.id AND notes.module_name = \'portfolio\'
		LEFT JOIN ' . DB_TABLE_NOTE . ' note ON note.id_in_module = portfolio.id AND note.module_name = \'portfolio\' AND note.user_id = ' . AppContext::get_current_user()->get_id() . '
		' . $condition, $parameters);

		$work = new Work();
		$work->set_properties($row);
		return $work;
	}

	 /**
	 * @desc Update the number of views of an item.
	 * @param string[] $work : item to update
	 */
	public static function update_views_number(Work $work)
	{
		self::$db_querier->update(PortfolioSetup::$portfolio_table, array('views_number' => $work->get_views_number()), 'WHERE id=:id', array('id' => $work->get_id()));
	}

	 /**
	 * @desc Update the number of visits of a link.
	 * @param string[] $work : item to update
	 */
	public static function update_visits_number(Work $work)
	{
		self::$db_querier->update(PortfolioSetup::$portfolio_table, array('visits_number' => $work->get_visits_number()), 'WHERE id=:id', array('id' => $work->get_id()));
	}

	 /**
	 * @desc Update the number of downloads of a file.
	 * @param string[] $work : item to update
	 */
	public static function update_downloads_number(Work $work)
	{
		self::$db_querier->update(PortfolioSetup::$portfolio_table, array('downloads_number' => $work->get_downloads_number()), 'WHERE id=:id', array('id' => $work->get_id()));
	}

	public static function get_authorized_categories($current_category_id)
	{
		$search_category_children_options = new SearchCategoryChildrensOptions();
		$search_category_children_options->add_authorizations_bits(Category::READ_AUTHORIZATIONS);

		if (AppContext::get_current_user()->is_guest())
			$search_category_children_options->set_allow_only_member_level_authorizations(PortfolioConfig::load()->are_descriptions_displayed_to_guests());

		$categories = self::get_categories_manager()->get_children($current_category_id, $search_category_children_options, true);
		return array_keys($categories);
	}

	public static function get_categories_manager()
	{
		if (self::$categories_manager === null)
		{
			$categories_items_parameters = new CategoriesItemsParameters();
			$categories_items_parameters->set_table_name_contains_items(PortfolioSetup::$portfolio_table);
			self::$categories_manager = new CategoriesManager(PortfolioCategoriesCache::load(), $categories_items_parameters);
		}
		return self::$categories_manager;
	}

	public static function get_keywords_manager()
	{
		if (self::$keywords_manager === null)
		{
			self::$keywords_manager = new KeywordsManager('portfolio');
		}
		return self::$keywords_manager;
	}
}
?>
