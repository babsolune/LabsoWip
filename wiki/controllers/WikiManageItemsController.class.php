<?php
/*##################################################
 *		    WikiManageItemsController.class.php
 *                            -------------------
 *   begin                : May 25, 2018
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

class WikiManageItemsController extends ModuleController
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
		$this->lang = LangLoader::get('common', 'wiki');
		$this->view = new StringTemplate('# INCLUDE table #');
	}

	private function build_table()
	{
		$display_categories = WikiService::get_categories_manager()->get_categories_cache()->has_categories();

		$columns = array(
			new HTMLTableColumn(LangLoader::get_message('form.title', 'common'), 'title'),
			new HTMLTableColumn(LangLoader::get_message('category', 'categories-common'), 'id_category'),
			new HTMLTableColumn(LangLoader::get_message('author', 'common'), 'display_name'),
			new HTMLTableColumn(LangLoader::get_message('form.date.creation', 'common'), 'date_created'),
			new HTMLTableColumn(LangLoader::get_message('status', 'common'), 'published'),
			new HTMLTableColumn('')
		);

		if (!$display_categories)
			unset($columns[1]);

		$table_model = new SQLHTMLTableModel(WikiSetup::$wiki_table, 'table', $columns, new HTMLTableSortingRule('date_created', HTMLTableSortingRule::DESC));

		$table_model->set_caption($this->lang['wiki_management']);

		$table = new HTMLTable($table_model);

		$results = array();
		$result = $table_model->get_sql_results('wiki
			LEFT JOIN ' . DB_TABLE_MEMBER . ' member ON member.user_id = wiki.author_user_id',
			array('*', 'wiki.id')
		);
		foreach ($result as $row)
		{
			$document = new Document();
			$document->set_properties($row);
			$category = $document->get_category();
			$user = $document->get_author_user();

			$edit_link = new LinkHTMLElement(WikiUrlBuilder::edit_item($document->get_id()), '', array('title' => LangLoader::get_message('edit', 'common')), 'far fa-edit');
			$delete_link = new LinkHTMLElement(WikiUrlBuilder::delete_item($document->get_id()), '', array('title' => LangLoader::get_message('delete', 'common'), 'data-confirmation' => 'delete-element'), 'far fa-delete');

			$user_group_color = User::get_group_color($user->get_groups(), $user->get_level(), true);
			$author = $user->get_id() !== User::VISITOR_LEVEL ? new LinkHTMLElement(UserUrlBuilder::profile($user->get_id()), $user->get_display_name(), (!empty($user_group_color) ? array('style' => 'color: ' . $user_group_color) : array()), UserService::get_level_class($user->get_level())) : $user->get_display_name();

			$br = new BrHTMLElement();

			$dates = '';
			if ($document->get_publishing_start_date() != null && $document->get_publishing_end_date() != null)
			{
				$dates = LangLoader::get_message('form.date.start', 'common') . ' ' . $document->get_publishing_start_date()->format(Date::FORMAT_DAY_MONTH_YEAR_HOUR_MINUTE) . $br->display() . LangLoader::get_message('form.date.end', 'common') . ' ' . $document->get_publishing_end_date()->format(Date::FORMAT_DAY_MONTH_YEAR_HOUR_MINUTE);
			}
			else
			{
				if ($document->get_publishing_start_date() != null)
					$dates = $document->get_publishing_start_date()->format(Date::FORMAT_DAY_MONTH_YEAR_HOUR_MINUTE);
				else
				{
					if ($document->get_publishing_end_date() != null)
						$dates = LangLoader::get_message('until', 'main') . ' ' . $document->get_publishing_end_date()->format(Date::FORMAT_DAY_MONTH_YEAR_HOUR_MINUTE);
				}
			}

			$start_and_end_dates = new SpanHTMLElement($dates, array(), 'smaller');

			$row = array(
				new HTMLTableRowCell(new LinkHTMLElement(WikiUrlBuilder::display_item($category->get_id(), $category->get_rewrited_name(), $document->get_id(), $document->get_rewrited_title()), $document->get_title()), 'left'),
				new HTMLTableRowCell(new LinkHTMLElement(WikiUrlBuilder::display_category($category->get_id(), $category->get_rewrited_name()), $category->get_name())),
				new HTMLTableRowCell($author),
				new HTMLTableRowCell($document->get_date_created()->format(Date::FORMAT_DAY_MONTH_YEAR_HOUR_MINUTE)),
				new HTMLTableRowCell($document->get_status() . $br->display() . ($dates ? $start_and_end_dates->display() : '')),
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
		if (!WikiAuthorizationsService::check_authorizations()->moderation())
		{
			$error_controller = PHPBoostErrors::user_not_authorized();
			DispatchManager::redirect($error_controller);
		}
	}

	private function generate_response()
	{
		$response = new SiteDisplayResponse($this->view);

		$graphical_environment = $response->get_graphical_environment();
		$graphical_environment->set_page_title($this->lang['wiki_management'], $this->lang['module.title']);
		$graphical_environment->get_seo_meta_data()->set_canonical_url(WikiUrlBuilder::manage_items());

		$breadcrumb = $graphical_environment->get_breadcrumb();
		$breadcrumb->add($this->lang['module.title'], WikiUrlBuilder::home());

		$breadcrumb->add($this->lang['wiki_management'], WikiUrlBuilder::manage_items());

		return $response;
	}
}
?>
