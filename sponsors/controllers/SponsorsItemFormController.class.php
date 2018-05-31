<?php
/*##################################################
 *                       SponsorsItemFormController.class.php
 *                            -------------------
 *   begin                : May 20, 2018
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

class SponsorsItemFormController extends ModuleController
{
	/**
	 * @var HTMLForm
	 */
	private $form;
	/**
	 * @var FormButtonSubmit
	 */
	private $submit_button;

	private $tpl;

	private $lang;
	private $common_lang;

	private $partner;
	private $is_new_partner;
	private $config;

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
		$this->lang = LangLoader::get('common', 'sponsors');
		$this->common_lang = LangLoader::get('common');
		$this->config = SponsorsConfig::load();
	}

	private function build_form(HTTPRequestCustom $request)
	{
		$form = new HTMLForm(__CLASS__);

		$fieldset = new FormFieldsetHTML('sponsors', $this->get_partner()->get_id() === null ? $this->lang['sponsors.form.add'] : $this->lang['sponsors.form.edit']);
		$form->add_fieldset($fieldset);

		$fieldset->add_field(new FormFieldTextEditor('title', $this->common_lang['form.title'], $this->get_partner()->get_title(),
			array('required' => true)
		));

		if (SponsorsAuthorizationsService::check_authorizations($this->get_partner()->get_id_category())->moderation())
		{
			$fieldset->add_field(new FormFieldCheckbox('personalize_rewrited_title', $this->common_lang['form.rewrited_name.personalize'], $this->get_partner()->rewrited_title_is_personalized(),
				array('events' => array('click' =>'
					if (HTMLForms.getField("personalize_rewrited_title").getValue()) {
						HTMLForms.getField("rewrited_title").enable();
					} else {
						HTMLForms.getField("rewrited_title").disable();
					}'
				))
			));

			$fieldset->add_field(new FormFieldTextEditor('rewrited_title', $this->common_lang['form.rewrited_name'], $this->get_partner()->get_rewrited_title(),
				array('description' => $this->common_lang['form.rewrited_name.description'],
				      'hidden' => !$this->get_partner()->rewrited_title_is_personalized()),
				array(new FormFieldConstraintRegex('`^[a-z0-9\-]+$`iu'))
			));
		}

		$fieldset->add_field(new FormFieldUrlEditor('website_url', $this->lang['sponsors.form.website'], $this->get_partner()->get_website()->absolute()));

		$fieldset->add_field(new FormFieldSimpleSelectChoice('partner_level', $this->lang['sponsors.form.level'], $this->get_partner()->get_partner_level(), $this->levels_list(),
			array('required' => true)
		));

		if (SponsorsService::get_categories_manager()->get_categories_cache()->has_categories())
		{
			$search_category_children_options = new SearchCategoryChildrensOptions();
			$search_category_children_options->add_authorizations_bits(Category::CONTRIBUTION_AUTHORIZATIONS);
			$search_category_children_options->add_authorizations_bits(Category::WRITE_AUTHORIZATIONS);
			$fieldset->add_field(SponsorsService::get_categories_manager()->get_select_categories_form_field('id_category', $this->common_lang['form.category'], $this->get_partner()->get_id_category(), $search_category_children_options,
				array('description' => $this->lang['sponsors.select.category'])
			));
		}

		$fieldset->add_field(new FormFieldRichTextEditor('contents', $this->common_lang['form.contents'], $this->get_partner()->get_contents(),
			array('rows' => 15)
		));

		$other_fieldset = new FormFieldsetHTML('other', $this->common_lang['form.other']);
		$form->add_fieldset($other_fieldset);

		$other_fieldset->add_field(new FormFieldUploadPictureFile('thumbnail', $this->common_lang['form.picture'], $this->get_partner()->get_thumbnail()->relative()));

		if (SponsorsAuthorizationsService::check_authorizations($this->get_partner()->get_id_category())->moderation())
		{
			$publication_fieldset = new FormFieldsetHTML('publication', $this->common_lang['form.approbation']);
			$form->add_fieldset($publication_fieldset);

			$publication_fieldset->add_field(new FormFieldDateTime('creation_date', $this->common_lang['form.date.creation'], $this->get_partner()->get_creation_date(),
				array('required' => true)
			));

			if (!$this->get_partner()->is_published())
			{
				$publication_fieldset->add_field(new FormFieldCheckbox('update_creation_date', $this->common_lang['form.update.date.creation'], false, array('hidden' => $this->get_partner()->get_status() != Partner::NOT_PUBLISHED)
				));
			}

			$publication_fieldset->add_field(new FormFieldSimpleSelectChoice('publication_state', $this->common_lang['form.approbation'], $this->get_partner()->get_publication_state(),
				array(
					new FormFieldSelectChoiceOption($this->common_lang['form.approbation.not'], Partner::NOT_PUBLISHED),
					new FormFieldSelectChoiceOption($this->common_lang['form.approbation.now'], Partner::PUBLISHED_NOW),
					new FormFieldSelectChoiceOption($this->common_lang['status.approved.date'], Partner::PUBLICATION_DATE),
				),
				array('events' => array('change' => '
				if (HTMLForms.getField("publication_state").getValue() == 2) {
					jQuery("#' . __CLASS__ . '_publication_start_date_field").show();
					HTMLForms.getField("end_date_enable").enable();
				} else {
					jQuery("#' . __CLASS__ . '_publication_start_date_field").hide();
					HTMLForms.getField("end_date_enable").disable();
				}'))
			));

			$publication_fieldset->add_field(new FormFieldDateTime('publication_start_date', $this->common_lang['form.date.start'],
				($this->get_partner()->get_publication_start_date() === null ? new Date() : $this->get_partner()->get_publication_start_date()),
				array('hidden' => ($this->get_partner()->get_publication_state() != Partner::PUBLICATION_DATE))
			));

			$publication_fieldset->add_field(new FormFieldCheckbox('end_date_enable', $this->common_lang['form.date.end.enable'], $this->get_partner()->enabled_end_date(),
				array('hidden' => ($this->get_partner()->get_publication_state() != Partner::PUBLICATION_DATE),
					'events' => array('click' => '
						if (HTMLForms.getField("end_date_enable").getValue()) {
							HTMLForms.getField("publication_end_date").enable();
						} else {
							HTMLForms.getField("publication_end_date").disable();
						}'
				))
			));

			$publication_fieldset->add_field(new FormFieldDateTime('publication_end_date', $this->common_lang['form.date.end'],
				($this->get_partner()->get_publication_end_date() === null ? new date() : $this->get_partner()->get_publication_end_date()),
				array('hidden' => !$this->get_partner()->enabled_end_date())
			));
		}

		$this->build_contribution_fieldset($form);

		$fieldset->add_field(new FormFieldHidden('referrer', $request->get_url_referrer()));

		$this->submit_button = new FormButtonDefaultSubmit();
		$form->add_button($this->submit_button);
		$form->add_button(new FormButtonReset());

		$this->form = $form;
	}

	private function levels_list()
	{
		$options = array();
		$levels = SponsorsConfig::load()->get_levels();

		// laisser un vide en début de liste
		$options[] = new FormFieldSelectChoiceOption('', '');

		$i = 1;
		foreach($levels as $name)
		{
			$options[] = new FormFieldSelectChoiceOption($name, $i);
			$i++;
		}

		return $options;
	}

	private function build_contribution_fieldset($form)
	{
		if ($this->get_partner()->get_id() === null && $this->is_contributor_member())
		{
			$fieldset = new FormFieldsetHTML('contribution', LangLoader::get_message('contribution', 'user-common'));
			$fieldset->set_description(MessageHelper::display(LangLoader::get_message('sponsors.form.member.contribution.explain', 'common', 'sponsors'), MessageHelper::WARNING)->render());
			$form->add_fieldset($fieldset);

			$fieldset->add_field(new FormFieldRichTextEditor('contribution_description', LangLoader::get_message('contribution.description', 'user-common'), '',
				array('description' => LangLoader::get_message('contribution.description.explain', 'user-common'))
			));
		}
		elseif ($this->get_partner()->is_published() && $this->get_partner()->is_authorized_to_edit() && !AppContext::get_current_user()->check_level(User::ADMIN_LEVEL))
		{
			$fieldset = new FormFieldsetHTML('member_edition', LangLoader::get_message('sponsors.form.member.edition', 'common', 'sponsors'));
			$fieldset->set_description(MessageHelper::display(LangLoader::get_message('sponsors.form.member.edition.explain', 'common', 'sponsors'), MessageHelper::WARNING)->render());
			$form->add_fieldset($fieldset);

			$fieldset->add_field(new FormFieldRichTextEditor('edittion_description', LangLoader::get_message('sponsors.form.member.edition.description', 'common', 'sponsors'), '',
				array('description' => LangLoader::get_message('sponsors.form.member.edition.description.desc', 'common', 'sponsors'))
			));
		}
	}

	private function is_contributor_member()
	{
		return (!SponsorsAuthorizationsService::check_authorizations()->write() && SponsorsAuthorizationsService::check_authorizations()->contribution());
	}

	private function get_partner()
	{
		if ($this->partner === null)
		{
			$id = AppContext::get_request()->get_getint('id', 0);
			if (!empty($id))
			{
				try
				{
					$this->partner = SponsorsService::get_partner('WHERE sponsors.id=:id', array('id' => $id));
				}
				catch(RowNotFoundException $e)
				{
					$error_controller = PHPBoostErrors::unexisting_page();
					DispatchManager::redirect($error_controller);
				}
			}
			else
			{
				$this->is_new_partner = true;
				$this->partner = new Partner();
				$this->partner->init_default_properties(AppContext::get_request()->get_getint('id_category', Category::ROOT_CATEGORY));
			}
		}
		return $this->partner;
	}

	private function check_authorizations()
	{
		$partner = $this->get_partner();

		if ($partner->get_id() === null)
		{
			if (!$partner->is_authorized_to_add())
			{
				$error_controller = PHPBoostErrors::user_not_authorized();
				DispatchManager::redirect($error_controller);
			}
		}
		else
		{
			if (!$partner->is_authorized_to_edit())
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
		$partner = $this->get_partner();

		$partner->set_title($this->form->get_value('title'));

		$partner->set_website(new Url($this->form->get_value('website_url')));
		$partner->set_partner_level($this->form->get_value('partner_level')->get_raw_value());
		if (SponsorsService::get_categories_manager()->get_categories_cache()->has_categories())
			$partner->set_id_category($this->form->get_value('id_category')->get_raw_value());

		$partner->set_contents($this->form->get_value('contents'));

		$partner->set_thumbnail(new Url($this->form->get_value('thumbnail')));

		if (!SponsorsAuthorizationsService::check_authorizations($partner->get_id_category())->moderation())
		{
			if ($partner->get_id() === null)
				$partner->set_creation_date(new Date());

			$partner->set_rewrited_title(Url::encode_rewrite($partner->get_title()));
			$partner->clean_publication_start_and_end_date();

			if (SponsorsAuthorizationsService::check_authorizations($partner->get_id_category())->contribution() && !SponsorsAuthorizationsService::check_authorizations($partner->get_id_category())->write())
				$partner->set_publication_state(Partner::NOT_PUBLISHED);
		}
		else
		{
			if ($this->form->get_value('update_creation_date'))
			{
				$partner->set_creation_date(new Date());
			}
			else
			{
				$partner->set_creation_date($this->form->get_value('creation_date'));
			}

			$rewrited_title = $this->form->get_value('rewrited_title', '');
			$rewrited_title = $this->form->get_value('personalize_rewrited_title') && !empty($rewrited_title) ? $rewrited_title : Url::encode_rewrite($partner->get_title());
			$partner->set_rewrited_title($rewrited_title);

			$partner->set_publication_state($this->form->get_value('publication_state')->get_raw_value());
			if ($partner->get_publication_state() == Partner::PUBLICATION_DATE)
			{
				$config = SponsorsConfig::load();
				$deferred_operations = $config->get_deferred_operations();

				$old_start_date = $partner->get_publication_start_date();
				$start_date = $this->form->get_value('publication_start_date');
				$partner->set_publication_start_date($start_date);

				if ($old_start_date !== null && $old_start_date->get_timestamp() != $start_date->get_timestamp() && in_array($old_start_date->get_timestamp(), $deferred_operations))
				{
					$key = array_search($old_start_date->get_timestamp(), $deferred_operations);
					unset($deferred_operations[$key]);
				}

				if (!in_array($start_date->get_timestamp(), $deferred_operations))
					$deferred_operations[] = $start_date->get_timestamp();

				if ($this->form->get_value('end_date_enable'))
				{
					$old_end_date = $partner->get_publication_end_date();
					$end_date = $this->form->get_value('publication_end_date');
					$partner->set_publication_end_date($end_date);

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
					$partner->clean_publication_end_date();
				}

				$config->set_deferred_operations($deferred_operations);
				SponsorsConfig::save();
			}
			else
			{
				$partner->clean_publication_start_and_end_date();
			}
		}

		if ($partner->get_id() === null)
		{
			$partner->set_author_user(AppContext::get_current_user());
			$id_partner = SponsorsService::add($partner);
		}
		else
		{
			$now = new Date();
			$partner->set_updated_date($now);
			$id_partner = $partner->get_id();
			SponsorsService::update($partner);
		}

		$this->contribution_actions($partner, $id_partner);

		Feed::clear_cache('sponsors');
		SponsorsCache::invalidate();
		SponsorsCategoriesCache::invalidate();
	}

	private function contribution_actions(Partner $partner, $id_partner)
	{
		if ($this->is_contributor_member())
		{
			$contribution = new Contribution();
			$contribution->set_id_in_module($id_partner);
			if ($partner->get_id() === null)
				$contribution->set_description(stripslashes($this->form->get_value('contribution_description')));
			else
				$contribution->set_description(stripslashes($this->form->get_value('edittion_description')));

			$contribution->set_entitled($partner->get_title());
			$contribution->set_fixing_url(SponsorsUrlBuilder::edit_item($id_partner)->relative());
			$contribution->set_poster_id(AppContext::get_current_user()->get_id());
			$contribution->set_module('sponsors');
			$contribution->set_auth(
				Authorizations::capture_and_shift_bit_auth(
					SponsorsService::get_categories_manager()->get_heritated_authorizations($partner->get_id_category(), Category::MODERATION_AUTHORIZATIONS, Authorizations::AUTH_CHILD_PRIORITY),
					Category::MODERATION_AUTHORIZATIONS, Contribution::CONTRIBUTION_AUTH_BIT
				)
			);
			ContributionService::save_contribution($contribution);
		}
		$partner->set_id($id_partner);
	}

	private function redirect()
	{
		$partner = $this->get_partner();
		$category = $partner->get_category();

		if ($this->is_new_partner && $this->is_contributor_member() && !$partner->is_published())
		{
			DispatchManager::redirect(new UserContributionSuccessController());
		}
		elseif ($partner->is_published())
		{
			if ($this->is_new_partner)
				AppContext::get_response()->redirect(SponsorsUrlBuilder::display_item($category->get_id(), $category->get_rewrited_name(), $partner->get_id(), $partner->get_rewrited_title(), AppContext::get_request()->get_getint('page', 1)), StringVars::replace_vars($this->lang['sponsors.message.success.add'], array('title' => $partner->get_title())));
			else
				AppContext::get_response()->redirect(($this->form->get_value('referrer') ? $this->form->get_value('referrer') : SponsorsUrlBuilder::display_item($category->get_id(), $category->get_rewrited_name(), $partner->get_id(), $partner->get_rewrited_title(), AppContext::get_request()->get_getint('page', 1))), StringVars::replace_vars($this->lang['sponsors.message.success.edit'], array('title' => $partner->get_title())));
		}
		else
		{
			if ($this->is_new_partner)
				AppContext::get_response()->redirect(SponsorsUrlBuilder::display_pending_items(), StringVars::replace_vars($this->lang['sponsors.message.success.add'], array('title' => $partner->get_title())));
			else
				AppContext::get_response()->redirect(($this->form->get_value('referrer') ? $this->form->get_value('referrer') : SponsorsUrlBuilder::display_pending_items()), StringVars::replace_vars($this->lang['sponsors.message.success.edit'], array('title' => $partner->get_title())));
		}
	}

	private function generate_response(View $tpl)
	{
		$partner = $this->get_partner();

		$response = new SiteDisplayResponse($tpl);
		$graphical_environment = $response->get_graphical_environment();

		$breadcrumb = $graphical_environment->get_breadcrumb();
		$breadcrumb->add($this->lang['sponsors.module.title'], SponsorsUrlBuilder::home());

		if ($partner->get_id() === null)
		{
			$breadcrumb->add($this->lang['sponsors.add'], SponsorsUrlBuilder::add_item($partner->get_id_category()));
			$graphical_environment->set_page_title($this->lang['sponsors.add'], $this->lang['sponsors.module.title']);
			$graphical_environment->get_seo_meta_data()->set_description($this->lang['sponsors.add']);
			$graphical_environment->get_seo_meta_data()->set_canonical_url(SponsorsUrlBuilder::add_item($partner->get_id_category()));
		}
		else
		{
			$categories = array_reverse(SponsorsService::get_categories_manager()->get_parents($partner->get_id_category(), true));
			foreach ($categories as $id => $category)
			{
				if ($category->get_id() != Category::ROOT_CATEGORY)
					$breadcrumb->add($category->get_name(), SponsorsUrlBuilder::display_category($category->get_id(), $category->get_rewrited_name()));
			}
			$breadcrumb->add($partner->get_title(), SponsorsUrlBuilder::display_item($category->get_id(), $category->get_rewrited_name(), $partner->get_id(), $partner->get_rewrited_title()));

			$breadcrumb->add($this->lang['sponsors.edit'], SponsorsUrlBuilder::edit_item($partner->get_id()));
			$graphical_environment->set_page_title($this->lang['sponsors.edit'], $this->lang['sponsors.module.title']);
			$graphical_environment->get_seo_meta_data()->set_description($this->lang['sponsors.edit']);
			$graphical_environment->get_seo_meta_data()->set_canonical_url(SponsorsUrlBuilder::edit_item($partner->get_id()));
		}

		return $response;
	}
}
?>
