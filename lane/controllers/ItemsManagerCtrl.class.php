<?php
/**
 * @copyright 	&copy; 2005-2019 PHPBoost https://www.phpboost.com
 * @license 	https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE [babsolune@phpboost.com]
 * @version   	PHPBoost 5.2 - last update: 2018 05 25
 * @since   	PHPBoost 5.1 - 2018 05 25
*/

namespace Wiki\controllers;
use \Wiki\services\ModSetup;
use \Wiki\services\ModAuthorizations;
use \Wiki\services\ModServices;
use \Wiki\util\ModUrlBuilder;

class ItemsManagerCtrl extends ModuleController
{
	private $lang;
	private $view;

	public function execute(HTTPRequestCustom $request)
	{
		$this->check_authorizations();

		$this->init();

		$this->build_table();

		return $this->generate_response($current_page);
	}

	private function init()
	{
		$this->lang = LangLoader::get('common', 'wiki');
		$this->view = new StringTemplate('# INCLUDE table #');
	}

	private function build_table()
	{
		$display_categories = ModServices::get_categories_manager()->get_categories_cache()->has_categories();

		$columns = array(
			new HTMLTableColumn(LangLoader::get_message('form.title', 'common'), 'title'),
			new HTMLTableColumn(LangLoader::get_message('category', 'categories-common'), 'category_id'),
			new HTMLTableColumn(LangLoader::get_message('author', 'common'), 'display_name'),
			new HTMLTableColumn(LangLoader::get_message('form.date.creation', 'common'), 'date_created'),
			new HTMLTableColumn(LangLoader::get_message('status', 'common'), 'published'),
			new HTMLTableColumn('')
		);

		if (!$display_categories)
			unset($columns[1]);

		$table_model = new SQLHTMLTableModel(ModSetup::$items_table, 'table', $columns, new HTMLTableSortingRule('date_created', HTMLTableSortingRule::DESC));

		$table_model->set_caption($this->lang['items.manager']);

		$table = new HTMLTable($table_model);

		$results = array();
		$result = $table_model->get_sql_results('item
			LEFT JOIN ' . DB_TABLE_MEMBER . ' member ON member.user_id = item.author_user_id',
			array('*', 'item.id')
		);
		foreach ($result as $row)
		{
			$moditem = new ModItem();
			$moditem->set_properties($row);
			$category = $moditem->get_category();
			$user = $moditem->get_author_user();

			$edit_link = new LinkHTMLElement(ModUrlBuilder::edit_item($moditem->get_id()), '', array('title' => LangLoader::get_message('edit', 'common')), 'far fa-edit');
			$delete_link = new LinkHTMLElement(ModUrlBuilder::delete_item($moditem->get_id()), '', array('title' => LangLoader::get_message('delete', 'common'), 'data-confirmation' => 'delete-element'), 'far fa-delete');

			$user_group_color = User::get_group_color($user->get_groups(), $user->get_level(), true);
			$author = $user->get_id() !== User::VISITOR_LEVEL ? new LinkHTMLElement(UserUrlBuilder::profile($user->get_id()), $user->get_display_name(), (!empty($user_group_color) ? array('style' => 'color: ' . $user_group_color) : array()), UserService::get_level_class($user->get_level())) : $user->get_display_name();

			$br = new BrHTMLElement();

			$dates = '';
			if ($moditem->get_publishing_start_date() != null && $moditem->get_publishing_end_date() != null)
			{
				$dates = LangLoader::get_message('form.date.start', 'common') . ' ' . $moditem->get_publishing_start_date()->format(Date::FORMAT_DAY_MONTH_YEAR_HOUR_MINUTE) . $br->display() . LangLoader::get_message('form.date.end', 'common') . ' ' . $moditem->get_publishing_end_date()->format(Date::FORMAT_DAY_MONTH_YEAR_HOUR_MINUTE);
			}
			else
			{
				if ($moditem->get_publishing_start_date() != null)
					$dates = $moditem->get_publishing_start_date()->format(Date::FORMAT_DAY_MONTH_YEAR_HOUR_MINUTE);
				else
				{
					if ($moditem->get_publishing_end_date() != null)
						$dates = LangLoader::get_message('until', 'main') . ' ' . $moditem->get_publishing_end_date()->format(Date::FORMAT_DAY_MONTH_YEAR_HOUR_MINUTE);
				}
			}

			$start_and_end_dates = new SpanHTMLElement($dates, array(), 'smaller');

			$row = array(
				new HTMLTableRowCell(new LinkHTMLElement(ModUrlBuilder::display_item($category->get_id(), $category->get_rewrited_name(), $moditem->get_id(), $moditem->get_rewrited_title()), $moditem->get_title()), 'left'),
				new HTMLTableRowCell(new LinkHTMLElement(ModUrlBuilder::display_category($category->get_id(), $category->get_rewrited_name()), $category->get_name())),
				new HTMLTableRowCell($author),
				new HTMLTableRowCell($moditem->get_date_created()->format(Date::FORMAT_DAY_MONTH_YEAR_HOUR_MINUTE)),
				new HTMLTableRowCell($moditem->get_status() . $br->display() . ($dates ? $start_and_end_dates->display() : '')),
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
		if (!ModAuthorizations::check_authorizations()->moderation())
		{
			$error_controller = PHPBoostErrors::user_not_authorized();
			DispatchManager::redirect($error_controller);
		}
	}

	private function generate_response()
	{
		$response = new SiteDisplayResponse($this->view);

		$graphical_environment = $response->get_graphical_environment();
		$graphical_environment->set_page_title($this->lang['items.manager'], $this->lang['module.title']);
		$graphical_environment->get_seo_meta_data()->set_canonical_url(ModUrlBuilder::manage_items());

		$breadcrumb = $graphical_environment->get_breadcrumb();
		$breadcrumb->add($this->lang['module.title'], ModUrlBuilder::home());

		$breadcrumb->add($this->lang['items.manager'], ModUrlBuilder::manage_items());

		return $response;
	}
}
?>
