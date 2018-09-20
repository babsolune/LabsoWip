<?php
/*##################################################
 *                      StaffDisplayCategoryController.class.php
 *                            -------------------
 *   begin                : May 13, 2013
 *   copyright            : (C) 2013 Patrick DUBEAU
 *   email                : daaxwizeman@gmail.com
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
 * @author Patrick DUBEAU <daaxwizeman@gmail.com>
 */
class StaffDisplayCategoryController extends ModuleController
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
		$this->lang = LangLoader::get('common', 'staff');
		$this->view = new FileTemplate('staff/StaffDisplayCategoryController.tpl');
		$this->view->add_lang($this->lang);
		$this->config = StaffConfig::load();
	}

	private function build_view()
	{
		$now = new Date();
		$request = AppContext::get_request();

		$this->build_categories_listing_view($now);
		$this->build_adherents_listing_view($now);
	}

	private function build_adherents_listing_view(Date $now)
	{
		$condition = 'WHERE id_category = :id_category
		AND publication = 1';
		$parameters = array(
			'id_category' => $this->get_category()->get_id(),
			'timestamp_now' => $now->get_timestamp()
		);

		$result = PersistenceContext::get_querier()->select('SELECT staff.*, member.*
		FROM ' . StaffSetup::$staff_table . ' staff
		LEFT JOIN ' . DB_TABLE_MEMBER . ' member ON member.user_id = staff.author_user_id
		' . $condition . '
		ORDER BY order_id ASC', array_merge($parameters, array(
			'user_id' => AppContext::get_current_user()->get_id()
		)));

		$this->view->put_all(array(
			'C_ADHERENTS' => $result->get_rows_count() != 0,
			'C_ONE_ADHERENT_AVAILABLE' => $result->get_rows_count() == 1,
			'C_TWO_ADHERENTS_AVAILABLE' => $result->get_rows_count() == 2,
			'C_AVATARS_ALLOWED' => $this->config->are_avatars_shown(),
			'C_DISPLAY_REORDER_LINK' => $result->get_rows_count() > 1 && StaffAuthorizationsService::check_authorizations($this->get_category()->get_id())->moderation(),
			'ID_CAT' => $this->get_category()->get_id(),
			'U_EDIT_CATEGORY' => $this->get_category()->get_id() == Category::ROOT_CATEGORY ? StaffUrlBuilder::configuration()->rel() : StaffUrlBuilder::edit_category($this->get_category()->get_id())->rel(),
			'U_REORDER_ITEMS' => StaffUrlBuilder::reorder_items($this->get_category()->get_id(), $this->get_category()->get_rewrited_name())->rel(),
		));

		while($row = $result->fetch())
		{
			$adherent = new Adherent();
			$adherent->set_properties($row);

			$this->view->assign_block_vars('items', $adherent->get_array_tpl_vars());
		}
		$result->dispose();
	}

	private function build_categories_listing_view(Date $now)
	{
		$subcategories = StaffService::get_categories_manager()->get_categories_cache()->get_children($this->get_category()->get_id(), StaffService::get_authorized_categories($this->get_category()->get_id()));

		$nbr_cat_displayed = 0;
		foreach ($subcategories as $id => $category)
		{
			$nbr_cat_displayed++;

			$category_image = $category->get_image()->rel();

			$this->view->assign_block_vars('sub_categories_list', array(
				'C_CATEGORY_IMAGE' => !empty($category_image),
				'C_MORE_THAN_ONE_ADHERENT' => $category->get_elements_number() > 1,
				'CATEGORY_ID' => $category->get_id(),
				'CATEGORY_NAME' => $category->get_name(),
				'CATEGORY_IMAGE' => $category_image,
				'ADHERENTS_NUMBER' => $category->get_elements_number(),
				'U_CATEGORY' => StaffUrlBuilder::display_category($category->get_id(), $category->get_rewrited_name())->rel()
			));
		}

		$category_description = FormatingHelper::second_parse($this->get_category()->get_description());

		$this->view->put_all(array(
			'C_CATEGORY' => true,
			'C_ROOT_CATEGORY' => $this->get_category()->get_id() == Category::ROOT_CATEGORY,
			'C_HIDE_NO_ITEM_MESSAGE' => $this->get_category()->get_id() == Category::ROOT_CATEGORY && ($nbr_cat_displayed != 0 || !empty($category_description)),
			'C_CATEGORY_DESCRIPTION' => !empty($category_description),
			'C_SUB_CATEGORIES' => $nbr_cat_displayed > 0,
			'CATEGORY_NAME' => $this->get_category()->get_name(),
			'CATEGORY_IMAGE' => $this->get_category()->get_image()->rel(),
			'CATEGORY_DESCRIPTION' => $category_description,
			'C_SEVERAL_CATS_COLUMNS' => $this->config->get_sub_categories_nb() > 1,
			'NUMBER_CATS_COLUMNS' => $this->config->get_sub_categories_nb(),
			'C_MODERATE' => AppContext::get_current_user()->check_level(User::MODERATOR_LEVEL)
		));
	}

	private function get_category()
	{
		if ($this->category === null)
		{
			$id = AppContext::get_request()->get_getstring('id_category', 0);
			if (!empty($id))
			{
				try {
					$this->category = StaffService::get_categories_manager()->get_categories_cache()->get_category($id);
				} catch (CategoryNotFoundException $e) {
					$error_controller = PHPBoostErrors::unexisting_page();
					DispatchManager::redirect($error_controller);
				}
			}
			else
			{
				$this->category = StaffService::get_categories_manager()->get_categories_cache()->get_category(Category::ROOT_CATEGORY);
			}
		}
		return $this->category;
	}

	private function check_authorizations()
	{
		if (AppContext::get_current_user()->is_guest())
		{
			if ((!Authorizations::check_auth(RANK_TYPE, User::MEMBER_LEVEL, $this->get_category()->get_authorizations(), Category::READ_AUTHORIZATIONS)) || (!StaffAuthorizationsService::check_authorizations($this->get_category()->get_id())->read()))
			{
				$error_controller = PHPBoostErrors::user_not_authorized();
				DispatchManager::redirect($error_controller);
			}
		}
		else
		{
			if (!StaffAuthorizationsService::check_authorizations($this->get_category()->get_id())->read())
			{
				$error_controller = PHPBoostErrors::user_not_authorized();
				DispatchManager::redirect($error_controller);
			}
		}
	}

	private function generate_response()
	{
		$response = new SiteDisplayResponse($this->view);

		$graphical_environment = $response->get_graphical_environment();

		if ($this->category->get_id() != Category::ROOT_CATEGORY)
			$graphical_environment->set_page_title($this->category->get_name(), $this->lang['staff.module.title']);
		else
			$graphical_environment->set_page_title($this->lang['staff.module.title']);

		$graphical_environment->get_seo_meta_data()->set_description($this->category->get_description());
		$graphical_environment->get_seo_meta_data()->set_canonical_url(StaffUrlBuilder::display_category($this->category->get_id(), $this->category->get_rewrited_name(),  AppContext::get_request()->get_getint('page', 1)));

		$breadcrumb = $graphical_environment->get_breadcrumb();
		$breadcrumb->add($this->lang['staff.module.title'], StaffUrlBuilder::home());

		$categories = array_reverse(StaffService::get_categories_manager()->get_parents($this->category->get_id(), true));
		foreach ($categories as $id => $category)
		{
			if ($category->get_id() != Category::ROOT_CATEGORY)
				$breadcrumb->add($category->get_name(), StaffUrlBuilder::display_category($category->get_id(), $category->get_rewrited_name(), AppContext::get_request()->get_getint('page', 1)));
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
