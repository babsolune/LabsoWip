<?php
/*##################################################
 *                      TsmDisplaySeasonController.class.php
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

class TsmDisplaySeasonController extends ModuleController
{
	private $lang,
			$season_lang,
			$tsm_lang,
			$config,
			$tpl,
			$season;

	public function execute(HTTPRequestCustom $request)
	{
		$this->init();

		$this->check_season_auth();

		$this->build_view();

		return $this->generate_response();
	}

	private function init()
	{
		$this->lang = LangLoader::get('common', 'tsm');
		$this->tsm_lang = LangLoader::get('common', 'tsm');
		$this->season_lang = LangLoader::get('season', 'tsm');
		$this->config = TsmConfig::load();
		$this->tpl = new FileTemplate('tsm/TsmDisplaySeasonController.tpl');
		$this->tpl->add_lang($this->lang);
		$this->tpl->add_lang($this->season_lang);
		$this->tpl->add_lang($this->tsm_lang);
	}

	private function build_view()
	{
		$season = $this->get_season();

		$competitions = TsmCompetitionsCache::load()->get_competition();

		foreach ($competitions as $competition) {
			$this->tpl->assign_block_vars('competitions', array(
				'NAME' => $competition['division_id'],
				'U_COMPETITION' => $competition['id'] . '-' . $competition['division_id']
			));
		}

		// $result = PersistenceContext::get_querier()->select('SELECT tsm_compet.*
		// 	FROM ' . TsmSetup::$tsm_competition . ' tsm_compet
		// 	WHERE tsm_compet.season_id = :season_id
		// 	ORDER BY tsm_compet.id ASC', array(
		// 		'season_id' => $season->get_id()
		// 	)
		// );
		//
		// $this->tpl->put_all(array(
		// 	'COMPET_COLS_NBR' => $this->config->get_competitions_cols_nb()
		// ));
		//
		// while($row = $result->fetch())
		// {
		// 	$competition = new Competition();
		// 	$competition->set_properties($row);
		//
		// 	$this->tpl->assign_block_vars('competitions', $competition->get_array_tpl_vars());
		// }
		// $result->dispose();
	}

	private function get_season()
	{
		if ($this->season === null)
		{
			$id = AppContext::get_request()->get_getint('id', 0);
			if (!empty($id))
			{
				try
				{
					$this->season = TsmSeasonsService::get_season('WHERE seasons.id=:id', array('id' => $id));
				}
				catch (RowNotFoundException $e)
				{
					$error_controller = PHPBoostErrors::unexisting_page();
   					DispatchManager::redirect($error_controller);
				}
			}
			else
				$this->season = new Season();
		}
		return $this->season;
	}

    private function check_season_auth()
    {
        $season = $this->get_season();

		if ($season->get_id() === null)
		{
			if (!$season->is_authorized_to_add())
			{
				$error_controller = PHPBoostErrors::user_not_authorized();
				DispatchManager::redirect($error_controller);
			}
		}
		else
		{
			if (!$season->is_authorized_to_edit())
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
		$season = $this->get_season();
		$response = new SiteDisplayResponse($this->tpl);

		$graphical_environment = $response->get_graphical_environment();

		$graphical_environment->set_page_title($this->tsm_lang['tsm.module.title']);

		$graphical_environment->get_seo_meta_data()->set_description($this->tsm_lang['tsm.seo.division.description']);
		$graphical_environment->get_seo_meta_data()->set_canonical_url(TsmUrlBuilder::display_season($season->get_id(),$season->get_name()));

		$breadcrumb = $graphical_environment->get_breadcrumb();
		$breadcrumb->add($this->tsm_lang['tsm.module.title'], TsmUrlBuilder::Home());
		$breadcrumb->add($season->get_name(), TsmUrlBuilder::display_season($season->get_id(), $season->get_name()));

		return $response;
	}

	public static function get_view()
	{
		$object = new self();
		$object->init();
		$object->check_season_auth();
		$object->build_view();
		return $object->view;
	}
}
?>
