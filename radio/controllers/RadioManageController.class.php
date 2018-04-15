<?php
/*##################################################
 *                      RadioManageController.class.php
 *                            -------------------
 *   begin                : May, 02, 2017
 *   copyright            : (C) 2017 Sebastien LARTIGUE
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

class RadioManageController extends AdminModuleController
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
		$this->lang = LangLoader::get('common', 'radio');
		$this->view = new StringTemplate('# INCLUDE table #');
	}

	private function build_table()
	{
		$display_categories = RadioService::get_categories_manager()->get_categories_cache()->has_categories();

		$columns = array(
			new HTMLTableColumn(LangLoader::get_message('form.name', 'common'), 'name'),
			new HTMLTableColumn(LangLoader::get_message('category', 'categories-common'), 'id_category'),
			new HTMLTableColumn(LangLoader::get_message('form.release.day', 'common', 'radio'), 'release_day'),
			new HTMLTableColumn(LangLoader::get_message('form.time', 'common', 'radio'), 'start_date', 'end_date'),
			new HTMLTableColumn(LangLoader::get_message('status', 'common'), 'approbation_type'),
			new HTMLTableColumn('')
		);

		if (!$display_categories)
			unset($columns[1]);

		$table_model = new SQLHTMLTableModel(RadioSetup::$radio_table, 'table', $columns, new HTMLTableSortingRule('release_day', HTMLTableSortingRule::DESC));

		$table_model->set_caption($this->lang['radio.management']);

        $table = new HTMLTable($table_model);

		$results = array();
		$result = $table_model->get_sql_results('radio LEFT JOIN ' . DB_TABLE_MEMBER . ' member ON member.user_id = radio.author_user_id');
		foreach ($result as $row)
		{
			$radio = new Radio();
			$radio->set_properties($row);
			$category = $radio->get_category();
			$user = $radio->get_author_user();
			$schedule = $radio->get_start_date()->get_hours() . 'h' . $radio->get_start_date()->get_minutes() . ' - ' . $radio->get_end_date()->get_hours() . 'h' . $radio->get_end_date()->get_minutes();
			
			$edit_link = new LinkHTMLElement(RadioUrlBuilder::edit_radio($radio->get_id()), '', array('title' => LangLoader::get_message('edit', 'common')), 'fa fa-edit');
			$delete_link = new LinkHTMLElement(RadioUrlBuilder::delete_radio($radio->get_id()), '', array('title' => LangLoader::get_message('delete', 'common'), 'data-confirmation' => 'delete-element'), 'fa fa-delete');

			$user_group_color = User::get_group_color($user->get_groups(), $user->get_level(), true);
			$author = $user->get_id() !== User::VISITOR_LEVEL ? new LinkHTMLElement(UserUrlBuilder::profile($user->get_id()), $user->get_display_name(), (!empty($user_group_color) ? array('style' => 'color: ' . $user_group_color) : array()), UserService::get_level_class($user->get_level())) : $user->get_display_name();

			$row = array(
				new HTMLTableRowCell(new LinkHTMLElement(RadioUrlBuilder::display_radio($category->get_id(), $category->get_rewrited_name(), $radio->get_id(), $radio->get_rewrited_name()), $radio->get_name()), 'left'),
				new HTMLTableRowCell(new LinkHTMLElement(RadioUrlBuilder::display_category($category->get_id(), $category->get_rewrited_name()), $category->get_name())),
				new HTMLTableRowCell($radio->get_calendar()),
				new HTMLTableRowCell($schedule),
				new HTMLTableRowCell($radio->get_status()),
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
		if (!RadioAuthorizationsService::check_authorizations()->moderation())
		{
			$error_controller = PHPBoostErrors::user_not_authorized();
			DispatchManager::redirect($error_controller);
		}
	}

	private function generate_response()
	{
		$response = new SiteDisplayResponse($this->view);

		$graphical_environment = $response->get_graphical_environment();
		$graphical_environment->set_page_title($this->lang['radio.management'], $this->lang['radio']);
		$graphical_environment->get_seo_meta_data()->set_canonical_url(RadioUrlBuilder::manage_radio());

		$breadcrumb = $graphical_environment->get_breadcrumb();
		$breadcrumb->add($this->lang['radio'], RadioUrlBuilder::home());

		$breadcrumb->add($this->lang['radio.management'], RadioUrlBuilder::manage_radio());

		return $response;
	}
}
?>
