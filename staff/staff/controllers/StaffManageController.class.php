<?php
/**
 * @copyright 	&copy; 2005-2019 PHPBoost
 * @license 	https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @version   	PHPBoost 5.2 - last update: 2017 11 05
 * @since   	PHPBoost 5.1 - 2017 06 29
*/

class StaffManageController extends AdminModuleController
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
		$this->lang = LangLoader::get('common', 'staff');
		$this->view = new StringTemplate('# INCLUDE table #');
	}

	private function build_table()
	{
		$display_categories = StaffService::get_categories_manager()->get_categories_cache()->has_categories();

		$columns = array(
			new HTMLTableColumn(LangLoader::get_message('form.name', 'common'), 'lastname'),
			new HTMLTableColumn(LangLoader::get_message('category', 'categories-common'), 'id_category'),
			new HTMLTableColumn($this->lang['staff.form.role'], 'role'),
			new HTMLTableColumn(LangLoader::get_message('form.date.creation', 'common'), 'creation_date'),
			new HTMLTableColumn(LangLoader::get_message('status', 'common'), 'publication'),
			new HTMLTableColumn('')
		);

		if (!$display_categories)
			unset($columns[1]);

		$table_model = new SQLHTMLTableModel(StaffSetup::$staff_table, 'table', $columns, new HTMLTableSortingRule('creation_date', HTMLTableSortingRule::DESC));

		$table_model->set_caption($this->lang['staff.management']);

		$table = new HTMLTable($table_model);

		$results = array();
		$result = $table_model->get_sql_results('staff
			LEFT JOIN ' . DB_TABLE_MEMBER . ' member ON member.user_id = staff.author_user_id',
			array('*', 'staff.id')
		);
		foreach ($result as $row)
		{
			$adherent = new Adherent();
			$adherent->set_properties($row);
			$category = $adherent->get_category();
			$user = $adherent->get_author_user();

			$edit_adherent = new LinkHTMLElement(StaffUrlBuilder::edit($adherent->get_id()), '', array('title' => LangLoader::get_message('edit', 'common')), 'fa fa-edit');
			$delete_adherent = new LinkHTMLElement(StaffUrlBuilder::delete($adherent->get_id()), '', array('title' => LangLoader::get_message('delete', 'common'), 'data-confirmation' => 'delete-element'), 'fa fa-delete');

			$row = array(
				new HTMLTableRowCell(new LinkHTMLElement(StaffUrlBuilder::display($category->get_id(), $category->get_rewrited_name(), $adherent->get_id(), $adherent->get_rewrited_name()), $adherent->get_firstname() . ' ' . $adherent->get_lastname()), 'left'),
				new HTMLTableRowCell(new LinkHTMLElement(StaffUrlBuilder::display_category($category->get_id(), $category->get_rewrited_name()), $category->get_name())),
				new HTMLTableRowCell($adherent->get_role()),
				new HTMLTableRowCell($adherent->get_creation_date()->format(Date::FORMAT_DAY_MONTH_YEAR_HOUR_MINUTE)),
				new HTMLTableRowCell($adherent->get_status()),
				new HTMLTableRowCell($edit_adherent->display() . $delete_adherent->display())
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
		if (!StaffAuthorizationsService::check_authorizations()->moderation())
		{
			$error_controller = PHPBoostErrors::user_not_authorized();
			DispatchManager::redirect($error_controller);
		}
	}

	private function generate_response()
	{
		$response = new SiteDisplayResponse($this->view);

		$graphical_environment = $response->get_graphical_environment();
		$graphical_environment->set_page_title($this->lang['staff.management'], $this->lang['staff.module.title']);
		$graphical_environment->get_seo_meta_data()->set_canonical_url(StaffUrlBuilder::manage());

		$breadcrumb = $graphical_environment->get_breadcrumb();
		$breadcrumb->add($this->lang['staff.module.title'], StaffUrlBuilder::home());

		$breadcrumb->add($this->lang['staff.management'], StaffUrlBuilder::manage());

		return $response;
	}
}
?>
