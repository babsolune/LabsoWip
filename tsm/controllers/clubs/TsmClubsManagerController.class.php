<?php
/*##################################################
 *                      TsmClubsManagerController.class.php
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

class TsmClubsManagerController extends ModuleController
{
	private $lang;
	private $tsm_lang;
	private $view;

	public function execute(HTTPRequestCustom $request)
	{
		$this->check_club_auth();

		$this->init();

		$this->build_table();

		return $this->generate_response();
	}

	private function init()
	{
		$this->lang = LangLoader::get('club', 'tsm');
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

		$table_model = new SQLHTMLTableModel(TsmSetup::$tsm_club, 'table', $columns, new HTMLTableSortingRule('name', HTMLTableSortingRule::ASC));

		$table_model->set_caption($this->lang['clubs.management']);

		$table = new HTMLTable($table_model);

		$results = array();
		$result = $table_model->get_sql_results('clubs
			LEFT JOIN ' . DB_TABLE_MEMBER . ' member ON member.user_id = clubs.author_user_id',
			array('*', 'clubs.id')
		);
		foreach ($result as $row)
		{
			$club = new Club();
			$club->set_properties($row);
			$user = $club->get_author_user();

			$edit_club = new LinkHTMLElement(TsmUrlBuilder::edit_club($club->get_id()), '', array('title' => LangLoader::get_message('edit', 'common')), 'fa fa-edit');
			$delete_club = new LinkHTMLElement(TsmUrlBuilder::delete_club($club->get_id()), '', array('title' => LangLoader::get_message('delete', 'common'), 'data-confirmation' => 'delete-element'), 'fa fa-delete');

			$user_group_color = User::get_group_color($user->get_groups(), $user->get_level(), true);
			$author = $user->get_id() !== User::VISITOR_LEVEL ? new LinkHTMLElement(UserUrlBuilder::profile($user->get_id()), $user->get_display_name(), (!empty($user_group_color) ? array('style' => 'color: ' . $user_group_color) : array()), UserService::get_level_class($user->get_level())) : $user->get_display_name();

			$row = array(
				new HTMLTableRowCell(new LinkHTMLElement(TsmUrlBuilder::display_club($club->get_id(), $club->get_rewrited_name()), $club->get_name()), 'left'),
				new HTMLTableRowCell($author),
				new HTMLTableRowCell($club->get_status()),
				new HTMLTableRowCell($edit_club->display()),
				new HTMLTableRowCell($delete_club->display())
			);

			$results[] = new HTMLTableRow($row);
		}
		$table->set_rows($table_model->get_number_of_matching_rows(), $results);

		$this->view->put('table', $table->display());
	}

	private function check_club_auth()
	{
		if (!TsmClubsAuthService::check_club_auth()->moderation_club())
		{
			$error_controller = PHPBoostErrors::user_not_authorized();
			DispatchManager::redirect($error_controller);
		}
	}

	private function generate_response()
	{
		$response = new SiteDisplayResponse($this->view);

		$graphical_environment = $response->get_graphical_environment();
		$graphical_environment->set_page_title($this->lang['clubs.management'], $this->tsm_lang['tsm.module.title']);
		$graphical_environment->get_seo_meta_data()->set_canonical_url(TsmUrlBuilder::clubs_manager());

		$breadcrumb = $graphical_environment->get_breadcrumb();
		$breadcrumb->add($this->tsm_lang['tsm.module.title'], TsmUrlBuilder::home());
		$breadcrumb->add($this->lang['clubs.clubs'], TsmUrlBuilder::home_club());

		$breadcrumb->add($this->lang['clubs.management'], TsmUrlBuilder::clubs_manager());

		return $response;
	}
}
?>
