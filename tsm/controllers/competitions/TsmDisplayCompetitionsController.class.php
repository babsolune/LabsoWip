<?php
/*##################################################
 *                        TsmDisplayCompetitionController.class.php
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
    private $lang,
            $tsm_lang,
            $tpl,
            $competition;

	private function init()
	{
		$this->lang = LangLoader::get('competition', 'tsm');
        $this->tsm_lang = LangLoader::get('common', 'tsm');
		$this->tpl = new FileTemplate('tsm/competitions/TsmDisplayCompetitionController.tpl');
		$this->tpl->add_lang($this->tsm_lang);
		$this->tpl->add_lang($this->lang);
		$config = TsmConfig::load();
	}

    public function execute(HTTPRequestCustom $request)
	{
		$this->init();
		$this->build_view();
		return $this->generate_response();
	}

	private function build_view()
	{
        $competition = $this->get_competition();
        $this->tpl->put_all(array_merge($competition->get_array_tpl_vars(), array(
            'C_DEFAULT_MAP_ADDRESS' => ''
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

	private function generate_response()
	{
		$competition = $this->get_competition();
		$response = new SiteDisplayResponse($this->tpl);

		$graphical_environment = $response->get_graphical_environment();
		$graphical_environment->set_page_title($competition->get_name(), $this->tsm_lang['tsm.module.title']);
		$graphical_environment->get_seo_meta_data()->set_canonical_url(TsmUrlBuilder::display_competition($competition->get_id(), $competition->get_rewrited_name()));

		$breadcrumb = $graphical_environment->get_breadcrumb();
		$breadcrumb->add($this->tsm_lang['tsm.module.title'],TsmUrlBuilder::home());
		$breadcrumb->add($this->lang['competitions.competitions'], TsmUrlBuilder::home_competition());
		$breadcrumb->add($competition->get_name(), TsmUrlBuilder::display_competition($competition->get_id(), $competition->get_rewrited_name()));

		return $response;
	}
}
?>
