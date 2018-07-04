<?php
/*##################################################
 *                      WikiSearchable.class.php
 *                            -------------------
 *   begin                : May 25, 2018
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

class WikiSearchable extends AbstractSearchableExtensionPoint
{
	public function get_search_request($args)
	{
		$now = new Date();
		$authorized_categories = WikiService::get_authorized_categories(Category::ROOT_CATEGORY);
		$weight = isset($args['weight']) && is_numeric($args['weight']) ? $args['weight'] : 1;

		return "SELECT " . $args['id_search'] . " AS id_search,
			wiki.id AS id_content,
			wiki.title AS title,
			(2 * FT_SEARCH_RELEVANCE(wiki.title, '" . $args['search'] . "') + (FT_SEARCH_RELEVANCE(wiki.contents, '" . $args['search'] . "') +
			FT_SEARCH_RELEVANCE(wiki.description, '" . $args['search'] . "')) / 2 ) / 3 * " . $weight . " AS relevance,
			CONCAT('" . PATH_TO_ROOT . "/wiki/" . (!ServerEnvironmentConfig::load()->is_url_rewriting_enabled() ? "index.php?url=/" : "") . "', id_category, '-', IF(id_category != 0, cat.rewrited_name, 'root'), '/', wiki.id, '-', wiki.rewrited_title) AS link
			FROM " . WikiSetup::$wiki_table . " wiki
			LEFT JOIN ". WikiSetup::$wiki_cats_table ." cat ON cat.id = wiki.id_category
			WHERE ( FT_SEARCH(wiki.title, '" . $args['search'] . "') OR FT_SEARCH(wiki.contents, '" . $args['search'] . "') OR FT_SEARCH_RELEVANCE(wiki.description, '" . $args['search'] . "') )
			AND id_category IN(" . implode(", ", $authorized_categories) . ")
			AND (published = 1 OR (published = 2 AND publishing_start_date < '" . $now->get_timestamp() . "' AND (publishing_end_date > '" . $now->get_timestamp() . "' OR publishing_end_date = 0)))
			ORDER BY relevance DESC
			LIMIT 100 OFFSET 0";
	}
}
?>
