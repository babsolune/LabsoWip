<?php
/*##################################################
 *                               StaffSearchable.class.php
 *                            -------------------
 *   begin                : June 29, 2017
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
 * @author Seabstien LARTIGUE <babsolune@phpboost.com>
 */

class StaffSearchable extends AbstractSearchableExtensionPoint
{
	public function get_search_request($args)
	{
		$now = new Date();
		$authorized_categories = StaffService::get_authorized_categories(Category::ROOT_CATEGORY);
		$weight = isset($args['weight']) && is_numeric($args['weight']) ? $args['weight'] : 1;

		return "SELECT " . $args['id_search'] . " AS id_search,
			w.id AS id_content,
			w.name AS title,
			( 2 * FT_SEARCH_RELEVANCE(w.name, '" . $args['search'] . "') + (FT_SEARCH_RELEVANCE(w.contents, '" . $args['search'] . "') +
			FT_SEARCH_RELEVANCE(w.short_contents, '" . $args['search'] . "')) / 2 ) / 3 * " . $weight . " AS relevance,
			CONCAT('" . PATH_TO_ROOT . "/staff/" . (!ServerEnvironmentConfig::load()->is_url_rewriting_enabled() ? "index.php?url=/" : "") . "', id_category, '-', IF(id_category != 0, cat.rewrited_name, 'root'), '/', w.id, '-', w.rewrited_name) AS member
			FROM " . StaffSetup::$staff_table . " w
			LEFT JOIN ". StaffSetup::$staff_cats_table ." cat ON w.id_category = cat.id
			WHERE ( FT_SEARCH(w.name, '" . $args['search'] . "') OR FT_SEARCH(w.contents, '" . $args['search'] . "') OR FT_SEARCH_RELEVANCE(w.short_contents, '" . $args['search'] . "') )
			AND id_category IN(" . implode(", ", $authorized_categories) . ")
			AND approbation_type = 1
			ORDER BY relevance DESC
			LIMIT 100 OFFSET 0";
	}
}
?>
