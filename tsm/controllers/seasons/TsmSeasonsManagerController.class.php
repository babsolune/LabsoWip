<?php
/*##################################################
 *                      TsmSeasonsManagerController.class.php
 *                            -------------------
 *   begin                : February 13, 2018
 *   copyright            : (C) 2018 Sebastien LARTIGUE
 *   email                : babsolune@phpboost.com
 *
 *
 ###################################################
 *
 * This program is a free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
 ###################################################*/

 /**
 * @author Sebastien LARTIGUE <babsolune@phpboost.com>
 */

class TsmSeasonsManagerController extends ModuleController
{
	private $lang;
	private $tsm_lang;
	private $view;

	public function execute(HTTPRequestCustom $request)
	{
		$this->check_season_auth();

		$this->init();

		$this->build_table();

		return $this->generate_response();
	}

	private function init()
	{
		$this->lang = LangLoader::get('season', 'tsm');
		$this->tsm_lang = LangLoader::get('common', 'tsm');
		$this->view = new StringTemplate('# INCLUDE table #');
	}

	private function build_table()
	{
		$columns = array(
			new HTMLTableColumn(LangLoader::get_message('form.name', 'common'), 'name'),
			new HTMLTableColumn(LangLoader::get_message('author', 'common'), 'display_name'),
			new HTMLTableColumn(LangLoader::get_message('status', 'common'), 'approbation_type'),
			new HTMLTableColumn(''),
			new HTMLTableColumn('')
		);

		$table_model = new SQLHTMLTableModel(TsmSetup::$tsm_season, 'table', $columns, new HTMLTableSortingRule('season_date', HTMLTableSortingRule::ASC));

		$table_model->set_caption($this->lang['seasons.management']);

		$table = new HTMLTable($table_model);

		$results = array();
		$result = $table_model->get_sql_results('seasons
			LEFT JOIN ' . DB_TABLE_MEMBER . ' member ON member.user_id = seasons.author_user_id',
			array('*', 'seasons.id')
		);
		foreach ($result as $row)
		{
			$season = new Season();
			$season->set_properties($row);
			$user = $season->get_author_user();

			$edit_season = new LinkHTMLElement(TsmUrlBuilder::edit_season($season->get_id()), '', array('title' => LangLoader::get_message('edit', 'common')), 'fa fa-edit');
			$delete_season = new LinkHTMLElement(TsmUrlBuilder::delete_season($season->get_id()), '', array('title' => LangLoader::get_message('delete', 'common'), 'data-confirmation' => 'delete-element'), 'fa fa-delete');

			$user_group_color = User::get_group_color($user->get_groups(), $user->get_level(), true);
			$author = $user->get_id() !== User::VISITOR_LEVEL ? new LinkHTMLElement(UserUrlBuilder::profile($user->get_id()), $user->get_display_name(), (!empty($user_group_color) ? array('style' => 'color: ' . $user_group_color) : array()), UserService::get_level_class($user->get_level())) : $user->get_display_name();

			$row = array(
				new HTMLTableRowCell(new LinkHTMLElement(TsmUrlBuilder::display_season($season->get_id(), $season->get_name()), $season->get_name()), 'left'),
				new HTMLTableRowCell($author),
				new HTMLTableRowCell($season->get_status()),
				new HTMLTableRowCell($edit_season->display()),
				new HTMLTableRowCell($delete_season->display())
			);

			$results[] = new HTMLTableRow($row);
		}
		$table->set_rows($table_model->get_number_of_matching_rows(), $results);

		$this->view->put('table', $table->display());
	}

	private function check_season_auth()
	{
		if (!TsmSeasonsAuthService::check_season_auth()->moderation_season())
		{
			$error_controller = PHPBoostErrors::user_not_authorized();
			DispatchManager::redirect($error_controller);
		}
	}

	private function generate_response()
	{
		$response = new SiteDisplayResponse($this->view);

		$graphical_environment = $response->get_graphical_environment();
		$graphical_environment->set_page_title($this->lang['seasons.management'], $this->tsm_lang['tsm.module.title']);
		$graphical_environment->get_seo_meta_data()->set_canonical_url(TsmUrlBuilder::seasons_manager());

		$breadcrumb = $graphical_environment->get_breadcrumb();
		$breadcrumb->add($this->tsm_lang['tsm.module.title'], TsmUrlBuilder::home());

		$breadcrumb->add($this->lang['seasons.management'], TsmUrlBuilder::seasons_manager());

		return $response;
	}
}
?>
