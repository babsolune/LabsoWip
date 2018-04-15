<?php
/*##################################################
 *		               AgendaDisplayEventController.class.php
 *                            -------------------
 *   begin                : July 29, 2013
 *   copyright            : (C) 2013 Julien BRISWALTER
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
class AgendaDisplayEventController extends ModuleController
{
	private $lang;
	private $tpl;

	private $event;

	public function execute(HTTPRequestCustom $request)
	{
		$this->check_authorizations();

		$this->init();

		$this->build_view();

		return $this->generate_response();
	}

	private function init()
	{
		$this->lang = LangLoader::get('common', 'agenda');
		$this->tpl = new FileTemplate('agenda/AgendaDisplayEventController.tpl');
		$this->tpl->add_lang($this->lang);
	}

	private function get_event()
	{
		if ($this->event === null)
		{
			$id = AppContext::get_request()->get_getint('id', 0);
			if (!empty($id))
			{
				try {
					$this->event = AgendaService::get_event('WHERE id_event = :id', array('id' => $id));
				} catch (RowNotFoundException $e) {
					$error_controller = PHPBoostErrors::unexisting_page();
   					DispatchManager::redirect($error_controller);
				}
			}
			else
				$this->event = new AgendaEvent();
		}
		return $this->event;
	}

	private function build_view()
	{
		$this->build_location_view();
		$this->build_contact_view();
		$this->build_path_view();

		$event = $this->get_event();
		$category = $event->get_content()->get_category();

		$this->tpl->put_all(array_merge($event->get_array_tpl_vars(), array(
			'NOT_VISIBLE_MESSAGE' => MessageHelper::display(LangLoader::get_message('element.not_visible', 'status-messages-common'), MessageHelper::WARNING)
		)));

		$participants_number = count($event->get_participants());
		$i = 0;
		foreach ($event->get_participants() as $participant)
		{
			$i++;
			$this->tpl->assign_block_vars('participant', array_merge($participant->get_array_tpl_vars(), array(
				'C_LAST_PARTICIPANT' => $i == $participants_number
			)));
		}

		if (AgendaConfig::load()->are_comments_enabled())
		{
			$comments_topic = new AgendaCommentsTopic($event);
			$comments_topic->set_id_in_module($event->get_id());
			$comments_topic->set_url(AgendaUrlBuilder::display_event($category->get_id(), $category->get_rewrited_name(), $event->get_id(), $event->get_content()->get_rewrited_title()));

			$this->tpl->put_all(array(
				'C_COMMENTS_ENABLED' => true,
				'COMMENTS' => $comments_topic->display()
			));
		}
	}

	private function build_location_view()
	{
		$event = $this->get_event();
		$location = $event->get_content()->get_location();

		foreach ($location as $id => $options)
		{
			$this->tpl->assign_block_vars('location', array(
				'C_LOCATION' => !empty($location),
				'CITY' => $options['city'],
				'POSTAL_CODE' => substr($options['postal_code'], 0, -3),
				'DEPARTMENT' => $options['department'],
			));
		}
	}

	private function build_contact_view()
	{
		$event = $this->get_event();
		$contact = $event->get_content()->get_contact_informations();
		foreach ($contact as $id => $options)
		{
			$this->tpl->assign_block_vars('contact', array(
				'C_NAME' => !empty($options['contact_name']),
				'C_TEL1' => !empty($options['contact_phone1']),
				'C_TEL2' => !empty($options['contact_phone2']),
				'C_WEBSITE' => !empty($options['contact_site']),
				'C_MAIL' => !empty($options['contact_mail']),
				'CONTACT_NAME' => $options['contact_name'],
				'CONTACT_MAIL' => $options['contact_mail'],
				'CONTACT_PHONE1' => $options['contact_phone1'],
				'CONTACT_PHONE2' => $options['contact_phone2'],
				'CONTACT_SITE' => $options['contact_site'],
			));
		}
	}

	private function build_path_view()
	{
		$event = $this->get_event();
		$path = $event->get_content()->get_path_informations();
		foreach ($path as $id => $options)
		{
			$this->tpl->assign_block_vars('path', array(
				'C_PATH_LENGTH' => !empty($options['path_length']),
				'C_PATH_ELEVATION' => !empty($options['path_elevation']),
				'C_PATH_LEVEL' => !empty($options['path_level']),
				'PATH_TYPE' => $options['path_type'],
				'PATH_LENGTH' => $options['path_length'],
				'PATH_ELEVATION' => $options['path_elevation'],
				'PATH_LEVEL' => $options['path_level'],
			));
		}
	}

	private function check_authorizations()
	{
		$event = $this->get_event();
		if (!AgendaAuthorizationsService::check_authorizations($event->get_content()->get_category_id())->read() && (!(AgendaAuthorizationsService::check_authorizations($event->get_content()->get_category_id())->write() || (AgendaAuthorizationsService::check_authorizations($event->get_content()->get_category_id())->contribution() && !$event->get_content()->is_approved())) && $event->get_content()->get_author_user()->get_id() != AppContext::get_current_user()->get_id()))
		{
			$error_controller = PHPBoostErrors::user_not_authorized();
			DispatchManager::redirect($error_controller);
		}
	}

	private function generate_response()
	{
		$event = $this->get_event();

		$response = new SiteDisplayResponse($this->tpl);
		$graphical_environment = $response->get_graphical_environment();
		$graphical_environment->set_page_title($event->get_content()->get_title(), $this->lang['module_title']);

		$breadcrumb = $graphical_environment->get_breadcrumb();
		$breadcrumb->add($this->lang['module_title'], AgendaUrlBuilder::home());

		$category = $event->get_content()->get_category();
		$breadcrumb->add($event->get_content()->get_title(), AgendaUrlBuilder::display_event($category->get_id(), $category->get_rewrited_name(), $event->get_id(), $event->get_content()->get_rewrited_title()));
		$graphical_environment->get_seo_meta_data()->set_canonical_url(AgendaUrlBuilder::display_event($category->get_id(), $category->get_rewrited_name(), $event->get_id(), $event->get_content()->get_rewrited_title()));

		return $response;
	}
}
?>
