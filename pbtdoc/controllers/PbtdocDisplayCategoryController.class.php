<?php
/*##################################################
 *                      PbtdocDisplayCategoryController.class.php
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
class PbtdocDisplayCategoryController extends ModuleController
{
	private $lang;
	private $config;
	private $category;
	private $content_management_config;

	public function execute(HTTPRequestCustom $request)
	{
		$this->init();

		$this->check_authorizations();

		$this->build_view();

		return $this->generate_response();
	}

	private function init()
	{
		$this->lang = LangLoader::get('common', 'pbtdoc');
		$this->view = new FileTemplate('pbtdoc/PbtdocDisplayCategoryController.tpl');
		$this->view->add_lang($this->lang);
		$this->config = PbtdocConfig::load();

		$this->comments_config = CommentsConfig::load();
		$this->content_management_config = ContentManagementConfig::load();
	}

	private function build_view()
	{
		$now = new Date();
		$page = AppContext::get_request()->get_getint('page', 1);
		$subcategories_page = AppContext::get_request()->get_getint('subcategories_page', 1);

		$this->build_categories_listing_view($now, $page, $subcategories_page);
		$this->build_courses_listing_view($now, $page, $subcategories_page);
	}

	private function build_courses_listing_view(Date $now, $page, $subcategories_page)
	{
		$condition = 'WHERE id_category = :id_category
		AND (published = 1 OR (published = 2 AND publishing_start_date < :timestamp_now AND (publishing_end_date > :timestamp_now OR publishing_end_date = 0)))';
		$parameters = array(
			'id_category' => $this->get_category()->get_id(),
			'timestamp_now' => $now->get_timestamp()
		);

		$pagination = $this->get_pagination($condition, $parameters, $page, $subcategories_page);

		$result = PersistenceContext::get_querier()->select('SELECT pbtdoc.*, member.*
		FROM ' . PbtdocSetup::$pbtdoc_table . ' pbtdoc
		LEFT JOIN ' . DB_TABLE_MEMBER . ' member ON member.user_id = pbtdoc.author_user_id
		' . $condition . '
		ORDER BY order_id
		LIMIT :number_items_per_page OFFSET :display_from', array_merge($parameters, array(
			'user_id' => AppContext::get_current_user()->get_id(),
			'number_items_per_page' => $pagination->get_number_items_per_page(),
			'display_from' => $pagination->get_display_from()
		)));

		$number_columns_display_per_line = $this->config->get_number_cols_display_per_line();

		$this->view->put_all(array(
			'C_MODERATION' => PbtdocAuthorizationsService::check_authorizations($this->get_category()->get_id())->moderation(),
			'C_COURSES' => $result->get_rows_count() > 0,
			'COURSES_NUMBER' => $result->get_rows_count(),
			'C_MORE_THAN_ONE_COURSE' => $result->get_rows_count() > 1,
			'C_TABLE' => $this->config->get_display_type() == PbtdocConfig::DISPLAY_TABLE,
			'C_MOSAIC' => $this->config->get_display_type() == PbtdocConfig::DISPLAY_MOSAIC,
			'C_DISPLAY_REORDER_LINK' => $result->get_rows_count() > 1 && PbtdocAuthorizationsService::check_authorizations($this->get_category()->get_id())->moderation(),
			'C_DISPLAY_CATS_ICON' => $this->config->are_cats_icon_enabled(),
			'C_NO_COURSE_AVAILABLE' => $result->get_rows_count() == 0,
			'C_SEVERAL_COLUMNS' => $number_columns_display_per_line > 1,
			'NUMBER_COLUMNS' => $number_columns_display_per_line,
			'C_ONE_COURSE_AVAILABLE' => $result->get_rows_count() == 1,
			'C_TWO_COURSES_AVAILABLE' => $result->get_rows_count() == 2,
			'C_PAGINATION' => $pagination->has_several_pages(),
			'PAGINATION' => $pagination->display(),
			'ID_CAT' => $this->get_category()->get_id(),
			'U_EDIT_CATEGORY' => $this->get_category()->get_id() == Category::ROOT_CATEGORY ? PbtdocUrlBuilder::configuration()->rel() : PbtdocUrlBuilder::edit_category($this->get_category()->get_id())->rel(),
			'U_REORDER_ITEMS' => PbtdocUrlBuilder::reorder_items($this->get_category()->get_id(), $this->get_category()->get_rewrited_name())->rel(),
		));

		while($row = $result->fetch())
		{
			$course = new Course();
			$course->set_properties($row);

			$this->build_keywords_view($course);

			$this->view->assign_block_vars('items', $course->get_array_tpl_vars());
		}
		$result->dispose();
	}

	private function build_categories_listing_view(Date $now, $page, $subcategories_page)
	{
		$subcategories = PbtdocService::get_categories_manager()->get_categories_cache()->get_children($this->get_category()->get_id(), PbtdocService::get_authorized_categories($this->get_category()->get_id()));
		$subcategories_pagination = $this->get_subcategories_pagination(count($subcategories), $this->config->get_number_categories_per_page(), $page, $subcategories_page);

		$nbr_cat_displayed = 0;
		foreach ($subcategories as $id => $category)
		{
			$nbr_cat_displayed++;

			if ($nbr_cat_displayed > $subcategories_pagination->get_display_from() && $nbr_cat_displayed <= ($subcategories_pagination->get_display_from() + $subcategories_pagination->get_number_items_per_page()))
			{
				$category_image = $category->get_image()->rel();

				$this->view->assign_block_vars('sub_categories_list', array(
					'C_CATEGORY_IMAGE' => !empty($category_image),
					'C_MORE_THAN_ONE_COURSE' => $category->get_elements_number() > 1,
					'C_CATEGORY_DESCRIPTION' => !empty(FormatingHelper::second_parse($category->get_description())),
					'CATEGORY_ID' => $category->get_id(),
					'CATEGORY_NAME' => $category->get_name(),
					'CATEGORY_DESCRIPTION' => FormatingHelper::second_parse($category->get_description()),
					'CATEGORY_IMAGE' => $category_image,
					'COURSES_NUMBER' => $category->get_elements_number(),
					'U_CATEGORY' => PbtdocUrlBuilder::display_category($category->get_id(), $category->get_rewrited_name())->rel()
				));
			}
		}

		$nbr_column_cats_per_line = ($nbr_cat_displayed > $this->config->get_number_cols_display_per_line()) ? $this->config->get_number_cols_display_per_line() : $nbr_cat_displayed;
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
					$this->category = PbtdocService::get_categories_manager()->get_categories_cache()->get_category($id);
				} catch (CategoryNotFoundException $e) {
					$error_controller = PHPBoostErrors::unexisting_page();
					DispatchManager::redirect($error_controller);
				}
			}
			else
			{
				$this->category = PbtdocService::get_categories_manager()->get_categories_cache()->get_category(Category::ROOT_CATEGORY);
			}
		}
		return $this->category;
	}

	private function build_keywords_view(Course $course)
	{
		$keywords = $course->get_keywords();
		$nbr_keywords = count($keywords);
		$this->view->put('C_KEYWORDS', $nbr_keywords > 0);

		$i = 1;
		foreach ($keywords as $keyword)
		{
			$this->view->assign_block_vars('keywords', array(
				'C_SEPARATOR' => $i < $nbr_keywords,
				'NAME' => $keyword->get_name(),
				'URL' => PbtdocUrlBuilder::display_tag($keyword->get_rewrited_name())->rel(),
			));
			$i++;
		}
	}

	private function get_pagination($condition, $parameters, $page, $subcategories_page)
	{
		$number_courses = PersistenceContext::get_querier()->count(PbtdocSetup::$pbtdoc_table, $condition, $parameters);

		$pagination = new ModulePagination($page, $number_courses, (int)PbtdocConfig::load()->get_number_items_per_page());
		$pagination->set_url(PbtdocUrlBuilder::display_category($this->category->get_id(), $this->category->get_rewrited_name(), '%d', $subcategories_page));

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
		$pagination->set_url(PbtdocUrlBuilder::display_category($this->category->get_id(), $this->category->get_rewrited_name(), $page, '%d'));

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
			if (($this->config->are_descriptions_displayed_to_guests() && !Authorizations::check_auth(RANK_TYPE, User::MEMBER_LEVEL, $this->get_category()->get_authorizations(), Category::READ_AUTHORIZATIONS)) || (!$this->config->are_descriptions_displayed_to_guests() && !PbtdocAuthorizationsService::check_authorizations($this->get_category()->get_id())->read()))
			{
				$error_controller = PHPBoostErrors::user_not_authorized();
				DispatchManager::redirect($error_controller);
			}
		}
		else
		{
			if (!PbtdocAuthorizationsService::check_authorizations($this->get_category()->get_id())->read())
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
			$graphical_environment->set_page_title($this->category->get_name(), $this->lang['module.title']);
		else
			$graphical_environment->set_page_title($this->lang['module.title']);

		$graphical_environment->get_seo_meta_data()->set_description($this->category->get_description());
		$graphical_environment->get_seo_meta_data()->set_canonical_url(PbtdocUrlBuilder::display_category($this->category->get_id(), $this->category->get_rewrited_name(), AppContext::get_request()->get_getint('page', 1)));

		$breadcrumb = $graphical_environment->get_breadcrumb();
		$breadcrumb->add($this->lang['module.title'], PbtdocUrlBuilder::home());

		$categories = array_reverse(PbtdocService::get_categories_manager()->get_parents($this->category->get_id(), true));
		foreach ($categories as $id => $category)
		{
			if ($category->get_id() != Category::ROOT_CATEGORY)
				$breadcrumb->add($category->get_name(), PbtdocUrlBuilder::display_category($category->get_id(), $category->get_rewrited_name(), AppContext::get_request()->get_getint('page', 1)));
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
