<?php
/*##################################################
 *                               StaffFeedProvider.class.php
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

class StaffFeedProvider implements FeedProvider
{
	public function get_feeds_list()
	{
		return StaffService::get_categories_manager()->get_feeds_categories_module()->get_feed_list();
	}

	public function get_feed_data_struct($idcat = 0, $name = '')
	{
		if (StaffService::get_categories_manager()->get_categories_cache()->category_exists($idcat))
		{
			$querier = PersistenceContext::get_querier();
			$category = StaffService::get_categories_manager()->get_categories_cache()->get_category($idcat);

			$site_name = GeneralConfig::load()->get_site_name();
			$site_name = $idcat != Category::ROOT_CATEGORY ? $site_name . ' : ' . $category->get_name() : $site_name;

			$feed_module_name = LangLoader::get_message('staff.module.title', 'common', 'staff');
			$data = new FeedData();
			$data->set_title($feed_module_name . ' - ' . $site_name);
			$data->set_date(new Date());
			$data->set_link(SyndicationUrlBuilder::rss('staff', $idcat));
			$data->set_host(HOST);
			$data->set_desc($feed_module_name . ' - ' . $site_name);
			$data->set_lang(LangLoader::get_message('xml_lang', 'main'));
			$data->set_auth_bit(Category::READ_AUTHORIZATIONS);

			$categories = StaffService::get_categories_manager()->get_children($idcat, new SearchCategoryChildrensOptions(), true);
			$ids_categories = array_keys($categories);

			$now = new Date();
			$results = $querier->select('SELECT staff.id, staff.id_category, staff.lastname, staff.firstname, staff.rewrited_name, staff.contents, staff.creation_date, staff.picture_url, cat.rewrited_name AS rewrited_name_cat
				FROM ' . StaffSetup::$staff_table . ' staff
				LEFT JOIN '. StaffSetup::$staff_cats_table .' cat ON cat.id = staff.id_category
				WHERE staff.id_category IN :ids_categories
				AND publication = 1
				ORDER BY staff.creation_date DESC', array(
					'ids_categories' => $ids_categories,
					'timestamp_now' => $now->get_timestamp()
			));

			foreach ($results as $row)
			{
				$row['rewrited_name_cat'] = !empty($row['id_category']) ? $row['rewrited_name_cat'] : 'root';
				$adherent = StaffUrlBuilder::display($row['id_category'], $row['rewrited_name_cat'], $row['id'], $row['rewrited_name']);

				$item = new FeedItem();
				$item->set_title($row['lastname'] . ' ' . $row['firstname']);
				$item->set_link($adherent);
				$item->set_guid($adherent);
				$item->set_desc(FormatingHelper::second_parse($row['contents']));
				$item->set_date(new Date($row['creation_date'], Timezone::SERVER_TIMEZONE));
				$item->set_image_url($row['picture_url']);
				$item->set_auth(StaffService::get_categories_manager()->get_heritated_authorizations($row['id_category'], Category::READ_AUTHORIZATIONS, Authorizations::AUTH_PARENT_PRIORITY));
				$data->add_item($item);
			}
			$results->dispose();

			return $data;
		}
	}
}
?>
