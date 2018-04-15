<?php
/*##################################################
 *		       TeamsportmanagerDisplayCompetitionController.class.php
 *                            -------------------
 *   begin                : February 13, 2018
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

class TeamsportmanagerDisplayCompetitionController extends ModuleController
{
	private $lang;
	private $tpl;
	private $competition;
	private $season;

	public function execute(HTTPRequestCustom $request)
	{
		$this->check_authorizations();

		$this->init();

		$this->build_view($request);

		return $this->generate_response();
	}

	private function init()
	{
		$this->lang = LangLoader::get('common', 'teamsportmanager');
		$this->tpl = new FileTemplate('teamsportmanager/TeamsportmanagerDisplayCompetitionController.tpl');
		$this->tpl->add_lang($this->lang);
	}

	private function get_competition()
	{
		if ($this->competition === null)
		{
			$id = AppContext::get_request()->get_getint('id', 0);
			if (!empty($id))
			{
				try
				{
					$this->competition = Competition::get_competition('WHERE teamsportmanager.id=:id', array('id' => $id));
				}
				catch (RowNotFoundException $e)
				{
					$error_controller = PHPBoostErrors::unexisting_page();
   					DispatchManager::redirect($error_controller);
				}
			}
			else
				$this->competition = new Competition();
		}
		return $this->competition;
	}

	private function build_view(HTTPRequestCustom $request)
	{
		$config = TeamsportmanagerConfig::load();

		$this->season = $this->competition->get_season();
	}

	private function generate_response()
	{
		$response = new SiteDisplayResponse($this->tpl);

		$graphical_environment = $response->get_graphical_environment();
		$graphical_environment->set_page_title($this->competition->get_title(), $this->lang['tsm.module.title']);
		$graphical_environment->get_seo_meta_data()->set_description($this->competition->get_description());
		$graphical_environment->get_seo_meta_data()->set_canonical_url(TeamsportmanagerUrlBuilder::display_item($this->category->get_id(), $this->category->get_rewrited_name(), $this->competition->get_id(), $this->competition->get_rewrited_title(), AppContext::get_request()->get_getint('page', 1)));

		$breadcrumb = $graphical_environment->get_breadcrumb();
		$breadcrumb->add($this->lang['tsm.module.title'], TeamsportmanagerUrlBuilder::home());

		$categories = array_reverse(TeamsportmanagerService::get_categories_manager()->get_parents($this->competition->get_category_id(), true));
		foreach ($categories as $id => $category)
		{
			if ($category->get_id() != Category::ROOT_CATEGORY)
				$breadcrumb->add($category->get_name(), TeamsportmanagerUrlBuilder::display_category($category->get_id(), $category->get_rewrited_name()));
		}
		$breadcrumb->add($this->competition->get_title(), TeamsportmanagerUrlBuilder::display_item($category->get_id(), $category->get_rewrited_name(), $this->competition->get_id(), $this->competition->get_rewrited_title()));

		return $response;
	}
}
?>
