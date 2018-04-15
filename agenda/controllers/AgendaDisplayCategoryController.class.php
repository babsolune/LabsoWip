<?php
/*##################################################
 *                      AgendaDisplayCategoryController.class.php
 *                            -------------------
 *   begin                : August 21, 2013
 *   copyright            : (C) 2013 Julien BRISWALTER
 *   email                : j1.seth@phpboost.com
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

class AgendaDisplayCategoryController extends ModuleController
{
	private $lang;
	private $tpl;

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
		$this->lang = LangLoader::get('common', 'agenda');
		$this->tpl = new FileTemplate('agenda/AgendaDisplaySeveralEventsController.tpl');
		$this->tpl->add_lang($this->lang);
	}

	private function build_view(HTTPRequestCustom $request)
	{
		$year = $request->get_getint('year', date('Y'));
		$month = $request->get_getint('month', date('n'));
		$day = $request->get_getint('day', date('j'));

		if (!checkdate($month, $day, $year))
		{
			$this->tpl->put('MSG', MessageHelper::display($this->lang['agenda.error.e_invalid_date'], MessageHelper::ERROR));

			$year = date('Y');
			$month = date('n');
			$day = date('j');
		}

		$this->tpl->put_all(array(
			'AGENDA' => AgendaAjaxAgendaController::get_view(false, $year, $month),
			'EVENTS' => AgendaAjaxEventsController::get_view($year, $month, $day)
		));

		return $this->tpl;
	}

	private function check_authorizations()
	{
		$id_cat = $this->get_category()->get_id();
		if (!AgendaAuthorizationsService::check_authorizations($id_cat)->read())
		{
			$error_controller = PHPBoostErrors::user_not_authorized();
			DispatchManager::redirect($error_controller);
		}
	}

	private function get_category()
	{
		if ($this->category === null)
		{
			$id = AppContext::get_request()->get_getint('id_category', 0);
			if (!empty($id))
			{
				try {
					$this->category = AgendaService::get_categories_manager()->get_categories_cache()->get_category($id);
				} catch (CategoryNotFoundException $e) {
					$error_controller = PHPBoostErrors::unexisting_page();
					DispatchManager::redirect($error_controller);
				}
			}
			else
			{
				$this->category = AgendaService::get_categories_manager()->get_categories_cache()->get_category(Category::ROOT_CATEGORY);
			}
		}
		return $this->category;
	}

	private function generate_response()
	{
		$response = new SiteDisplayResponse($this->tpl);
		$graphical_environment = $response->get_graphical_environment();

		if ($this->get_category()->get_id() != Category::ROOT_CATEGORY)
			$graphical_environment->set_page_title($this->get_category()->get_name(), $this->lang['module_title']);
		else
			$graphical_environment->set_page_title($this->lang['module_title']);

		$graphical_environment->get_seo_meta_data()->set_canonical_url(AgendaUrlBuilder::home());

		$breadcrumb = $graphical_environment->get_breadcrumb();
		$breadcrumb->add($this->lang['module_title'], AgendaUrlBuilder::home());

		return $response;
	}

	public static function get_view()
	{
		$object = new self();
		$object->init();
		$object->check_authorizations();
		$object->build_view(AppContext::get_request());
		return $object->tpl;
	}
}
?>
