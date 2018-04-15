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
	private $comments_config;
	private $notation_config;
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
		$this->view = new FileTemplate('staff/StaffDisplaySeveralMembersController.tpl');
		$this->view->add_lang($this->lang);
		$this->config = StaffConfig::load();
	}

	private function build_view()
	{
		$now = new Date();
		$request = AppContext::get_request();
		$page = AppContext::get_request()->get_getint('page', 1);
		$subcategories_page = AppContext::get_request()->get_getint('subcategories_page', 1);
		$root = Category::ROOT_CATEGORY;
		// $root = StaffService::get_categories_manager()->get_categories_cache()->get_children($this->get_category()->get_id() == 0);
		// var_dump($root);

		if ($root) {
			$this->build_root_listing_view();
		} else {
			$this->build_categories_listing_view($now, $page, $subcategories_page);
			$this->build_members_listing_view($now, $page, $subcategories_page);
		}
	}

	private function build_root_listing_view()
	{
		$this->view->put_all(array(
			'C_ROOT_CATEGORY' => Category::ROOT_CATEGORY,
			'C_CATEGORY_DISPLAYED_TABLE' => $this->config->is_category_displayed_table(),
		));

		$result_cat = PersistenceContext::get_querier()->select('SELECT staff_cat.*
		FROM '. StaffSetup::$staff_cats_table .' staff_cat
		WHERE staff_cat.special_authorizations = 0
		ORDER BY staff_cat.id_parent ASC, staff_cat.c_order ASC'
		);

		while ($row_cat = $result_cat->fetch())
		{
			$this->view->assign_block_vars('staffcats', array(
				'C_MEMBERS' => $result->get_rows_count() != 0,
				'CATEGORY_NAME' => $row_cat['name'],
				'U_CATEGORY' => StaffUrlBuilder::display_category($row_cat['id'], $row_cat['rewrited_name'])->rel()
			));

			$id_cat = $row_cat['id'];

			$result = PersistenceContext::get_querier()->select('SELECT staff.*
			FROM '. StaffSetup::$staff_table .' staff
			WHERE staff.id_category = :id_cat
			AND staff.approbation_type = 1
			ORDER BY staff.group_leader, staff.lastname ASC, staff.firstname ASC', array(
				'user_id' => AppContext::get_current_user()->get_id(),
				'id_cat' => $id_cat,
				'timestamp_now' => $now->get_timestamp()
			));

			while ($row = $result->fetch())
			{
				$this->view->assign_block_vars('staffcats.members', array(
					'C_IS_GROUP_LEADER' => (bool)$row['group_leader'],
					'LASTNAME' => $row['lastname'],
					'FIRSTNAME' => $row['firstname'],
					'ROLE' => $row['role'],
					'MEMBER_PHONE' => $row['member_phone'],
				));
			}
			$result->dispose();
		}
		$result_cat->dispose();
	}

	private function build_members_listing_view(Date $now, $page, $subcategories_page)
	{
		$condition = 'WHERE id_category = :id_category
		AND approbation_type = 1';
		$parameters = array(
			'id_category' => $this->get_category()->get_id(),
			'timestamp_now' => $now->get_timestamp()
		);

		$pagination = $this->get_pagination($condition, $parameters, $page, $subcategories_page);

		$result = PersistenceContext::get_querier()->select('SELECT staff.*, member.*
		FROM ' . StaffSetup::$staff_table . ' staff
		LEFT JOIN ' . DB_TABLE_MEMBER . ' member ON member.user_id = staff.author_user_id
		' . $condition . '
		ORDER BY creation_date
		LIMIT :number_items_per_page OFFSET :display_from', array_merge($parameters, array(
			'user_id' => AppContext::get_current_user()->get_id(),
			'number_items_per_page' => $pagination->get_number_items_per_page(),
			'display_from' => $pagination->get_display_from()
		)));

		$number_columns_display_per_line = $this->config->get_categories_number_per_page();

		$this->view->put_all(array(
			'C_CATEGORY_DISPLAYED_TABLE' => $this->config->get_category_display_type() == StaffConfig::DISPLAY_TABLE,
			'C_PAGINATION' => $pagination->has_several_pages(),
			'C_MEMBERS' => $result->get_rows_count() != 0,
			'C_SEVERAL_COLUMNS' => $number_columns_display_per_line > 1,
			'NUMBER_COLUMNS' => $number_columns_display_per_line,
			'C_ONE_MEMBER_AVAILABLE' => $result->get_rows_count() == 1,
			'C_TWO_MEMBERS_AVAILABLE' => $result->get_rows_count() == 2,
			'PAGINATION' => $pagination->display(),
			'ID_CAT' => $this->get_category()->get_id(),
			'U_EDIT_CATEGORY' => $this->get_category()->get_id() == Category::ROOT_CATEGORY ? StaffUrlBuilder::configuration()->rel() : StaffUrlBuilder::edit_category($this->get_category()->get_id())->rel()
		));

		while($row = $result->fetch())
		{
			$member = new Member();
			$member->set_properties($row);

			$this->view->assign_block_vars('members', $member->get_array_tpl_vars());
		}
		$result->dispose();
	}

	private function build_categories_listing_view(Date $now, $page, $subcategories_page)
	{
		$subcategories = StaffService::get_categories_manager()->get_categories_cache()->get_children($this->get_category()->get_id(), StaffService::get_authorized_categories($this->get_category()->get_id()));
		$subcategories_pagination = $this->get_subcategories_pagination(count($subcategories), $this->config->get_categories_number_per_page(), $page, $subcategories_page);

		$nbr_cat_displayed = 0;
		foreach ($subcategories as $id => $category)
		{
			$nbr_cat_displayed++;

			if ($nbr_cat_displayed > $subcategories_pagination->get_display_from() && $nbr_cat_displayed <= ($subcategories_pagination->get_display_from() + $subcategories_pagination->get_number_items_per_page()))
			{
				$category_image = $category->get_image()->rel();

				$this->view->assign_block_vars('sub_categories_list', array(
					'C_CATEGORY_IMAGE' => !empty($category_image),
					'C_MORE_THAN_ONE_MEMBER' => $category->get_elements_number() > 1,
					'CATEGORY_ID' => $category->get_id(),
					'CATEGORY_NAME' => $category->get_name(),
					'CATEGORY_IMAGE' => $category_image,
					'MEMBERS_NUMBER' => $category->get_elements_number(),
					'U_CATEGORY' => StaffUrlBuilder::display_category($category->get_id(), $category->get_rewrited_name())->rel()
				));
			}
		}

		$nbr_column_cats_per_line = ($nbr_cat_displayed > $this->config->get_columns_number_per_line()) ? $this->config->get_columns_number_per_line() : $nbr_cat_displayed;
		$nbr_column_cats_per_line = !empty($nbr_column_cats_per_line) ? $nbr_column_cats_per_line : 1;

		$category_description = FormatingHelper::second_parse($this->get_category()->get_description());

		$this->view->put_all(array(
			'C_CATEGORY' => true,
			'C_ROOT_CATEGORY' => $this->get_category()->get_id() == Category::ROOT_CATEGORY,
			'C_HIDE_NO_ITEM_MESSAGE' => $this->get_category()->get_id() == Category::ROOT_CATEGORY && ($nbr_cat_displayed != 0 || !empty($category_description)),
			'C_CATEGORY_DESCRIPTION' => !empty($category_description),
			'C_SUB_CATEGORIES' => $nbr_cat_displayed > 0,
			'C_SUBCATEGORIES_PAGINATION' => $subcategories_pagination->has_several_pages(),
			'CATEGORY_NAME' => $this->get_category()->get_name(),
			'CATEGORY_IMAGE' => $this->get_category()->get_image()->rel(),
			'CATEGORY_DESCRIPTION' => $category_description,
			'SUBCATEGORIES_PAGINATION' => $subcategories_pagination->display(),
			'C_SEVERAL_CATS_COLUMNS' => $nbr_column_cats_per_line > 1,
			'NUMBER_CATS_COLUMNS' => $nbr_column_cats_per_line
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

	private function get_pagination($condition, $parameters, $page, $subcategories_page)
	{
		$number_members = PersistenceContext::get_querier()->count(StaffSetup::$staff_table, $condition, $parameters);

		$pagination = new ModulePagination($page, $number_members, (int)StaffConfig::load()->get_items_number_per_page());
		$pagination->set_url(StaffUrlBuilder::display_category($this->category->get_id(), $this->category->get_rewrited_name(), '%d', $subcategories_page));

		if ($pagination->current_page_is_empty() && $page > 1)
		{
			$error_controller = PHPBoostErrors::unexisting_page();
			DispatchManager::redirect($error_controller);
		}

		return $pagination;
	}

	private function get_subcategories_pagination($subcategories_number, $number_categories_per_page, $page, $subcategories_page)
	{
		$pagination = new ModulePagination($subcategories_page, $subcategories_number, (int)$number_categories_per_page);
		$pagination->set_url(StaffUrlBuilder::display_category($this->category->get_id(), $this->category->get_rewrited_name(), $page, '%d'));

		if ($pagination->current_page_is_empty() && $subcategories_page > 1)
		{
			$error_controller = PHPBoostErrors::unexisting_page();
			DispatchManager::redirect($error_controller);
		}

		return $pagination;
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
			$graphical_environment->set_page_title($this->category->get_name(), $this->lang['module_title']);
		else
			$graphical_environment->set_page_title($this->lang['module_title']);

		$graphical_environment->get_seo_meta_data()->set_description($this->category->get_description());
		$graphical_environment->get_seo_meta_data()->set_canonical_url(StaffUrlBuilder::display_category($this->category->get_id(), $this->category->get_rewrited_name(),  AppContext::get_request()->get_getint('page', 1)));

		$breadcrumb = $graphical_environment->get_breadcrumb();
		$breadcrumb->add($this->lang['module_title'], StaffUrlBuilder::home());

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
