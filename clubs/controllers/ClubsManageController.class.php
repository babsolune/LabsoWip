<?php
/*##################################################
 *                      ClubsManageController.class.php
 *                            -------------------
 *   begin                : June 23, 2017
 *   copyright            : (C) 2017 Sebastien LARTIGUE
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

class ClubsManageController extends AdminModuleController
{
	private $lang;
	private $view;

	public function execute(HTTPRequestCustom $request)
	{
		$this->check_authorizations();

		$this->init();

		$this->build_table();

		return $this->generate_response();
	}

	private function init()
	{
		$this->lang = LangLoader::get('common', 'clubs');
		$this->view = new StringTemplate('# INCLUDE table #');
	}

	private function build_table()
	{
		$display_categories = ClubsService::get_categories_manager()->get_categories_cache()->has_categories();

		$columns = array(
			new HTMLTableColumn(LangLoader::get_message('form.name', 'common'), 'name'),
			new HTMLTableColumn(LangLoader::get_message('category', 'categories-common'), 'id_category'),
			new HTMLTableColumn(LangLoader::get_message('author', 'common'), 'display_name'),
			new HTMLTableColumn(LangLoader::get_message('form.date.creation', 'common'), 'creation_date'),
			new HTMLTableColumn(LangLoader::get_message('status', 'common'), 'approbation_type'),
			new HTMLTableColumn('')
		);

		if (!$display_categories)
			unset($columns[1]);

		$table_model = new SQLHTMLTableModel(ClubsSetup::$clubs_table, 'table', $columns, new HTMLTableSortingRule('creation_date', HTMLTableSortingRule::DESC));

		$table_model->set_caption($this->lang['clubs.management']);

		$table = new HTMLTable($table_model);

		$results = array();
		$result = $table_model->get_sql_results('clubs
			LEFT JOIN ' . DB_TABLE_COMMENTS_TOPIC . ' com ON com.id_in_module = clubs.id AND com.module_id = \'clubs\'
			LEFT JOIN ' . DB_TABLE_AVERAGE_NOTES . ' notes ON notes.id_in_module = clubs.id AND notes.module_name = \'clubs\'
			LEFT JOIN ' . DB_TABLE_NOTE . ' note ON note.id_in_module = clubs.id AND note.module_name = \'clubs\' AND note.user_id = ' . AppContext::get_current_user()->get_id() . '
			LEFT JOIN ' . DB_TABLE_MEMBER . ' member ON member.user_id = clubs.author_user_id',
			array('*', 'clubs.id')
		);
		foreach ($result as $row)
		{
			$club = new Club();
			$club->set_properties($row);
			$category = $club->get_category();
			$user = $club->get_author_user();

			$edit_club = new LinkHTMLElement(ClubsUrlBuilder::edit($club->get_id()), '', array('title' => LangLoader::get_message('edit', 'common')), 'fa fa-edit');
			$delete_club = new LinkHTMLElement(ClubsUrlBuilder::delete($club->get_id()), '', array('title' => LangLoader::get_message('delete', 'common'), 'data-confirmation' => 'delete-element'), 'fa fa-delete');

			$user_group_color = User::get_group_color($user->get_groups(), $user->get_level(), true);
			$author = $user->get_id() !== User::VISITOR_LEVEL ? new LinkHTMLElement(UserUrlBuilder::profile($user->get_id()), $user->get_display_name(), (!empty($user_group_color) ? array('style' => 'color: ' . $user_group_color) : array()), UserService::get_level_class($user->get_level())) : $user->get_display_name();

			$row = array(
				new HTMLTableRowCell(new LinkHTMLElement(ClubsUrlBuilder::display($category->get_id(), $category->get_rewrited_name(), $club->get_id(), $club->get_rewrited_name()), $club->get_name()), 'left'),
				new HTMLTableRowCell(new LinkHTMLElement(ClubsUrlBuilder::display_category($category->get_id(), $category->get_rewrited_name()), $category->get_name())),
				new HTMLTableRowCell($author),
				new HTMLTableRowCell($club->get_creation_date()->format(Date::FORMAT_DAY_MONTH_YEAR_HOUR_MINUTE)),
				new HTMLTableRowCell($club->get_status()),
				new HTMLTableRowCell($edit_club->display() . $delete_club->display())
			);

			if (!$display_categories)
				unset($row[1]);

			$results[] = new HTMLTableRow($row);
		}
		$table->set_rows($table_model->get_number_of_matching_rows(), $results);

		$this->view->put('table', $table->display());
	}

	private function check_authorizations()
	{
		if (!ClubsAuthorizationsService::check_authorizations()->moderation())
		{
			$error_controller = PHPBoostErrors::user_not_authorized();
			DispatchManager::redirect($error_controller);
		}
	}

	private function generate_response()
	{
		$response = new SiteDisplayResponse($this->view);

		$graphical_environment = $response->get_graphical_environment();
		$graphical_environment->set_page_title($this->lang['clubs.management'], $this->lang['module_title']);
		$graphical_environment->get_seo_meta_data()->set_canonical_url(ClubsUrlBuilder::manage());

		$breadcrumb = $graphical_environment->get_breadcrumb();
		$breadcrumb->add($this->lang['module_title'], ClubsUrlBuilder::home());

		$breadcrumb->add($this->lang['clubs.management'], ClubsUrlBuilder::manage());

		return $response;
	}
}
?>
