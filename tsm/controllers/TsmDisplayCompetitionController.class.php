<?php
/*##################################################
 *		       TsmDisplayCompetitionController.class.php
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

class TsmDisplayCompetitionController extends ModuleController
{
	private $lang;
	private $tsm_lang;
	private $tpl;
	private $competition;
	private $season;
	private $division;
	private $club;

	public function execute(HTTPRequestCustom $request)
	{
		$this->check_competition_auth();

		$this->init();

		$this->build_view();

		return $this->generate_response();
	}

	private function init()
	{
		$this->lang = LangLoader::get('competition', 'tsm');
		$this->tsm_lang = LangLoader::get('common', 'tsm');
		$this->tpl = new FileTemplate('tsm/TsmDisplayCompetitionController.tpl');
		$this->tpl->add_lang($this->lang);
		$this->tpl->add_lang($this->tsm_lang);
	}

	private function build_view()
	{
		$competition = $this->get_competition();
		$this->tpl->put_all(array_merge($competition->get_array_tpl_vars(), array(

		)));
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
					$this->competition = TsmCompetitionsService::get_competition('WHERE competitions.id=:id', array('id' => $id));
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

	private function count_number_view(HTTPRequestCustom $request)
	{
		if (!$this->competition->is_published())
		{
			$this->tpl->put('NOT_VISIBLE_MESSAGE', MessageHelper::display(LangLoader::get_message('element.not_visible', 'status-messages-common'), MessageHelper::WARNING));
		}
		else
		{
			if ($request->get_url_referrer() && !TextHelper::strstr($request->get_url_referrer(), TsmUrlBuilder::display_competition($this->competition->get_season()->get_id(), $this->competition->get_season()->get_name(), $this->competition->get_id(), $this->competition->get_division()->get_rewrited_name())->rel()))
			{
				$this->competition->set_views_nb($this->competition->get_views_nb() + 1);
				TsmCompetitionsService::update_views_nb($this->competition);
			}
		}
	}

    private function check_competition_auth()
    {
        $competition = $this->get_competition();

		if ($competition->get_id() === null)
		{
			if (!$competition->is_authorized_to_add())
			{
				$error_controller = PHPBoostErrors::user_not_authorized();
				DispatchManager::redirect($error_controller);
			}
		}
		else
		{
			if (!$competition->is_authorized_to_edit())
			{
				$error_controller = PHPBoostErrors::user_not_authorized();
				DispatchManager::redirect($error_controller);
			}
		}
		if (AppContext::get_current_user()->is_readonly())
		{
			$controller = PHPBoostErrors::user_in_read_only();
			DispatchManager::redirect($controller);
		}
    }

	private function generate_response()
	{
		$response = new SiteDisplayResponse($this->tpl);

		// $competition = $this->get_competition();
		// $season = $competition->get_season();
		// $division = $competition->get_division();
		//
		//
		// $graphical_environment = $response->get_graphical_environment();
		// $graphical_environment->set_page_title($division->get_name(), $this->tsm_lang['tsm.module.title']);
		// $graphical_environment->get_seo_meta_data()->set_description($this->tsm_lang['tsm.module.title']);
		// $graphical_environment->get_seo_meta_data()->set_canonical_url(TsmUrlBuilder::display_competition($season->get_id(), $season->get_name(), $competition->get_id(), $division()->get_rewrited_name()));
		//
		// $breadcrumb = $graphical_environment->get_breadcrumb();
		// $breadcrumb->add($this->tsm_lang['tsm.module.title'], TsmUrlBuilder::home());
		// $breadcrumb->add($division->get_name(), TsmUrlBuilder::display_competition($season->get_id(), $season->get_name(), $competition->get_id(), $division()->get_rewrited_name()));

		return $response;
	}
}
?>
