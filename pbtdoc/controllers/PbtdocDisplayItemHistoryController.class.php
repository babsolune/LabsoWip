<?php
/*##################################################
 *		       PbtdocDisplayItemHistoryController.class.php
 *                            -------------------
 *   begin                : April 03, 2013
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

class PbtdocDisplayItemHistoryController extends ModuleController
{
	private $lang;
	private $tpl;
	private $course;
	private $category;

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
		$this->tpl = new FileTemplate('pbtdoc/PbtdocDisplayItemHistoryController.tpl');
		$this->tpl->add_lang($this->lang);
	}

	private function build_view(HTTPRequestCustom $request)
	{
		$current_page = $request->get_getint('page', 1);
		$config = PbtdocConfig::load();
		$content_management_config = ContentManagementConfig::load();



		$this->build_pages_pagination($current_page, $nbr_pages, $array_page);
	}

	private function build_pages_pagination($current_page, $nbr_pages, $array_page)
	{
		if ($nbr_pages > 1)
		{
			$pagination = $this->get_pagination($nbr_pages, $current_page);

			if ($current_page > 1 && $current_page <= $nbr_pages)
			{
				$previous_page = PbtdocUrlBuilder::display_item($this->category->get_id(), $this->category->get_rewrited_name(), $this->course->get_id(), $this->course->get_rewrited_title())->rel() . ($current_page - 1);

				$this->tpl->put_all(array(
					'U_PREVIOUS_PAGE' => $previous_page,
					'L_PREVIOUS_TITLE' => $array_page[1][$current_page-2]
				));
			}

			if ($current_page > 0 && $current_page < $nbr_pages)
			{
				$next_page = PbtdocUrlBuilder::display_item($this->category->get_id(), $this->category->get_rewrited_name(), $this->course->get_id(), $this->course->get_rewrited_title())->rel() . ($current_page + 1);

				$this->tpl->put_all(array(
					'U_NEXT_PAGE' => $next_page,
					'L_NEXT_TITLE' => $array_page[1][$current_page]
				));
			}

			$this->tpl->put_all(array(
				'C_PAGINATION' => true,
				'C_PREVIOUS_PAGE' => ($current_page != 1) ? true : false,
				'C_NEXT_PAGE' => ($current_page != $nbr_pages) ? true : false,
				'PAGINATION_COURSES' => $pagination->display()
			));
		}
	}

	private function check_authorizations()
	{
		$course = $this->get_course();

		$current_user = AppContext::get_current_user();
		$not_authorized = !PbtdocAuthorizationsService::check_authorizations($course->get_id_category())->moderation() && !PbtdocAuthorizationsService::check_authorizations($course->get_id_category())->write() && (!PbtdocAuthorizationsService::check_authorizations($course->get_id_category())->contribution() || $course->get_author_user()->get_id() != $current_user->get_id());

		switch ($course->get_publishing_state())
		{
			case Course::PUBLISHED_NOW:
				if (!PbtdocAuthorizationsService::check_authorizations($course->get_id_category())->read())
				{
					$error_controller = PHPBoostErrors::user_not_authorized();
		   			DispatchManager::redirect($error_controller);
				}
			break;
			case Course::NOT_PUBLISHED:
				if ($not_authorized || ($current_user->get_id() == User::VISITOR_LEVEL))
				{
					$error_controller = PHPBoostErrors::user_not_authorized();
		   			DispatchManager::redirect($error_controller);
				}
			break;
			case Course::PUBLISHED_DATE:
				if (!$course->is_published() && ($not_authorized || ($current_user->get_id() == User::VISITOR_LEVEL)))
				{
					$error_controller = PHPBoostErrors::user_not_authorized();
		   			DispatchManager::redirect($error_controller);
				}
			break;
			default:
				$error_controller = PHPBoostErrors::unexisting_page();
				DispatchManager::redirect($error_controller);
			break;
		}
	}

	private function get_pagination($nbr_pages, $current_page)
	{
		$pagination = new ModulePagination($current_page, $nbr_pages, 1, Pagination::LIGHT_PAGINATION);
		$pagination->set_url(PbtdocUrlBuilder::display_item($this->category->get_id(), $this->category->get_rewrited_name(), $this->course->get_id(), $this->course->get_rewrited_title(), '%d'));

		if ($pagination->current_page_is_empty() && $current_page > 1)
		{
			$error_controller = PHPBoostErrors::unexisting_page();
			DispatchManager::redirect($error_controller);
		}

		return $pagination;
	}

	private function get_course()
	{
		if ($this->course === null)
		{
			$id = AppContext::get_request()->get_getint('id', 0);
			if (!empty($id)) {
				try {
					$this->course = PbtdocService::get_course('WHERE pbtdoc.id=:id', array('id' => $id));
				} catch (RowNotFoundException $e)
				{
					$error_controller = PHPBoostErrors::unexisting_page();
   					DispatchManager::redirect($error_controller);
				}
			} else
				$this->course = new Course();
		}
		return $this->course;
	}

	private function generate_response()
	{
		$response = new SiteDisplayResponse($this->tpl);

		$graphical_environment = $response->get_graphical_environment();
		$graphical_environment->set_page_title($this->course->get_title(), $this->lang['module.title']);
		$graphical_environment->get_seo_meta_data()->set_description($this->course->get_description());
		$graphical_environment->get_seo_meta_data()->set_canonical_url(PbtdocUrlBuilder::display_item($this->category->get_id(), $this->category->get_rewrited_name(), $this->course->get_id(), $this->course->get_rewrited_title(), AppContext::get_request()->get_getint('page', 1)));

		$breadcrumb = $graphical_environment->get_breadcrumb();
		$breadcrumb->add($this->lang['module.title'], PbtdocUrlBuilder::home());

		$categories = array_reverse(PbtdocService::get_categories_manager()->get_parents($this->course->get_id_category(), true));
		foreach ($categories as $id => $category)
		{
			if ($category->get_id() != Category::ROOT_CATEGORY)
				$breadcrumb->add($category->get_name(), PbtdocUrlBuilder::display_category($category->get_id(), $category->get_rewrited_name()));
		}
		$breadcrumb->add($this->course->get_title(), PbtdocUrlBuilder::display_item($category->get_id(), $category->get_rewrited_name(), $this->course->get_id(), $this->course->get_rewrited_title()));

		return $response;
	}
}
?>
