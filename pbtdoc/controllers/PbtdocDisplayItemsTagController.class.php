<?php
/*##################################################
 *		       PbtdocDisplayItemsTagController.class.php
 *                            -------------------
 *   begin                : June 05, 2013
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

class PbtdocDisplayItemsTagController extends ModuleController
{
	private $lang;
	private $view;
	private $keyword;

	private $config;
	private $content_management_config;

	public function execute(HTTPRequestCustom $request)
	{
		$this->check_authorizations();

		$this->init();

		$this->build_view($request);

		return $this->generate_response();
	}

	private function init()
	{
		$this->lang = LangLoader::get('common', 'pbtdoc');
		$this->view = new FileTemplate('pbtdoc/PbtdocDisplayCategoryController.tpl');
		$this->view->add_lang($this->lang);
		$this->config = PbtdocConfig::load();
		$this->content_management_config = ContentManagementConfig::load();
	}

	private function get_keyword()
	{
		if ($this->keyword === null)
		{
			$rewrited_name = AppContext::get_request()->get_getstring('tag', '');
			if (!empty($rewrited_name))
			{
				try {
					$this->keyword = PbtdocService::get_keywords_manager()->get_keyword('WHERE rewrited_name=:rewrited_name', array('rewrited_name' => $rewrited_name));
				} catch (RowNotFoundException $e) {
					$error_controller = PHPBoostErrors::unexisting_page();
   					DispatchManager::redirect($error_controller);
				}
			}
			else
			{
				$error_controller = PHPBoostErrors::unexisting_page();
   				DispatchManager::redirect($error_controller);
			}
		}
		return $this->keyword;
	}

	private function build_view($request)
	{
		$now = new Date();

		$authorized_categories = PbtdocService::get_authorized_categories(Category::ROOT_CATEGORY);

		$condition = 'WHERE relation.id_keyword = :id_keyword
		AND id_category IN :authorized_categories
		AND (published = 1 OR (published = 2 AND publishing_start_date < :timestamp_now AND (publishing_end_date > :timestamp_now OR publishing_end_date = 0)))';
		$parameters = array(
			'id_keyword' => $this->get_keyword()->get_id(),
			'authorized_categories' => $authorized_categories,
			'timestamp_now' => $now->get_timestamp()
		);

		$page = AppContext::get_request()->get_getint('page', 1);
		$pagination = $this->get_pagination($condition, $parameters, $page);

		$result = PersistenceContext::get_querier()->select('SELECT pbtdoc.*, member.*
		FROM ' . PbtdocSetup::$pbtdoc_table . ' pbtdoc
		LEFT JOIN ' . DB_TABLE_KEYWORDS_RELATIONS . ' relation ON relation.module_id = \'pbtdoc\' AND relation.id_in_module = pbtdoc.id
		LEFT JOIN ' . DB_TABLE_MEMBER . ' member ON member.user_id = pbtdoc.author_user_id
		' . $condition . '
		ORDER BY order_id
		LIMIT :number_items_per_page OFFSET :display_from', array_merge($parameters, array(
			'number_items_per_page' => $pagination->get_number_items_per_page(),
			'display_from' => $pagination->get_display_from()
		)));

		$number_columns_display_per_line = $this->config->get_number_cols_display_per_line();

		$this->view->put_all(array(
			'C_COURSES' => $result->get_rows_count() > 0,
			'C_MORE_THAN_ONE_COURSE' => $result->get_rows_count() > 1,
			'C_PAGINATION' => $pagination->has_several_pages(),
			'PAGINATION' => $pagination->display(),
			'C_NO_COURSE_AVAILABLE' => $result->get_rows_count() == 0,
			'C_MOSAIC' => $this->config->get_display_type() == PbtdocConfig::DISPLAY_MOSAIC,
			'C_COURSES_CAT' => false,
			'C_COMMENTS_ENABLED' => $this->comments_config->module_comments_is_enabled('pbtdoc'),
			'C_NOTATION_ENABLED' => $this->content_management_config->module_notation_is_enabled('pbtdoc'),
			'C_COURSES_FILTERS' => true,
			'CATEGORY_NAME' => $this->get_keyword()->get_name(),
			'C_SEVERAL_COLUMNS' => $number_columns_display_per_line > 1,
			'NUMBER_COLUMNS' => $number_columns_display_per_line
		));

		while ($row = $result->fetch())
		{
			$course = new Course();
			$course->set_properties($row);

			$this->build_keywords_view($course);

			$this->view->assign_block_vars('pbtdoc', $course->get_array_tpl_vars());
		}
		$result->dispose();
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

	private function get_pagination($condition, $parameters, $page)
	{
		$result = PersistenceContext::get_querier()->select_single_row_query('SELECT COUNT(*) AS nbr_pbtdoc
		FROM '. PbtdocSetup::$pbtdoc_table .' pbtdoc
		LEFT JOIN '. DB_TABLE_KEYWORDS_RELATIONS .' relation ON relation.module_id = \'pbtdoc\' AND relation.id_in_module = pbtdoc.id
		' . $condition, $parameters);

		$pagination = new ModulePagination($page, $result['nbr_pbtdoc'], PbtdocConfig::load()->get_number_items_per_page());
		$pagination->set_url(PbtdocUrlBuilder::display_tag($this->get_keyword()->get_rewrited_name(), '%d'));

		if ($pagination->current_page_is_empty() && $page > 1)
		{
			$error_controller = PHPBoostErrors::unexisting_page();
			DispatchManager::redirect($error_controller);
		}

		return $pagination;
	}

	private function check_authorizations()
	{
		if (!(PbtdocAuthorizationsService::check_authorizations()->read()))
		{
			$error_controller = PHPBoostErrors::user_not_authorized();
			DispatchManager::redirect($error_controller);
		}
	}

	private function generate_response()
	{
		$response = new SiteDisplayResponse($this->view);

		$graphical_environment = $response->get_graphical_environment();
		$graphical_environment->set_page_title($this->get_keyword()->get_name(), $this->lang['pbtdoc']);
		$graphical_environment->get_seo_meta_data()->set_description(StringVars::replace_vars($this->lang['pbtdoc.seo.description.tag'], array('subject' => $this->get_keyword()->get_name())));
		$graphical_environment->get_seo_meta_data()->set_canonical_url(PbtdocUrlBuilder::display_tag($this->get_keyword()->get_rewrited_name(), AppContext::get_request()->get_getint('page', 1)));

		$breadcrumb = $graphical_environment->get_breadcrumb();
		$breadcrumb->add($this->lang['pbtdoc'], PbtdocUrlBuilder::home());
		$breadcrumb->add($this->get_keyword()->get_name(), PbtdocUrlBuilder::display_tag($this->get_keyword()->get_rewrited_name(), AppContext::get_request()->get_getint('page', 1)));

		return $response;
	}
}
?>
