<?php
/*##################################################
 *                      SponsorsSearchable.class.php
 *                            -------------------
 *   begin                : May 20, 2018 
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

class SponsorsSearchable extends AbstractSearchableExtensionPoint
{
	public function get_search_request($args)
	{
		$now = new Date();
		$authorized_categories = SponsorsService::get_authorized_categories(Category::ROOT_CATEGORY);
		$weight = isset($args['weight']) && is_numeric($args['weight']) ? $args['weight'] : 1;

		return "SELECT " . $args['id_search'] . " AS id_search,
			sponsors.id AS id_content,
			sponsors.title AS title,
			(2 * FT_SEARCH_RELEVANCE(sponsors.title, '" . $args['search'] . "') + (FT_SEARCH_RELEVANCE(sponsors.contents, '" . $args['search'] . "') +
			FT_SEARCH_RELEVANCE(sponsors.description, '" . $args['search'] . "')) / 2 ) / 3 * " . $weight . " AS relevance,
			CONCAT('" . PATH_TO_ROOT . "/sponsors/" . (!ServerEnvironmentConfig::load()->is_url_rewriting_enabled() ? "index.php?url=/" : "") . "', id_category, '-', IF(id_category != 0, cat.rewrited_name, 'root'), '/', sponsors.id, '-', sponsors.rewrited_title) AS link
			FROM " . SponsorsSetup::$sponsors_table . " sponsors
			LEFT JOIN ". SponsorsSetup::$sponsors_cats_table ." cat ON cat.id = sponsors.id_category
			WHERE ( FT_SEARCH(sponsors.title, '" . $args['search'] . "') OR FT_SEARCH(sponsors.contents, '" . $args['search'] . "') OR FT_SEARCH_RELEVANCE(sponsors.description, '" . $args['search'] . "') )
			AND id_category IN(" . implode(", ", $authorized_categories) . ")
			AND (published = 1 OR (published = 2 AND publication_start_date < '" . $now->get_timestamp() . "' AND (publication_end_date > '" . $now->get_timestamp() . "' OR publication_end_date = 0)))
			ORDER BY relevance DESC
			LIMIT 100 OFFSET 0";
	}
}
?>
