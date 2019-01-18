<?php
/**
 * @copyright 	&copy; 2005-2019 PHPBoost https://www.phpboost.com
 * @license 	https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Loic ROUCHON <horn@phpboost.com>
 * @version   	PHPBoost 5.2 - last update: 2018 05 25
 * @since   	PHPBoost 3.0 - 2010 02 07
 * @contributor Julien BRISWALTER [j1.seth@phpboost.com]
 * @contributor Arnaud GENET [elenwii@phpboost.com]
 * @contributor Sebastien LARTIGUE [babsolune@phpboost.com]
*/

namespace Wiki\phpboost;
use \Wiki\services\ModServices;
use \Wiki\services\ModSetup;
use \Wiki\services\ModUrlBuilder;

class ModFeedProvider implements FeedProvider
{
	public function get_feeds_list()
	{
		return ModServices::get_categories_manager()->get_feeds_categories_module()->get_feed_list();
	}

	public function get_feed_data_struct($category_id = 0, $name = '')
	{
		if (ModServices::get_categories_manager()->get_categories_cache()->category_exists($category_id))
		{
			$querier = PersistenceContext::get_querier();

			$category = ModServices::get_categories_manager()->get_categories_cache()->get_category($category_id);

			$site_name = GeneralConfig::load()->get_site_name();
			$site_name = $category_id != Category::ROOT_CATEGORY ? $site_name . ' : ' . $category->get_name() : $site_name;

			$feed_module_name = LangLoader::get_message('module.feed.name', 'common', 'wiki');
			$data = new FeedData();
			$data->set_title($feed_module_name . ' - ' . $site_name);
			$data->set_date(new Date());
			$data->set_link(SyndicationUrlBuilder::rss('wiki', $category_id));
			$data->set_host(HOST);
			$data->set_desc($feed_module_name . ' - ' . $site_name);
			$data->set_lang(LangLoader::get_message('xml_lang', 'main'));
			$data->set_auth_bit(Category::READ_AUTHORIZATIONS);

			$categories = ModServices::get_categories_manager()->get_children($category_id, new SearchCategoryChildrensOptions(), true);
			$categories_ids = array_keys($categories);

			$now = new Date();
			$results = $querier->select('SELECT item.id, item.category_id, item.title, item.rewrited_title, item.thumbnail_url,
			item.contents, item.description, item.creation_date, cat.rewrited_name AS rewrited_name_cat
			FROM ' . ModSetup::$items_table . ' item
			LEFT JOIN '. ModSetup::$categories_table .' cat ON cat.id = item.category_id
			WHERE item.category_id IN :cats_ids
			AND (published = 1 OR (published = 2 AND publishing_start_date < :timestamp_now AND (publishing_end_date > :timestamp_now OR publishing_end_date = 0)))
			ORDER BY item.creation_date DESC',
			array(
				'cats_ids' => $categories_ids,
				'timestamp_now' => $now->get_timestamp()
			));

			foreach ($results as $row)
			{
				$row['rewrited_name_cat'] = !empty($row['category_id']) ? $row['rewrited_name_cat'] : 'root';
				$link = ModUrlBuilder::display_item($row['category_id'], $row['rewrited_name_cat'], $row['id'], $row['rewrited_title']);
				$item = new FeedItem();
				$item->set_title($row['title']);
				$item->set_link($link);
				$item->set_guid($link);
				$item->set_desc(FormatingHelper::second_parse($row['contents']));
				$item->set_date(new Date($row['creation_date'], Timezone::SERVER_TIMEZONE));
				$item->set_image_url($row['thumbnail_url']);
				$item->set_auth(ModServices::get_categories_manager()->get_heritated_authorizations($row['category_id'], Category::READ_AUTHORIZATIONS, Authorizations::AUTH_PARENT_PRIORITY));
				$data->add_item($item);
			}
			$results->dispose();

			return $data;
		}
	}
}
?>
