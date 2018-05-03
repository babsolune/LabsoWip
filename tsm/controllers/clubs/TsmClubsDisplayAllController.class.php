<?php
/*##################################################
 *                        TsmClubsDisplayAllController.class.php
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

class TsmClubsDisplayAllController extends ModuleController
{
    private $lang,
            $tsm_lang,
            $config,
            $tpl;

	public function execute(HTTPRequestCustom $request)
	{
		$this->init();
		$this->build_view($request);
		return $this->generate_response();
	}

	private function init()
	{
		$this->lang = LangLoader::get('club', 'tsm');
        $this->tsm_lang = LangLoader::get('common', 'tsm');
		$this->tpl = new FileTemplate('tsm/clubs/TsmClubsDisplayAllController.tpl');
		$this->tpl->add_lang($this->lang);
		$this->tpl->add_lang($this->tsm_lang);
		$this->config = TsmConfig::load();
	}

	private function build_view(HTTPRequestCustom $request)
	{
		$this->tpl->put_all(array(
			'C_MOSAIC' => $this->config->get_clubs_display() == TsmConfig::MOSAIC_DISPLAY,
			'C_LIST' => $this->config->get_clubs_display() == TsmConfig::LIST_DISPLAY,
			'C_TABLE' => $this->config->get_clubs_display() == TsmConfig::TABLE_DISPLAY,
            'C_NEW_WINDOW' => $this->config->get_new_window(),
            'C_MODERATE' => AppContext::get_current_user()->check_level(User::ADMIN_LEVEL),
			'U_CONFIG' => TsmUrlBuilder::clubs_config()->rel(),
			'COLS_NB' => $this->config->get_clubs_cols_nb(),
		));

		$result = PersistenceContext::get_querier()->select('SELECT tsm_club.*, member.*
    		FROM '. TsmSetup::$tsm_club .' tsm_club
            LEFT JOIN ' . DB_TABLE_MEMBER . ' member ON member.user_id = tsm_club.author_user_id
			WHERE publication = 1
    		ORDER BY name ASC', array(
				'user_id' => AppContext::get_current_user()->get_id()
        ));

        while ($row = $result->fetch())
        {
            $club = new Club();
            $club->set_properties($row);
            $this->tpl->assign_block_vars('clubs', $club->get_array_tpl_vars());
        }
		$result->dispose();
    }

	private function generate_response()
	{
		$response = new SiteDisplayResponse($this->tpl);

		$graphical_environment = $response->get_graphical_environment();

		$graphical_environment->set_page_title($this->tsm_lang['tsm.module.title']);
		$graphical_environment->get_seo_meta_data()->set_description($this->tsm_lang['tsm.module.title']);
		$graphical_environment->get_seo_meta_data()->set_canonical_url(TsmUrlBuilder::home_club());

		$breadcrumb = $graphical_environment->get_breadcrumb();
		$breadcrumb->add($this->tsm_lang['tsm.module.title'], TsmUrlBuilder::home());
		$breadcrumb->add($this->lang['clubs.clubs'], TsmUrlBuilder::home_club());

		return $response;
	}

	public static function get_view()
	{
		$object = new self();
		$object->init();
		$object->build_view();
		return $object->view;
	}
}


?>
