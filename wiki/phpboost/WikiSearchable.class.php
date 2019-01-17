<?php
/**
 * @copyright 	&copy; 2005-2019 PHPBoost
 * @license 	https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version   	PHPBoost 5.2 - last update: 2018 05 25
 * @since   	PHPBoost 3.0 - 2012 01 21
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
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
