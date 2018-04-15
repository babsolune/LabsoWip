<?php
/*##################################################
 *                              AgendaFormController.class.php
 *                            -------------------
 *   begin                : February 25, 2013
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
class AgendaFormController extends ModuleController
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
	private $is_new_event;

	public function execute(HTTPRequestCustom $request)
	{
		$this->init();
		$this->check_authorizations();
		$this->build_form($request);

		if ($this->submit_button->has_been_submited() && $this->form->validate())
		{
			$this->save();
			$this->redirect();
		}

		$this->tpl->put_all(array(
			'FORM' => $this->form->display()
		));

		return $this->build_response($this->tpl);
	}

	private function init()
	{
		$this->lang = LangLoader::get('common', 'agenda');
		$this->tpl = new FileTemplate('agenda/AgendaFormController.tpl');
		$this->tpl->add_lang($this->lang);
	}

	private function build_form(HTTPRequestCustom $request)
	{
		$common_lang = LangLoader::get('common');
		$date_lang = LangLoader::get('date-common');
		$event_content = $this->get_event()->get_content();

		$form = new HTMLForm(__CLASS__);

		$fieldset = new FormFieldsetHTML('event', $this->lang['agenda.titles.event']);
		$form->add_fieldset($fieldset);

		$fieldset->add_field(new FormFieldTextEditor('title', $common_lang['form.title'], $event_content->get_title(), array('required' => true)));

		if (AgendaService::get_categories_manager()->get_categories_cache()->has_categories())
		{
			$search_category_children_options = new SearchCategoryChildrensOptions();
			$search_category_children_options->add_authorizations_bits(Category::CONTRIBUTION_AUTHORIZATIONS);
			$search_category_children_options->add_authorizations_bits(Category::WRITE_AUTHORIZATIONS);
			$fieldset->add_field(AgendaService::get_categories_manager()->get_select_categories_form_field('category_id', LangLoader::get_message('category', 'categories-common'), $event_content->get_category_id(), $search_category_children_options));
		}

        // if (empty($event_content->get_location()))
        $fieldset->add_field(new AgendaFormFieldLocation('location', $this->lang['agenda.labels.place'], $event_content->get_location()));

		$fieldset->add_field(new FormFieldMultiLineTextEditor('location_more', $this->lang['agenda.labels.location.more'], $event_content->get_location_more(),
            array(
            )
        ));

		$fieldset->add_field(new FormFieldDateTime('start_date', $this->lang['agenda.labels.start_date'], $this->get_event()->get_start_date(), array('required' => true)));

		$fieldset->add_field(new FormFieldCheckbox('end_date_enabled', $this->lang['agenda.labels.end_date_enabled'], $this->get_event()->get_end_date(),
			array(
				'events' => array('click' => '
					if (HTMLForms.getField("end_date_enabled").getValue()) {
						HTMLForms.getField("end_date").enable();
					} else {
						HTMLForms.getField("end_date").disable();
					}'
				)
			)
		));

		$fieldset->add_field(new FormFieldDateTime('end_date', $this->lang['agenda.labels.end_date'], $this->get_event()->get_end_date(), array('hidden' => !$this->get_event()->get_end_date())));

		$fieldset->add_field(new FormFieldRichTextEditor('contents', $this->lang['agenda.labels.contents'], $event_content->get_contents(), array('rows' => 15, 'required' => true)));

		$fieldset->add_field(new FormFieldUploadFile('picture', $this->lang['agenda.labels.picture'],  $event_content->get_picture()->relative(), array ( 'description' => $this->lang['agenda.labels.picture.explain'])));

		$fieldset->add_field(new AgendaFormFieldContact('contact_informations', $this->lang['agenda.labels.contact_informations'], $event_content->get_contact_informations(), array('description' => $this->lang['agenda.contact.desc'])));

		$fieldset->add_field(new AgendaFormFieldPath('path_informations', $this->lang['agenda.labels.path_informations'], $event_content->get_path_informations(), array('description' => $this->lang['agenda.path.desc'])));

		$fieldset->add_field(new FormFieldCheckbox('registration_authorized', $this->lang['agenda.labels.registration_authorized'], $event_content->is_registration_authorized()
            ,array(
    			 'events' => array('click' => '
    			 if (HTMLForms.getField("registration_authorized").getValue()) {
    				 HTMLForms.getField("max_registered_members").enable();
    				 $("' . __CLASS__ . '_register_authorizations").show();
    			 } else {
    				 HTMLForms.getField("max_registered_members").disable();
    				 $("' . __CLASS__ . '_register_authorizations").hide();
    			 }')
             )
        ));

		 $fieldset->add_field(new FormFieldTextEditor('max_registered_members', $this->lang['agenda.labels.max_registered_members'], $event_content->get_max_registered_members(), array(
			 'description' => $this->lang['agenda.labels.max_registered_members.explain'], 'maxlength' => 5, 'size' => 5, 'hidden' => !$event_content->is_registration_authorized()),
			 array(new FormFieldConstraintRegex('`^[0-9]+$`i'))
		 ));

		//  $fieldset->add_field(new FormFieldCheckbox('last_registration_date_enabled', $this->lang['agenda.labels.last_registration_date_enabled'], $event_content->is_last_registration_date_enabled(),array(
		// 	 'hidden' => !$event_content->is_registration_authorized(), 'events' => array('click' => '
		// 	 if (HTMLForms.getField("last_registration_date_enabled").getValue()) {
		// 		 HTMLForms.getField("last_registration_date").enable();
		// 	 } else {
		// 		 HTMLForms.getField("last_registration_date").disable();
		// 	 }'
		//  ))));

		//  $fieldset->add_field(new FormFieldDateTime('last_registration_date', $this->lang['agenda.labels.last_registration_date'], $event_content->get_last_registration_date(), array(
		// 	 'hidden' => !$event_content->is_last_registration_date_enabled())
		//  ));

		// $auth_settings = new AuthorizationsSettings(array(
			// new ActionAuthorization($this->lang['agenda.authorizations.display_registered_users'], AgendaEventContent::DISPLAY_REGISTERED_USERS_AUTHORIZATION),
			// new ActionAuthorization($this->lang['agenda.authorizations.register'], AgendaEventContent::REGISTER_AUTHORIZATION)
		// ));
		// $auth_settings->build_from_auth_array($event_content->get_register_authorizations());
		// $auth_setter = new FormFieldAuthorizationsSetter('register_authorizations', $auth_settings, array('hidden' => !$event_content->is_registration_authorized()));
		// $fieldset->add_field($auth_setter);

		$this->build_approval_field($fieldset);
		$this->build_contribution_fieldset($form);

		$fieldset->add_field(new FormFieldHidden('referrer', $request->get_url_referrer()));

		$this->submit_button = new FormButtonDefaultSubmit();
		$form->add_button($this->submit_button);
		$form->add_button(new FormButtonReset());

		$this->form = $form;
	}

	private function build_approval_field($fieldset)
	{
		if (!$this->is_contributor_member() || $this->get_event()->is_authorized_to_edit())
		{
			$common_lang = LangLoader::get('common');
			if ($this->get_event()->get_id() !== null)
				$fieldset->add_field(new FormFieldCheckbox('cancelled', $this->lang['agenda.labels.cancelled'], $this->get_event()->get_content()->is_cancelled()));

			if (AgendaAuthorizationsService::check_authorizations()->moderation())
				$fieldset->add_field(new FormFieldCheckbox('approved', $common_lang['form.approve'], $this->get_event()->get_content()->is_approved()));
		}
	}

	private function build_contribution_fieldset($form)
	{
		if ($this->get_event()->get_id() === null && $this->is_contributor_member())
		{
			$fieldset = new FormFieldsetHTML('contribution', LangLoader::get_message('contribution', 'user-common'));
			$fieldset->set_description(MessageHelper::display($this->lang['agenda.labels.contribution.explain'] . ' ' . LangLoader::get_message('contribution.explain', 'user-common') . ' ' . $this->lang['agenda.labels.contribution.modification'], MessageHelper::WARNING)->render());
			$form->add_fieldset($fieldset);

			$fieldset->add_field(new FormFieldRichTextEditor('contribution_description', LangLoader::get_message('contribution.description', 'user-common'), '', array('description' => LangLoader::get_message('contribution.description.explain', 'user-common'))));
		}
	}

	private function is_contributor_member()
	{
		return (!AgendaAuthorizationsService::check_authorizations()->write() && AgendaAuthorizationsService::check_authorizations()->contribution());
	}

	private function get_event()
	{
		if ($this->event === null)
		{
			$request = AppContext::get_request();
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
			else
			{
				$this->is_new_event = true;
				$this->event = new AgendaEvent();
				$this->event->init_default_properties($request->get_getint('year', date('Y')), $request->get_getint('month', date('n')), $request->get_getint('day', date('j')));

				$event_content = new AgendaEventContent();
				$event_content->init_default_properties($request->get_getint('id_category', Category::ROOT_CATEGORY));

				$this->event->set_content($event_content);
			}
		}
		return $this->event;
	}

	private function check_authorizations()
	{
		$event = $this->get_event();

		if ($event->get_id() === null)
		{
			if (!$event->is_authorized_to_add())
			{
				$error_controller = PHPBoostErrors::user_not_authorized();
				DispatchManager::redirect($error_controller);
			}
		}
		else
		{
			if (!$event->is_authorized_to_edit())
			{
				$error_controller = PHPBoostErrors::user_not_authorized();
				DispatchManager::redirect($error_controller);
			}
		}
		if (AppContext::get_current_user()->is_readonly())
		{
			$error_controller = PHPBoostErrors::user_in_read_only();
			DispatchManager::redirect($error_controller);
		}
	}

	private function save()
	{
		$event = $this->get_event();
		$event_content = $event->get_content();

		$event_content->set_title($this->form->get_value('title'));
		$event_content->set_rewrited_title(Url::encode_rewrite($this->form->get_value('title')));
		if (AgendaService::get_categories_manager()->get_categories_cache()->has_categories())
			$event_content->set_category_id($this->form->get_value('category_id')->get_raw_value());

		$event_content->set_contents($this->form->get_value('contents'));
		$event_content->set_location($this->form->get_value('location'));
        $event_content->set_location_more($this->form->get_value('location_more'));
		$event_content->set_picture(new Url($this->form->get_value('picture')));
		$event_content->set_contact_informations($this->form->get_value('contact_informations'));
		$event_content->set_path_informations($this->form->get_value('path_informations'));

		if (!$this->is_contributor_member() || $this->get_event()->is_authorized_to_edit())
		{
			if ($event->get_id() !== null && $this->form->get_value('cancelled'))
				$event_content->cancel();
			if ($event->get_id() !== null && !$this->form->get_value('cancelled'))
				$event_content->uncancel();

			if (AgendaAuthorizationsService::check_authorizations()->moderation() && $this->form->get_value('approved'))
				$event_content->approve();
			if (AgendaAuthorizationsService::check_authorizations()->moderation() && !$this->form->get_value('approved'))
				$event_content->unapprove();
		}

		 if ($this->form->get_value('registration_authorized'))
		 {
			 $event_content->authorize_registration();
			 $event_content->set_max_registered_members($this->form->get_value('max_registered_members'));

			 if ($this->form->get_value('last_registration_date_enabled'))
			 {
				 $event_content->enable_last_registration_date();
				 $event_content->set_last_registration_date($this->form->get_value('last_registration_date'));
			 }
			 else
			 {
				 $event_content->disable_last_registration_date();
				 $event_content->set_last_registration_date(null);
			 }

			 $event_content->set_register_authorizations($this->form->get_value('register_authorizations', $event_content->get_register_authorizations())/*->build_auth_array()*/);
		 }
		 else
			 $event_content->unauthorize_registration();

		$event->set_start_date($this->form->get_value('start_date'));

		if ($this->form->get_value('end_date_enabled'))
		{
			$event->set_end_date($this->form->get_value('end_date'));
		}
		else
			$event->unset_end_date();

		if ($event->get_id() === null)
		{
			$id_content = AgendaService::add_event_content($event_content);
			$event_content->set_id($id_content);

			$event->set_content($event_content);

			$id_event = AgendaService::add_event($event);
		}
		else
		{
			AgendaService::update_event_content($event_content);
			$id_event = AgendaService::update_event($event);
		}

		$this->contribution_actions($event, $id_event);

		Feed::clear_cache('agenda');
		AgendaCurrentMonthEventsCache::invalidate();
	}

	private function set_event_start_and_end_date(AgendaEvent $event, $new_start_date, $new_end_date)
	{
		switch ($event->get_content()->get_repeat_type())
		{
			case AgendaEventContent::DAILY:
				$new_start_date->add_days(1);
				$new_end_date->add_days(1);
				$event->set_start_date($new_start_date);
				$event->set_end_date($new_end_date);
				break;
			case AgendaEventContent::WEEKLY:
				$new_start_date->add_weeks(1);
				$new_end_date->add_weeks(1);
				$event->set_start_date($new_start_date);
				$event->set_end_date($new_end_date);
				break;
			case AgendaEventContent::MONTHLY:
				$new_start_month = $new_start_date->get_month() + 1;
				if ($new_start_month > 12)
				{
					$new_start_date->set_month(1);
					$new_start_date->set_year($new_start_date->get_year() + 1);
				}
				else
					$new_start_date->set_month($new_start_month);
				$new_end_month = $new_end_date->get_month() + 1;
				if ($new_end_month > 12)
				{
					$new_end_date->set_month(1);
					$new_end_date->set_year($new_end_date->get_year() + 1);
				}
				else
					$new_end_date->set_month($new_end_month);
				$event->set_start_date($new_start_date);
				$event->set_end_date($new_end_date);
				break;
			case AgendaEventContent::YEARLY:
				$new_start_date->set_year($new_start_date->get_year() + 1);
				$new_end_date->set_year($new_end_date->get_year() + 1);
				$event->set_start_date($new_start_date);
				$event->set_end_date($new_end_date);
				break;
			default :
				$event->set_start_date($new_start_date);
				$event->set_end_date($new_end_date);
				break;
		}

		return $event;
	}

	private function contribution_actions(AgendaEvent $event, $id_event)
	{
		if ($event->get_id() === null)
		{
			if ($this->is_contributor_member())
			{
				$contribution = new Contribution();
				$contribution->set_id_in_module($id_event);
				$contribution->set_description(stripslashes($this->form->get_value('contribution_description')));
				$contribution->set_entitled($event->get_content()->get_title());
				$contribution->set_fixing_url(AgendaUrlBuilder::edit_event($id_event)->relative());
				$contribution->set_poster_id(AppContext::get_current_user()->get_id());
				$contribution->set_module('agenda');
				$contribution->set_auth(
					Authorizations::capture_and_shift_bit_auth(
						AgendaService::get_categories_manager()->get_heritated_authorizations($event->get_content()->get_category_id(), Category::MODERATION_AUTHORIZATIONS, Authorizations::AUTH_CHILD_PRIORITY),
						Category::MODERATION_AUTHORIZATIONS, Contribution::CONTRIBUTION_AUTH_BIT
					)
				);
				ContributionService::save_contribution($contribution);
			}
		}
		else
		{
			$corresponding_contributions = ContributionService::find_by_criteria('agenda', $id_event);
			if (count($corresponding_contributions) > 0)
			{
				$event_contribution = $corresponding_contributions[0];
				$event_contribution->set_status(Event::EVENT_STATUS_PROCESSED);

				ContributionService::save_contribution($event_contribution);
			}
		}
		$event->set_id($id_event);
	}

	private function redirect()
	{
		$event = $this->get_event();
		$category = $event->get_content()->get_category();

		if ($this->is_new_event && $this->is_contributor_member() && !$event->get_content()->is_approved())
		{
			DispatchManager::redirect(new UserContributionSuccessController());
		}
		elseif ($event->get_content()->is_approved())
		{
			if ($this->is_new_event)
				AppContext::get_response()->redirect(AgendaUrlBuilder::home($event->get_start_date()->get_year(), $event->get_start_date()->get_month(), $event->get_start_date()->get_day() , true), StringVars::replace_vars($this->lang['agenda.message.success.add'], array('title' => $event->get_content()->get_title())));
			else
				AppContext::get_response()->redirect(($this->form->get_value('referrer') ? $this->form->get_value('referrer') : AgendaUrlBuilder::home($event->get_start_date()->get_year(), $event->get_start_date()->get_month(), $event->get_start_date()->get_day() , true)), StringVars::replace_vars($this->lang['agenda.message.success.edit'], array('title' => $event->get_content()->get_title())));
		}
		elseif (!$event->get_content()->is_approved())
		{
			if ($this->is_new_event)
				AppContext::get_response()->redirect(AgendaUrlBuilder::display_pending_events(), StringVars::replace_vars($this->lang['agenda.message.success.add'], array('title' => $event->get_content()->get_title())));
			else
				AppContext::get_response()->redirect(($this->form->get_value('referrer') ? $this->form->get_value('referrer') : AgendaUrlBuilder::display_pending_events()), StringVars::replace_vars($this->lang['agenda.message.success.edit'], array('title' => $event->get_content()->get_title())));
		}
		else
		{
			AppContext::get_response()->redirect(AgendaUrlBuilder::home());
		}
	}

	private function build_response(View $tpl)
	{
		$event = $this->get_event();

		$response = new SiteDisplayResponse($tpl);
		$graphical_environment = $response->get_graphical_environment();

		$breadcrumb = $graphical_environment->get_breadcrumb();
		$breadcrumb->add($this->lang['module_title'], AgendaUrlBuilder::home());

		if ($event->get_id() === null)
		{
			$graphical_environment->set_page_title($this->lang['agenda.titles.add_event'], $this->lang['module_title']);
			$breadcrumb->add($this->lang['agenda.titles.add_event'], AgendaUrlBuilder::add_event());
			$graphical_environment->get_seo_meta_data()->set_canonical_url(AgendaUrlBuilder::add_event());
		}
		else
		{
			$graphical_environment->set_page_title($this->lang['agenda.titles.event_edition'], $this->lang['module_title']);

			$category = $event->get_content()->get_category();
			$breadcrumb->add($event->get_content()->get_title(), AgendaUrlBuilder::display_event($category->get_id(), $category->get_rewrited_name(), $event->get_id(), $event->get_content()->get_rewrited_title()));

			$breadcrumb->add($this->lang['agenda.titles.event_edition'], AgendaUrlBuilder::edit_event($event->get_id()));
			$graphical_environment->get_seo_meta_data()->set_canonical_url(AgendaUrlBuilder::edit_event($event->get_id()));
		}

		return $response;
	}
}
?>
