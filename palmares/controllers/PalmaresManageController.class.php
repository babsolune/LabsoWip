<?php
/*##################################################
 *                      PalmaresManageController.class.php
 *                            -------------------
 *   begin                : June 24, 2013
 *   copyright            : (C) 2013 Kevin MASSY
 *   email                : kevin.massy@phpboost.com
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

class PalmaresManageController extends AdminModuleController
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
		$this->lang = LangLoader::get('common', 'palmares');
		$this->view = new StringTemplate('# INCLUDE table #');
	}

	private function build_table()
	{
		$display_categories = PalmaresService::get_categories_manager()->get_categories_cache()->has_categories();
		
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
		
		$table_model = new SQLHTMLTableModel(PalmaresSetup::$palmares_table, 'table', $columns, new HTMLTableSortingRule('creation_date', HTMLTableSortingRule::DESC));
		
		$table_model->set_caption($this->lang['palmares.management']);

        $table = new HTMLTable($table_model);
		
		$results = array();
		$result = $table_model->get_sql_results('palmares LEFT JOIN ' . DB_TABLE_MEMBER . ' member ON member.user_id = palmares.author_user_id');
		foreach ($result as $row)
		{
			$palmares = new Palmares();
			$palmares->set_properties($row);
			$category = $palmares->get_category();
			$user = $palmares->get_author_user();

			$edit_link = new LinkHTMLElement(PalmaresUrlBuilder::edit_palmares($palmares->get_id()), '', array('title' => LangLoader::get_message('edit', 'common')), 'fa fa-edit');
			$delete_link = new LinkHTMLElement(PalmaresUrlBuilder::delete_palmares($palmares->get_id()), '', array('title' => LangLoader::get_message('delete', 'common'), 'data-confirmation' => 'delete-element'), 'fa fa-delete');

			$user_group_color = User::get_group_color($user->get_groups(), $user->get_level(), true);
			$author = $user->get_id() !== User::VISITOR_LEVEL ? new LinkHTMLElement(UserUrlBuilder::profile($user->get_id()), $user->get_display_name(), (!empty($user_group_color) ? array('style' => 'color: ' . $user_group_color) : array()), UserService::get_level_class($user->get_level())) : $user->get_display_name();

			$row = array(
				new HTMLTableRowCell(new LinkHTMLElement(PalmaresUrlBuilder::display_palmares($category->get_id(), $category->get_rewrited_name(), $palmares->get_id(), $palmares->get_rewrited_name()), $palmares->get_name()), 'left'),
				new HTMLTableRowCell(new LinkHTMLElement(PalmaresUrlBuilder::display_category($category->get_id(), $category->get_rewrited_name()), $category->get_name())),
				new HTMLTableRowCell($author),
				new HTMLTableRowCell($palmares->get_creation_date()->format(Date::FORMAT_DAY_MONTH_YEAR_HOUR_MINUTE)),
				new HTMLTableRowCell($palmares->get_status()),
				new HTMLTableRowCell($edit_link->display() . $delete_link->display())
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
		if (!PalmaresAuthorizationsService::check_authorizations()->moderation())
		{
			$error_controller = PHPBoostErrors::user_not_authorized();
			DispatchManager::redirect($error_controller);
		}
	}
	
	private function generate_response()
	{
		$response = new SiteDisplayResponse($this->view);

		$graphical_environment = $response->get_graphical_environment();
		$graphical_environment->set_page_title($this->lang['palmares.management'], $this->lang['palmares']);
		$graphical_environment->get_seo_meta_data()->set_canonical_url(PalmaresUrlBuilder::manage_palmares());
		
		$breadcrumb = $graphical_environment->get_breadcrumb();
		$breadcrumb->add($this->lang['palmares'], PalmaresUrlBuilder::home());
		
		$breadcrumb->add($this->lang['palmares.management'], PalmaresUrlBuilder::manage_palmares());
		
		return $response;
	}
}
?>