<?php
/*##################################################
 *                      AgendaDeleteController.class.php
 *                            -------------------
 *   begin                : November 20, 2012
 *   copyright            : (C) 2012 Julien BRISWALTER
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
 * @author Julien BRISWALTER <julien.briswalter@phpboost.com>
 */
class AgendaDeleteController extends ModuleController
{
	/**
	 * @var HTMLForm
	 */
	private $form;
	/**
	 * @var FormButtonSubmit
	 */
	private $submit_button;
	
	private $lang;
	
	private $event;
	
	public function execute(HTTPRequestCustom $request)
	{
		AppContext::get_session()->csrf_get_protect();
		
		$this->init();
		
		$this->get_event($request);
		
		$this->check_authorizations();
		
		$tpl = new StringTemplate('# INCLUDE FORM #');
		$tpl->add_lang($this->lang);
		
		if ($this->event->belongs_to_a_serie())
			$this->build_form($request);
		
		if (($this->event->belongs_to_a_serie() && $this->submit_button->has_been_submited() && $this->form->validate()) || !$this->event->belongs_to_a_serie())
		{
			$this->delete_event($this->event->belongs_to_a_serie() ? $this->form->get_value('delete_serie')->get_raw_value() : false);
			$this->redirect($request);
		}
		
		$tpl->put('FORM', $this->form->display());
		
		return $this->generate_response($tpl);
	}
	
	private function init()
	{
		$this->lang = LangLoader::get('common', 'agenda');
	}
	
	private function build_form(HTTPRequestCustom $request)
	{
		$form = new HTMLForm(__CLASS__);
		
		$fieldset = new FormFieldsetHTML('delete_serie', $this->lang['agenda.titles.delete_event']);
		$form->add_fieldset($fieldset);
		
		$fieldset->add_field(new FormFieldRadioChoice('delete_serie', LangLoader::get_message('delete', 'common'), 0,
			array(
				new FormFieldRadioChoiceOption($this->lang['agenda.titles.delete_occurrence'], 0),
				new FormFieldRadioChoiceOption($this->lang['agenda.titles.delete_all_events_of_the_serie'], 1)
			)
		));
		
		$fieldset->add_field(new FormFieldHidden('referrer', $request->get_url_referrer()));
		
		$this->submit_button = new FormButtonDefaultSubmit();
		$form->add_button($this->submit_button);
		
		$this->form = $form;
	}
	
	private function get_event(HTTPRequestCustom $request)
	{
		$id = $request->get_getint('id', 0);
		
		if (!empty($id))
		{
			try {
				$this->event = AgendaService::get_event('WHERE id_event = :id', array('id' => $id));
			} catch (RowNotFoundException $e) {
				$error_controller = PHPBoostErrors::unexisting_page();
				DispatchManager::redirect($error_controller);
			}
		}
	}
	
	private function delete_event($delete_all_serie_events = false)
	{
		$events_list = AgendaService::get_serie_events($this->event->get_content()->get_id());
		
		if ($delete_all_serie_events)
		{
			foreach ($events_list as $event)
			{
				//Delete event comments
				CommentsService::delete_comments_topic_module('agenda', $event->get_id());
				
				//Delete participants
				AgendaService::delete_all_participants($event->get_id());
			}
			
			AgendaService::delete_all_serie_events($this->event->get_content()->get_id());
			PersistenceContext::get_querier()->delete(DB_TABLE_EVENTS, 'WHERE module = :module AND id_in_module = :id', array('module' => 'agenda', 'id' => !$this->event->get_parent_id() ? $this->event->get_id() : $this->event->get_parent_id()));
		}
		else
		{
			if (!$this->event->belongs_to_a_serie() || count($events_list) == 1)
			{
				AgendaService::delete_event_content('WHERE id = :id', array('id' => $this->event->get_id()));
			}
			
			//Delete event
			AgendaService::delete_event('WHERE id_event = :id', array('id' => $this->event->get_id()));
			
			if (!$this->event->get_parent_id())
				PersistenceContext::get_querier()->delete(DB_TABLE_EVENTS, 'WHERE module=:module AND id_in_module=:id', array('module' => 'agenda', 'id' => $this->event->get_id()));
			
			//Delete event comments
			CommentsService::delete_comments_topic_module('agenda', $this->event->get_id());
			
			//Delete participants
			AgendaService::delete_all_participants($this->event->get_id());
		}
		
		Feed::clear_cache('agenda');
		AgendaCurrentMonthEventsCache::invalidate();
	}
	
	private function check_authorizations()
	{
		if (!$this->event->is_authorized_to_delete())
		{
			$error_controller = PHPBoostErrors::user_not_authorized();
			DispatchManager::redirect($error_controller);
		}
		if (AppContext::get_current_user()->is_readonly())
		{
			$error_controller = PHPBoostErrors::user_in_read_only();
			DispatchManager::redirect($error_controller);
		}
	}
	
	private function redirect(HTTPRequestCustom $request)
	{
		if ($this->event->belongs_to_a_serie())
			AppContext::get_response()->redirect(($this->form->get_value('referrer') && !strstr($request->get_url_referrer(), AgendaUrlBuilder::display_event($this->event->get_content()->get_category()->get_id(), $this->event->get_content()->get_category()->get_rewrited_name(), $this->event->get_id(), $this->event->get_content()->get_rewrited_title())->rel()) ? $this->form->get_value('referrer') : AgendaUrlBuilder::home($this->event->get_start_date()->get_year(), $this->event->get_start_date()->get_month())), StringVars::replace_vars($this->lang['agenda.message.success.delete'], array('title' => $this->event->get_content()->get_title())));
		else
			AppContext::get_response()->redirect(($request->get_url_referrer() && !strstr($request->get_url_referrer(), AgendaUrlBuilder::display_event($this->event->get_content()->get_category()->get_id(), $this->event->get_content()->get_category()->get_rewrited_name(), $this->event->get_id(), $this->event->get_content()->get_rewrited_title())->rel()) ? $request->get_url_referrer() : AgendaUrlBuilder::home($this->event->get_start_date()->get_year(), $this->event->get_start_date()->get_month())), StringVars::replace_vars($this->lang['agenda.message.success.delete'], array('title' => $this->event->get_content()->get_title())));
	}
	
	private function generate_response(View $tpl)
	{
		$response = new SiteDisplayResponse($tpl);
		$graphical_environment = $response->get_graphical_environment();
		$graphical_environment->set_page_title($this->lang['agenda.titles.event_removal'], $this->lang['module_title']);
		
		$breadcrumb = $graphical_environment->get_breadcrumb();
		$breadcrumb->add($this->lang['module_title'], AgendaUrlBuilder::home());
		
		$event_content = $this->event->get_content();
		
		$category = $event_content->get_category();
		$breadcrumb->add($event_content->get_title(), AgendaUrlBuilder::display_event($category->get_id(), $category->get_rewrited_name(), $event_content->get_id(), $event_content->get_rewrited_title()));
		
		$breadcrumb->add($this->lang['agenda.titles.event_removal'], AgendaUrlBuilder::delete_event($this->event->get_id()));
		$graphical_environment->get_seo_meta_data()->set_canonical_url(AgendaUrlBuilder::delete_event($this->event->get_id()));
		
		return $response;
	}
}
?>
