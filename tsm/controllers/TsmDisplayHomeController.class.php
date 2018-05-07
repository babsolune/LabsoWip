<?php
/*##################################################
 *                      TsmDisplayHomeController.class.php
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

class TsmDisplayHomeController extends ModuleController
{
	private $lang;
	private $season;
	private $config;

	public function execute(HTTPRequestCustom $request)
	{
		$this->init();

		$this->build_view();

		return $this->generate_response();
	}

	private function init()
	{
		$this->lang = LangLoader::get('common', 'tsm');
		$this->view = new FileTemplate('tsm/TsmDisplayHomeController.tpl');
		$this->view->add_lang($this->lang);
	}

	private function build_view()
	{
		$now = new Date();
		$request = AppContext::get_request();
		$this->config = TsmConfig::load();

		$this->build_home_view($now);
	}

	private function build_home_view(Date $now)
	{
		$this->view->put_all(array(
			'SEASONS_COLS_NB' => $this->config->get_seasons_cols_nb()
		));

		$result = PersistenceContext::get_querier()->select('SELECT *
		FROM ' . TsmSetup::$tsm_season . ' tsm_season
		ORDER BY season_date DESC'
		);

		while($row = $result->fetch())
		{
			$season = new Season();
			$season->set_properties($row);

			$this->view->assign_block_vars('seasons', $season->get_array_tpl_vars(

			));
		}
		$result->dispose();
	}

	private function generate_response()
	{
		$response = new SiteDisplayResponse($this->view);

		$graphical_environment = $response->get_graphical_environment();

		$graphical_environment->set_page_title($this->lang['tsm.module.title']);

		$graphical_environment->get_seo_meta_data()->set_description($this->lang['tsm.seo.season.description']);
		$graphical_environment->get_seo_meta_data()->set_canonical_url(TsmUrlBuilder::home());

		$breadcrumb = $graphical_environment->get_breadcrumb();
		$breadcrumb->add($this->lang['tsm.module.title'], TsmUrlBuilder::home());

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
