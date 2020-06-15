<?php
/**
 * @copyright 	&copy; 2005-2019 PHPBoost
 * @license 	https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @version   	PHPBoost 5.2 - last update: 2017 11 05
 * @since   	PHPBoost 5.1 - 2017 06 29
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
			CONCAT('" . PATH_TO_ROOT . "/staff/" . (!ServerEnvironmentConfig::load()->is_url_rewriting_enabled() ? "index.php?url=/" : "") . "', id_category, '-', IF(id_category != 0, cat.rewrited_name, 'root'), '/', w.id, '-', w.rewrited_name) AS adherent
			FROM " . StaffSetup::$staff_table . " w
			LEFT JOIN ". StaffSetup::$staff_cats_table ." cat ON w.id_category = cat.id
			WHERE ( FT_SEARCH(w.name, '" . $args['search'] . "') OR FT_SEARCH(w.contents, '" . $args['search'] . "') OR FT_SEARCH_RELEVANCE(w.short_contents, '" . $args['search'] . "') )
			AND id_category IN(" . implode(", ", $authorized_categories) . ")
			AND publication = 1
			ORDER BY relevance DESC
			LIMIT 100 OFFSET 0";
	}
}
?>
