<?php
/*##################################################
 *		    AgendaEventsListController.class.php
 *                            -------------------
 *   begin                : April 13, 2015
 *   copyright            : (C) 2015 Julien BRISWALTER
 *   email                : j1.seth@phpboost.com
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
 * @author Julien BRISWALTER <j1.seth@phpboost.com>
 */
class AgendaEventsListController extends ModuleController
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
		$this->lang = LangLoader::get('common', 'agenda');
		$this->view = new StringTemplate('# INCLUDE table #');
	}

	private function build_table()
	{
		if (AgendaAuthorizationsService::check_authorizations()->moderation()){
			$table_model = new SQLHTMLTableModel(AgendaSetup::$agenda_events_table, 'table', array(
				new HTMLTableColumn(LangLoader::get_message('form.title', 'common'), 'title'),
				new HTMLTableColumn(LangLoader::get_message('author', 'common'), 'display_name'),
				new HTMLTableColumn(LangLoader::get_message('date', 'date-common'), 'start_date'),
				new HTMLTableColumn('')
			), new HTMLTableSortingRule('start_date', HTMLTableSortingRule::ASC));
		}
		else {
			$table_model = new SQLHTMLTableModel(AgendaSetup::$agenda_events_table, 'table', array(
				new HTMLTableColumn(LangLoader::get_message('form.title', 'common'), 'title'),
				new HTMLTableColumn(LangLoader::get_message('author', 'common'), 'display_name'),
				new HTMLTableColumn(LangLoader::get_message('date', 'date-common'), 'start_date'),
			), new HTMLTableSortingRule('start_date', HTMLTableSortingRule::ASC));
		}

		$table_model->set_caption($this->lang['agenda.events_list']);
		$table_model->add_permanent_filter('parent_id = 0');

		$table_model->add_filter(new HTMLTableDateTimeGreaterThanOrEqualsToSQLFilter('start_date', 'filter1', $this->lang['agenda.labels.start_date'] . ' ' . TextHelper::lcfirst(LangLoader::get_message('minimum', 'common'))));
		$table_model->add_filter(new HTMLTableDateTimeLessThanOrEqualsToSQLFilter('start_date', 'filter2', $this->lang['agenda.labels.start_date'] . ' ' . TextHelper::lcfirst(LangLoader::get_message('maximum', 'common'))));

		$table = new HTMLTable($table_model);
		$table->set_filters_fieldset_class_HTML();

		$results = array();
		$result = $table_model->get_sql_results('event
			LEFT JOIN ' . AgendaSetup::$agenda_events_content_table . ' event_content ON event_content.id = event.content_id
			LEFT JOIN ' . DB_TABLE_MEMBER . ' member ON member.user_id = event_content.author_id'
		);

		foreach ($result as $row)
		{
			$event = new AgendaEvent();
			$event->set_properties($row);
			$category = $event->get_content()->get_category();
			$user = $event->get_content()->get_author_user();

			$edit_link = new LinkHTMLElement(AgendaUrlBuilder::edit_event(!$event->get_parent_id() ? $event->get_id() : $event->get_parent_id()), '', array('title' => LangLoader::get_message('edit', 'common')), 'fa fa-edit');
			$delete_link = new LinkHTMLElement(AgendaUrlBuilder::delete_event($event->get_id()), '', array('title' => LangLoader::get_message('delete', 'common'), 'data-confirmation' => !$event->belongs_to_a_serie() ? 'delete-element' : ''), 'fa fa-delete');

			$user_group_color = User::get_group_color($user->get_groups(), $user->get_level(), true);
			$author = $user->get_id() !== User::VISITOR_LEVEL ? new LinkHTMLElement(UserUrlBuilder::profile($user->get_id()), $user->get_display_name(), (!empty($user_group_color) ? array('style' => 'color: ' . $user_group_color) : array()), UserService::get_level_class($user->get_level())) : $user->get_display_name();

			$br = new BrHTMLElement();

			if (AgendaAuthorizationsService::check_authorizations()->moderation()){
				$results[] = new HTMLTableRow(array(
					new HTMLTableRowCell(new LinkHTMLElement(AgendaUrlBuilder::display_event($category->get_id(), $category->get_rewrited_name(), $event->get_id(), $event->get_content()->get_rewrited_title()), $event->get_content()->get_title()), 'left'),
					new HTMLTableRowCell($author),
					new HTMLTableRowCell($event->get_start_date()->format(Date::FORMAT_DAY_MONTH_YEAR)),
					new HTMLTableRowCell($edit_link->display() . $delete_link->display())
				));
			}
			else{
				$results[] = new HTMLTableRow(array(
					new HTMLTableRowCell(new LinkHTMLElement(AgendaUrlBuilder::display_event($category->get_id(), $category->get_rewrited_name(), $event->get_id(), $event->get_content()->get_rewrited_title()), $event->get_content()->get_title()), 'left'),
					new HTMLTableRowCell($author),
					new HTMLTableRowCell($event->get_start_date()->format(Date::FORMAT_DAY_MONTH_YEAR)),
				));
			}
		}
		$table->set_rows($table_model->get_number_of_matching_rows(), $results);

		$this->view->put('table', $table->display());
	}

	private function check_authorizations()
	{
		if (!AgendaAuthorizationsService::check_authorizations()->read())
		{
			$error_controller = PHPBoostErrors::user_not_authorized();
			DispatchManager::redirect($error_controller);
		}
	}

	private function get_repeat_type_label(AgendaEvent $event)
	{
		$label = AgendaEventContent::NEVER;

		switch ($event->get_content()->get_repeat_type())
		{
			case AgendaEventContent::DAILY :
				$label = LangLoader::get_message('every_day', 'date-common');
				break;

			case AgendaEventContent::WEEKLY :
				$label = LangLoader::get_message('every_week', 'date-common');
				break;

			case AgendaEventContent::MONTHLY :
				$label = LangLoader::get_message('every_month', 'date-common');
				break;

			case AgendaEventContent::YEARLY :
				$label = LangLoader::get_message('every_year', 'date-common');
				break;
		}

		return $label;
	}

	private function generate_response()
	{
		$response = new SiteDisplayResponse($this->view);
		$graphical_environment = $response->get_graphical_environment();
		$graphical_environment->set_page_title($this->lang['agenda.events_list'], $this->lang['module_title']);

		$breadcrumb = $graphical_environment->get_breadcrumb();
		$breadcrumb->add($this->lang['module_title'], AgendaUrlBuilder::home());
		$breadcrumb->add($this->lang['agenda.events_list'], AgendaUrlBuilder::events_list());

		$graphical_environment->get_seo_meta_data()->set_canonical_url(AgendaUrlBuilder::events_list());

		return $response;
	}
}
?>
