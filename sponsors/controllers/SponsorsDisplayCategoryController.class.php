<?php
/*##################################################
 *                      SponsorsDisplayCategoryController.class.php
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

class SponsorsDisplayCategoryController extends ModuleController
{
	private $lang;
	private $config;
	private $category;

	public function execute(HTTPRequestCustom $request)
	{
		$this->init();
		$this->check_authorizations();
		$this->build_view();
		return $this->generate_response();
	}

	private function init()
	{
		$this->lang = LangLoader::get('common', 'sponsors');
		$this->view = new FileTemplate('sponsors/SponsorsDisplayCategoryController.tpl');
		$this->view->add_lang($this->lang);
		$this->config = SponsorsConfig::load();
	}

	private function build_view()
	{
		$now = new Date();
		$request = AppContext::get_request();

		$this->build_category_list();
		$this->build_items_listing_view($now);
	}

	private function build_category_list()
	{
		$authorized_categories = SponsorsService::get_authorized_categories(Category::ROOT_CATEGORY);

		$result_cat = PersistenceContext::get_querier()->select('SELECT sponsors_cat.*
		FROM '. SponsorsSetup::$sponsors_cats_table .' sponsors_cat
		WHERE sponsors_cat.id IN :authorized_categories
		ORDER BY id', array(
			'authorized_categories' => $authorized_categories
		));

		while ($row_cat = $result_cat->fetch())
		{
			$this->view->assign_block_vars('categories', array(
				'ID' => $row_cat['id'],
				'ID_PARENT' => $row_cat['id_parent'],
				'SUB_ORDER' => $row_cat['c_order'],
				'NAME' => $row_cat['name'],
				'REWRITED_NAME' => $row_cat['rewrited_name'],
				'U_CATEGORY' => SponsorsUrlBuilder::display_category($row_cat['id'], $row_cat['rewrited_name'])->rel(),
				'C_NO_ITEM_AVAILABLE' => $result_cat->get_rows_count() == 0,
			));
		}
		$result_cat->dispose();
	}

	private function build_items_listing_view(Date $now)
	{
		$partnership_levels = SponsorsConfig::load()->get_partnership_levels();
		$partnership_levels_nb = count($partnership_levels);

		$category_description = FormatingHelper::second_parse($this->get_category()->get_description());
		$category_image = $this->get_category()->get_image()->rel();

		$this->view->put_all(array(
			'C_CATEGORY'             => true,
			'U_MEMBERSHIP'           => SponsorsUrlBuilder::membership_terms()->rel(),
			'C_ROOT_CATEGORY'        => $this->get_category()->get_id() == Category::ROOT_CATEGORY,
			'C_CATEGORY_IMAGE'       => !empty($category_image),
			'C_CATEGORY_DESCRIPTION' => !empty($category_description),
			'CATEGORY_NAME'          => $this->get_category()->get_name(),
			'CATEGORY_DESCRIPTION'   => $category_description,
			'CATEGORY_IMAGE'         => $category_image,
			'C_MODERATION'           => SponsorsAuthorizationsService::check_authorizations($this->get_category()->get_id())->moderation(),
			'ITEMS_PER_LINE'         => $this->config->get_items_number_per_line(),
			'ID_CATEGORY'            => $this->get_category()->get_id(),
			'U_EDIT_CATEGORY'        => $this->get_category()->get_id() == Category::ROOT_CATEGORY ? SponsorsUrlBuilder::configuration()->rel() : SponsorsUrlBuilder::edit_category($this->get_category()->get_id())->rel(),
		));

		$i = 1;
		foreach($partnership_levels as $id => $name)
		{
			// $this->view->assign_block_vars('level_links', array(
			// ));

			$authorized_categories = SponsorsService::get_authorized_categories($this->get_category()->get_id());

			$condition = 'WHERE id_category IN :authorized_categories
			AND sponsors.partner_level = :partner_level
			AND (published = 1 OR (published = 2 AND publication_start_date < :timestamp_now AND (publication_end_date > :timestamp_now OR publication_end_date = 0)))';
			$parameters = array(
				'authorized_categories' => $authorized_categories,
				'timestamp_now' => $now->get_timestamp(),
				'partner_level' => $i
			);

			$result = PersistenceContext::get_querier()->select('SELECT sponsors.*, member.*
			FROM ' . SponsorsSetup::$sponsors_table . ' sponsors
			LEFT JOIN ' . DB_TABLE_MEMBER . ' member ON member.user_id = sponsors.author_user_id

			' . $condition . '
			ORDER BY sponsors.partner_level, sponsors.title
			', array_merge($parameters, array(
				'user_id' => AppContext::get_current_user()->get_id()
			)));

			$this->view->assign_block_vars('level_links', array(
				'WIDTH'  => $partnership_levels_nb,
				'TARGET' => Url::encode_rewrite($name),
				'NAME'   => $name,
				'ID'     => $i,
				'ITEM_ROWS'           => $result->get_rows_count(),
				'C_NO_ITEM_AVAILABLE' => $result->get_rows_count() == 0,
			));

			while($row = $result->fetch())
			{
				$partner = new Partner();
				$partner->set_properties($row);

				$this->view->assign_block_vars('level_links.items', $partner->get_array_tpl_vars());
			}
			$result->dispose();

			$i++;

			// $test = $result->get_rows_count();
			// Debug::stop($partner);
		}
	}

	private function get_category()
	{
		if ($this->category === null)
		{
			$id = AppContext::get_request()->get_getstring('id_category', 0);
			if (!empty($id))
			{
				try {
					$this->category = SponsorsService::get_categories_manager()->get_categories_cache()->get_category($id);
				} catch (CategoryNotFoundException $e) {
					$error_controller = PHPBoostErrors::unexisting_page();
					DispatchManager::redirect($error_controller);
				}
			}
			else
			{
				$this->category = SponsorsService::get_categories_manager()->get_categories_cache()->get_category(Category::ROOT_CATEGORY);
			}
		}
		return $this->category;
	}

	private function check_authorizations()
	{
		if (!SponsorsAuthorizationsService::check_authorizations($this->get_category()->get_id())->read())
		{
			$error_controller = PHPBoostErrors::user_not_authorized();
			DispatchManager::redirect($error_controller);
		}
	}

	private function generate_response()
	{
		$response = new SiteDisplayResponse($this->view);

		$graphical_environment = $response->get_graphical_environment();

		if ($this->category->get_id() != Category::ROOT_CATEGORY)
			$graphical_environment->set_page_title($this->category->get_name(), $this->lang['sponsors.module.title']);
		else
			$graphical_environment->set_page_title($this->lang['sponsors.module.title']);

		$graphical_environment->get_seo_meta_data()->set_description($this->category->get_description());
		$graphical_environment->get_seo_meta_data()->set_canonical_url(SponsorsUrlBuilder::display_category($this->category->get_id(), $this->category->get_rewrited_name(), AppContext::get_request()->get_getstring('field', 'date'), AppContext::get_request()->get_getstring('sort', 'desc'), AppContext::get_request()->get_getint('page', 1)));

		$breadcrumb = $graphical_environment->get_breadcrumb();
		$breadcrumb->add($this->lang['sponsors.module.title'], SponsorsUrlBuilder::home());

		$categories = array_reverse(SponsorsService::get_categories_manager()->get_parents($this->category->get_id(), true));
		foreach ($categories as $id => $category)
		{
			if ($category->get_id() != Category::ROOT_CATEGORY)
				$breadcrumb->add($category->get_name(), SponsorsUrlBuilder::display_category($category->get_id(), $category->get_rewrited_name(), AppContext::get_request()->get_getstring('field', 'date'), AppContext::get_request()->get_getstring('sort', 'desc'), AppContext::get_request()->get_getint('page', 1)));
		}

		return $response;
	}

	public static function get_view()
	{
		$object = new self();
		$object->init();
		$object->check_authorizations();
		$object->build_view();
		return $object->view;
	}
}
?>
