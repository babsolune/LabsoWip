<?php
/*##################################################
 *		                         PalmaresFormController.class.php
 *                            -------------------
 *   begin                : February 13, 2013
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

class PalmaresFormController extends ModuleController
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
	private $common_lang;
	
	private $palmares;
	private $is_new_palmares;
	
	public function execute(HTTPRequestCustom $request)
	{
		$this->init();
		
		$this->check_authorizations();
		
		$this->build_form($request);
		
		$tpl = new StringTemplate('# INCLUDE FORM #');
		$tpl->add_lang($this->lang);
		
		if ($this->submit_button->has_been_submited() && $this->form->validate())
		{
			$this->save();
			$this->redirect();
		}
		
		$tpl->put('FORM', $this->form->display());
		
		return $this->generate_response($tpl);
	}
	
	private function init()
	{
		$this->lang = LangLoader::get('common', 'palmares');
		$this->common_lang = LangLoader::get('common');
		$this->config = PalmaresConfig::load();
	}
	
	private function build_form(HTTPRequestCustom $request)
	{
		$form = new HTMLForm(__CLASS__);
		
		$fieldset = new FormFieldsetHTML('palmares', $this->lang['palmares']);
		$form->add_fieldset($fieldset);
		
		$fieldset->add_field(new FormFieldTextEditor('name', $this->common_lang['form.name'], $this->get_palmares()->get_name(), array('required' => true)));

		if (PalmaresAuthorizationsService::check_authorizations($this->get_palmares()->get_id_cat())->moderation())
		{
			$fieldset->add_field(new FormFieldCheckbox('personalize_rewrited_name', $this->common_lang['form.rewrited_name.personalize'], $this->get_palmares()->rewrited_name_is_personalized(), array(
			'events' => array('click' => '
			if (HTMLForms.getField("personalize_rewrited_name").getValue()) {
				HTMLForms.getField("rewrited_name").enable();
			} else { 
				HTMLForms.getField("rewrited_name").disable();
			}'
			))));
			
			$fieldset->add_field(new FormFieldTextEditor('rewrited_name', $this->common_lang['form.rewrited_name'], $this->get_palmares()->get_rewrited_name(), array(
				'description' => $this->common_lang['form.rewrited_name.description'], 
				'hidden' => !$this->get_palmares()->rewrited_name_is_personalized()
			), array(new FormFieldConstraintRegex('`^[a-z0-9\-]+$`iu'))));
		}
		
		if (PalmaresService::get_categories_manager()->get_categories_cache()->has_categories())
		{
			$search_category_children_options = new SearchCategoryChildrensOptions();
			$search_category_children_options->add_authorizations_bits(Category::CONTRIBUTION_AUTHORIZATIONS);
			$search_category_children_options->add_authorizations_bits(Category::WRITE_AUTHORIZATIONS);
			$fieldset->add_field(PalmaresService::get_categories_manager()->get_select_categories_form_field('id_cat', $this->common_lang['form.category'], $this->get_palmares()->get_id_cat(), $search_category_children_options));
		}
		
		$fieldset->add_field(new FormFieldRichTextEditor('contents', $this->common_lang['form.contents'], $this->get_palmares()->get_contents(), array('rows' => 15, 'required' => true)));
		
		$fieldset->add_field(new FormFieldCheckbox('enable_short_contents', $this->lang['palmares.form.short_contents.enabled'], $this->get_palmares()->get_short_contents_enabled(), 
			array('description' => StringVars::replace_vars($this->lang['palmares.form.short_contents.enabled.description'], array('number' => PalmaresConfig::load()->get_number_character_to_cut())), 'events' => array('click' => '
			if (HTMLForms.getField("enable_short_contents").getValue()) {
				HTMLForms.getField("short_contents").enable();
			} else { 
				HTMLForms.getField("short_contents").disable();
			}'))
		));
		
		$fieldset->add_field(new FormFieldRichTextEditor('short_contents', $this->lang['palmares.form.short_contents'], $this->get_palmares()->get_short_contents(), array(
			'hidden' => !$this->get_palmares()->get_short_contents_enabled(),
			'description' => !PalmaresConfig::load()->get_display_condensed_enabled() ? '<span class="color-alert">' . $this->lang['palmares.form.short_contents.description'] . '</span>' : ''
		)));
		
		if ($this->config->get_author_displayed() == true)
		{
			$fieldset->add_field(new FormFieldCheckbox('author_custom_name_enabled', $this->lang['palmares.form.author_custom_name_enabled'], $this->get_palmares()->is_author_custom_name_enabled(), 
				array('events' => array('click' => '
				if (HTMLForms.getField("author_custom_name_enabled").getValue()) {
					HTMLForms.getField("author_custom_name").enable();
				} else { 
					HTMLForms.getField("author_custom_name").disable();
				}'))
			));
			
			$fieldset->add_field(new FormFieldTextEditor('author_custom_name', $this->lang['palmares.form.author_custom_name'], $this->get_palmares()->get_author_custom_name(), array(
				'hidden' => !$this->get_palmares()->is_author_custom_name_enabled(),
			)));
		}

		$other_fieldset = new FormFieldsetHTML('other', $this->common_lang['form.other']);
		$form->add_fieldset($other_fieldset);

		$other_fieldset->add_field(new FormFieldUploadFile('picture', $this->common_lang['form.picture'], $this->get_palmares()->get_picture()->relative()));

		$other_fieldset->add_field(PalmaresService::get_keywords_manager()->get_form_field($this->get_palmares()->get_id(), 'keywords', $this->common_lang['form.keywords'], array('description' => $this->common_lang['form.keywords.description'])));
		
		$other_fieldset->add_field(new PalmaresFormFieldSelectSources('sources', $this->common_lang['form.sources'], $this->get_palmares()->get_sources()));
		
		if (PalmaresAuthorizationsService::check_authorizations($this->get_palmares()->get_id_cat())->moderation())
		{
			$publication_fieldset = new FormFieldsetHTML('publication', $this->common_lang['form.approbation']);
			$form->add_fieldset($publication_fieldset);

			$publication_fieldset->add_field(new FormFieldDateTime('creation_date', $this->common_lang['form.date.creation'], $this->get_palmares()->get_creation_date(),
				array('required' => true)
			));

			if (!$this->is_new_palmares)
			{
				$publication_fieldset->add_field(new FormFieldCheckbox('update_creation_date', $this->common_lang['form.update.date.creation'], false, array('hidden' => $this->get_palmares()->get_approbation_type() != Palmares::NOT_APPROVAL)
				));
			}

			$publication_fieldset->add_field(new FormFieldSimpleSelectChoice('approbation_type', $this->common_lang['form.approbation'], $this->get_palmares()->get_approbation_type(),
				array(
					new FormFieldSelectChoiceOption($this->common_lang['form.approbation.not'], Palmares::NOT_APPROVAL),
					new FormFieldSelectChoiceOption($this->common_lang['form.approbation.now'], Palmares::APPROVAL_NOW),
					new FormFieldSelectChoiceOption($this->common_lang['status.approved.date'], Palmares::APPROVAL_DATE),
				),
				array('events' => array('change' => '
				if (HTMLForms.getField("approbation_type").getValue() == 2) {
					jQuery("#' . __CLASS__ . '_start_date_field").show();
					HTMLForms.getField("end_date_enable").enable();
				} else { 
					jQuery("#' . __CLASS__ . '_start_date_field").hide();
					HTMLForms.getField("end_date_enable").disable();
				}'))
			));
			
			$publication_fieldset->add_field(new FormFieldDateTime('start_date', $this->common_lang['form.date.start'], ($this->get_palmares()->get_start_date() === null ? new Date() : $this->get_palmares()->get_start_date()), array('hidden' => ($this->get_palmares()->get_approbation_type() != Palmares::APPROVAL_DATE))));
			
			$publication_fieldset->add_field(new FormFieldCheckbox('end_date_enable', $this->common_lang['form.date.end.enable'], $this->get_palmares()->end_date_enabled(), array(
			'hidden' => ($this->get_palmares()->get_approbation_type() != Palmares::APPROVAL_DATE),
			'events' => array('click' => '
			if (HTMLForms.getField("end_date_enable").getValue()) {
				HTMLForms.getField("end_date").enable();
			} else { 
				HTMLForms.getField("end_date").disable();
			}'
			))));
			
			$publication_fieldset->add_field(new FormFieldDateTime('end_date', $this->common_lang['form.date.end'], ($this->get_palmares()->get_end_date() === null ? new Date() : $this->get_palmares()->get_end_date()), array('hidden' => !$this->get_palmares()->end_date_enabled())));
		
			$publication_fieldset->add_field(new FormFieldCheckbox('top_list', $this->lang['palmares.form.top_list'], $this->get_palmares()->top_list_enabled()));
		}
		
		$this->build_contribution_fieldset($form);
		
		$fieldset->add_field(new FormFieldHidden('referrer', $request->get_url_referrer()));
		
		$this->submit_button = new FormButtonDefaultSubmit();
		$form->add_button($this->submit_button);
		$form->add_button(new FormButtonReset());
		
		$this->form = $form;
	}
	
	private function build_contribution_fieldset($form)
	{
		if ($this->get_palmares()->get_id() === null && $this->is_contributor_member())
		{
			$fieldset = new FormFieldsetHTML('contribution', LangLoader::get_message('contribution', 'user-common'));
			$fieldset->set_description(MessageHelper::display($this->lang['palmares.form.contribution.explain'] . ' ' . LangLoader::get_message('contribution.explain', 'user-common'), MessageHelper::WARNING)->render());
			$form->add_fieldset($fieldset);
			
			$fieldset->add_field(new FormFieldRichTextEditor('contribution_description', LangLoader::get_message('contribution.description', 'user-common'), '', array('description' => LangLoader::get_message('contribution.description.explain', 'user-common'))));
		}
	}
	
	private function is_contributor_member()
	{
		return (!PalmaresAuthorizationsService::check_authorizations()->write() && PalmaresAuthorizationsService::check_authorizations()->contribution());
	}
	
	private function get_palmares()
	{
		if ($this->palmares === null)
		{
			$id = AppContext::get_request()->get_getint('id', 0);
			if (!empty($id))
			{
				try {
					$this->palmares = PalmaresService::get_palmares('WHERE id=:id', array('id' => $id));
				} catch (RowNotFoundException $e) {
					$error_controller = PHPBoostErrors::unexisting_page();
					DispatchManager::redirect($error_controller);
				}
			}
			else
			{
				$this->is_new_palmares = true;
				$this->palmares = new Palmares();
				$this->palmares->init_default_properties(AppContext::get_request()->get_getint('id_category', Category::ROOT_CATEGORY));
			}
		}
		return $this->palmares;
	}
	
	private function check_authorizations()
	{
		$palmares = $this->get_palmares();
		
		if ($palmares->get_id() === null)
		{
			if (!$palmares->is_authorized_to_add())
			{
				$error_controller = PHPBoostErrors::user_not_authorized();
				DispatchManager::redirect($error_controller);
			}
		}
		else
		{
			if (!$palmares->is_authorized_to_edit())
			{
				$error_controller = PHPBoostErrors::user_not_authorized();
				DispatchManager::redirect($error_controller);
			}
		}
		if (AppContext::get_current_user()->is_readonly())
		{
			$controller = PHPBoostErrors::user_in_read_only();
			DispatchManager::redirect($controller);
		}
	}
	
	private function save()
	{
		$palmares = $this->get_palmares();
		
		$palmares->set_name($this->form->get_value('name'));
		
		if (PalmaresService::get_categories_manager()->get_categories_cache()->has_categories())
			$palmares->set_id_cat($this->form->get_value('id_cat')->get_raw_value());
		
		$palmares->set_contents($this->form->get_value('contents'));
		$palmares->set_short_contents(($this->form->get_value('enable_short_contents') ? $this->form->get_value('short_contents') : ''));
		$palmares->set_picture(new Url($this->form->get_value('picture')));
		
		if ($this->config->get_author_displayed() == true)
			$palmares->set_author_custom_name(($this->form->get_value('author_custom_name') && $this->form->get_value('author_custom_name') !== $palmares->get_author_user()->get_display_name() ? $this->form->get_value('author_custom_name') : ''));
		
		$palmares->set_sources($this->form->get_value('sources'));
		
		if (!PalmaresAuthorizationsService::check_authorizations($palmares->get_id_cat())->moderation())
		{
			if ($palmares->get_id() === null)
				$palmares->set_creation_date(new Date());
			
			$palmares->set_rewrited_name(Url::encode_rewrite($palmares->get_name()));
			$palmares->clean_start_and_end_date();
			
			if (PalmaresAuthorizationsService::check_authorizations($palmares->get_id_cat())->contribution() && !PalmaresAuthorizationsService::check_authorizations($palmares->get_id_cat())->write())
				$palmares->set_approbation_type(Palmares::NOT_APPROVAL);
		}
		else
		{
			if ($this->form->get_value('update_creation_date'))
			{
				$palmares->set_creation_date(new Date());
			}
			else
			{
				$palmares->set_creation_date($this->form->get_value('creation_date'));
			}

			$rewrited_name = $this->form->get_value('rewrited_name', '');
			$rewrited_name = $this->form->get_value('personalize_rewrited_name') && !empty($rewrited_name) ? $rewrited_name : Url::encode_rewrite($palmares->get_name());
			$palmares->set_rewrited_name($rewrited_name);
			$palmares->set_top_list_enabled($this->form->get_value('top_list'));
			$palmares->set_approbation_type($this->form->get_value('approbation_type')->get_raw_value());
			if ($palmares->get_approbation_type() == Palmares::APPROVAL_DATE)
			{
				$config = PalmaresConfig::load();
				$deferred_operations = $config->get_deferred_operations();
				
				$old_start_date = $palmares->get_start_date();
				$start_date = $this->form->get_value('start_date');
				$palmares->set_start_date($start_date);
				
				if ($old_start_date !== null && $old_start_date->get_timestamp() != $start_date->get_timestamp() && in_array($old_start_date->get_timestamp(), $deferred_operations))
				{
					$key = array_search($old_start_date->get_timestamp(), $deferred_operations);
					unset($deferred_operations[$key]);
				}
				
				if (!in_array($start_date->get_timestamp(), $deferred_operations))
					$deferred_operations[] = $start_date->get_timestamp();
				
				if ($this->form->get_value('end_date_enable'))
				{
					$old_end_date = $palmares->get_end_date();
					$end_date = $this->form->get_value('end_date');
					$palmares->set_end_date($end_date);
					
					if ($old_end_date !== null && $old_end_date->get_timestamp() != $end_date->get_timestamp() && in_array($old_end_date->get_timestamp(), $deferred_operations))
					{
						$key = array_search($old_end_date->get_timestamp(), $deferred_operations);
						unset($deferred_operations[$key]);
					}
					
					if (!in_array($end_date->get_timestamp(), $deferred_operations))
						$deferred_operations[] = $end_date->get_timestamp();
				}
				else
				{
					$palmares->clean_end_date();
				}
				
				$config->set_deferred_operations($deferred_operations);
				PalmaresConfig::save();
			}
			else
			{
				$palmares->clean_start_and_end_date();
			}
		}
		
		if ($palmares->get_id() === null)
		{
			$palmares->set_author_user(AppContext::get_current_user());
			$id_palmares = PalmaresService::add($palmares);
		}
		else
		{
			$id_palmares = $palmares->get_id();
			PalmaresService::update($palmares);
		}
		
		$this->contribution_actions($palmares, $id_palmares);
		
		PalmaresService::get_keywords_manager()->put_relations($id_palmares, $this->form->get_value('keywords'));
		
		Feed::clear_cache('palmares');
	}
	
	private function contribution_actions(Palmares $palmares, $id_palmares)
	{
		if ($palmares->get_id() === null)
		{
			if ($this->is_contributor_member())
			{
				$contribution = new Contribution();
				$contribution->set_id_in_module($id_palmares);
				$contribution->set_description(stripslashes($this->form->get_value('contribution_description')));
				$contribution->set_entitled($palmares->get_name());
				$contribution->set_fixing_url(PalmaresUrlBuilder::edit_palmares($id_palmares)->relative());
				$contribution->set_poster_id(AppContext::get_current_user()->get_id());
				$contribution->set_module('palmares');
				$contribution->set_auth(
					Authorizations::capture_and_shift_bit_auth(
						PalmaresService::get_categories_manager()->get_heritated_authorizations($palmares->get_id_cat(), Category::MODERATION_AUTHORIZATIONS, Authorizations::AUTH_CHILD_PRIORITY),
						Category::MODERATION_AUTHORIZATIONS, Contribution::CONTRIBUTION_AUTH_BIT
					)
				);
				ContributionService::save_contribution($contribution);
			}
		}
		else
		{
			$corresponding_contributions = ContributionService::find_by_criteria('palmares', $id_palmares);
			if (count($corresponding_contributions) > 0)
			{
				foreach ($corresponding_contributions as $contribution)
				{
					$contribution->set_status(Event::EVENT_STATUS_PROCESSED);
					ContributionService::save_contribution($contribution);
				}
			}
		}
		$palmares->set_id($id_palmares);
	}
	
	private function redirect()
	{
		$palmares = $this->get_palmares();
		$category = $palmares->get_category();

		if ($this->is_new_palmares && $this->is_contributor_member() && !$palmares->is_visible())
		{
			DispatchManager::redirect(new UserContributionSuccessController());
		}
		elseif ($palmares->is_visible())
		{
			if ($this->is_new_palmares)
				AppContext::get_response()->redirect(PalmaresUrlBuilder::display_palmares($category->get_id(), $category->get_rewrited_name(), $palmares->get_id(), $palmares->get_rewrited_name()), StringVars::replace_vars($this->lang['palmares.message.success.add'], array('name' => $palmares->get_name())));
			else
				AppContext::get_response()->redirect(($this->form->get_value('referrer') ? $this->form->get_value('referrer') : PalmaresUrlBuilder::display_palmares($category->get_id(), $category->get_rewrited_name(), $palmares->get_id(), $palmares->get_rewrited_name())), StringVars::replace_vars($this->lang['palmares.message.success.edit'], array('name' => $palmares->get_name())));
		}
		else
		{
			if ($this->is_new_palmares)
				AppContext::get_response()->redirect(PalmaresUrlBuilder::display_pending_palmares(), StringVars::replace_vars($this->lang['palmares.message.success.add'], array('name' => $palmares->get_name())));
			else
				AppContext::get_response()->redirect(($this->form->get_value('referrer') ? $this->form->get_value('referrer') : PalmaresUrlBuilder::display_pending_palmares()), StringVars::replace_vars($this->lang['palmares.message.success.edit'], array('name' => $palmares->get_name())));
		}
	}
	
	private function generate_response(View $tpl)
	{
		$palmares = $this->get_palmares();
		
		$response = new SiteDisplayResponse($tpl);
		$graphical_environment = $response->get_graphical_environment();
		
		$breadcrumb = $graphical_environment->get_breadcrumb();
		$breadcrumb->add($this->lang['palmares'], PalmaresUrlBuilder::home());
		
		if ($this->get_palmares()->get_id() === null)
		{
			$graphical_environment->set_page_title($this->lang['palmares.add'], $this->lang['palmares']);
			$breadcrumb->add($this->lang['palmares.add'], PalmaresUrlBuilder::add_palmares($palmares->get_id_cat()));
			$graphical_environment->get_seo_meta_data()->set_description($this->lang['palmares.add']);
			$graphical_environment->get_seo_meta_data()->set_canonical_url(PalmaresUrlBuilder::add_palmares($palmares->get_id_cat()));
		}
		else
		{
			$graphical_environment->set_page_title($this->lang['palmares.edit'], $this->lang['palmares']);
			$graphical_environment->get_seo_meta_data()->set_description($this->lang['palmares.edit']);
			$graphical_environment->get_seo_meta_data()->set_canonical_url(PalmaresUrlBuilder::edit_palmares($palmares->get_id()));
			
			$categories = array_reverse(PalmaresService::get_categories_manager()->get_parents($palmares->get_id_cat(), true));
			foreach ($categories as $id => $category)
			{
				if ($category->get_id() != Category::ROOT_CATEGORY)
					$breadcrumb->add($category->get_name(), PalmaresUrlBuilder::display_category($category->get_id(), $category->get_rewrited_name()));
			}
			$category = $palmares->get_category();
			$breadcrumb->add($palmares->get_name(), PalmaresUrlBuilder::display_palmares($category->get_id(), $category->get_rewrited_name(), $palmares->get_id(), $palmares->get_rewrited_name()));
			$breadcrumb->add($this->lang['palmares.edit'], PalmaresUrlBuilder::edit_palmares($palmares->get_id()));
		}
		
		return $response;
	}
}
?>