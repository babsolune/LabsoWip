<?php
/*##################################################
 *		                         RadioFormController.class.php
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

class RadioFormController extends ModuleController
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

	private $radio;
	private $is_new_radio;

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
		$this->lang = LangLoader::get('common', 'radio');
		$this->common_lang = LangLoader::get('common');
		$this->config = RadioConfig::load();
	}

	private function build_form(HTTPRequestCustom $request)
	{
		$form = new HTMLForm(__CLASS__);

		$fieldset = new FormFieldsetHTML('radio.program', $this->lang['radio.program']);
		$form->add_fieldset($fieldset);

		$fieldset->add_field(new FormFieldTextEditor('name', $this->lang['form.program.name'], $this->get_radio()->get_name(), array('required' => true)));

		if (RadioAuthorizationsService::check_authorizations($this->get_radio()->get_id_cat())->moderation())
		{
			$fieldset->add_field(new FormFieldCheckbox('personalize_rewrited_name', $this->common_lang['form.rewrited_name.personalize'], $this->get_radio()->rewrited_name_is_personalized(), array(
			'events' => array('click' => '
			if (HTMLForms.getField("personalize_rewrited_name").getValue()) {
				HTMLForms.getField("rewrited_name").enable();
			} else {
				HTMLForms.getField("rewrited_name").disable();
			}'
			))));

			$fieldset->add_field(new FormFieldTextEditor('rewrited_name', $this->common_lang['form.rewrited_name'], $this->get_radio()->get_rewrited_name(), array(
				'description' => $this->common_lang['form.rewrited_name.description'],
				'hidden' => !$this->get_radio()->rewrited_name_is_personalized()
			), array(new FormFieldConstraintRegex('`^[a-z0-9\-]+$`iu'))));
		}

		if (RadioService::get_categories_manager()->get_categories_cache()->has_categories())
		{
			$search_category_children_options = new SearchCategoryChildrensOptions();
			$search_category_children_options->add_authorizations_bits(Category::CONTRIBUTION_AUTHORIZATIONS);
			$search_category_children_options->add_authorizations_bits(Category::WRITE_AUTHORIZATIONS);
			$fieldset->add_field(RadioService::get_categories_manager()->get_select_categories_form_field('id_cat', $this->common_lang['form.category'], $this->get_radio()->get_id_cat(), $search_category_children_options));
		}

		$fieldset->add_field(new FormFieldRichTextEditor('contents', $this->lang['form.program.desc'], $this->get_radio()->get_contents(), array('rows' => 15, 'required' => true)));

		$fieldset->add_field(new FormFieldTextEditor('author_custom_name', $this->lang['form.announcer'], $this->get_radio()->get_author_custom_name(), array('required' => true)));

		$fieldset->add_field(new FormFieldSimpleSelectChoice('release_day', $this->lang['form.release.day'], $this->get_radio()->get_release_day(),
			array(
				new FormFieldSelectChoiceOption('', ''),
				new FormFieldSelectChoiceOption($this->lang['form.monday'], Radio::MONDAY),
				new FormFieldSelectChoiceOption($this->lang['form.tuesday'], Radio::TUESDAY),
				new FormFieldSelectChoiceOption($this->lang['form.wednesday'], Radio::WEDNESDAY),
				new FormFieldSelectChoiceOption($this->lang['form.thursday'], Radio::THURSDAY),
				new FormFieldSelectChoiceOption($this->lang['form.friday'], Radio::FRIDAY),
				new FormFieldSelectChoiceOption($this->lang['form.saturday'], Radio::SATURDAY),
				new FormFieldSelectChoiceOption($this->lang['form.sunday'], Radio::SUNDAY),
			),
			array('required' => true)
		));

		$fieldset->add_field($start_date = new FormFieldDateTime('start_date', $this->lang['form.time.start'], $this->get_radio()->get_start_date(), array('required' => true, 'five_minutes_step' => true)));

		$fieldset->add_field($end_date = new FormFieldDateTime('end_date', $this->lang['form.time.end'], $this->get_radio()->get_end_date(), array('required' => true, 'five_minutes_step' => true)));

		$form->add_constraint(new FormConstraintFieldsDifferenceSuperior($start_date, $end_date));

		$other_fieldset = new FormFieldsetHTML('other', $this->common_lang['form.other']);
		$form->add_fieldset($other_fieldset);

		$other_fieldset->add_field(new FormFieldUploadFile('picture', $this->common_lang['form.picture'], $this->get_radio()->get_picture()->relative()));

		if (RadioAuthorizationsService::check_authorizations($this->get_radio()->get_id_cat())->moderation())
		{
			$publication_fieldset = new FormFieldsetHTML('publication', $this->common_lang['form.approbation']);
			$form->add_fieldset($publication_fieldset);

			$publication_fieldset->add_field(new FormFieldSimpleSelectChoice('approbation_type', $this->common_lang['form.approbation'], $this->get_radio()->get_approbation_type(),
				array(
					new FormFieldSelectChoiceOption($this->common_lang['form.approbation.not'], Radio::NOT_APPROVAL),
					new FormFieldSelectChoiceOption($this->common_lang['form.approbation.now'], Radio::APPROVAL_NOW),
				)
			));

			$publication_fieldset->add_field(new FormFieldCheckbox('extra_list', $this->lang['radio.form.extra_list'], $this->get_radio()->extra_list_enabled()));
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
		if ($this->get_radio()->get_id() === null && $this->is_contributor_member())
		{
			$fieldset = new FormFieldsetHTML('contribution', LangLoader::get_message('contribution', 'user-common'));
			$fieldset->set_description(MessageHelper::display($this->lang['radio.form.contribution.explain'] . ' ' . LangLoader::get_message('contribution.explain', 'user-common'), MessageHelper::WARNING)->render());
			$form->add_fieldset($fieldset);

			$fieldset->add_field(new FormFieldRichTextEditor('contribution_description', LangLoader::get_message('contribution.description', 'user-common'), '', array('description' => LangLoader::get_message('contribution.description.explain', 'user-common'))));
		}
	}

	private function is_contributor_member()
	{
		return (!RadioAuthorizationsService::check_authorizations()->write() && RadioAuthorizationsService::check_authorizations()->contribution());
	}

	private function get_radio()
	{
		if ($this->radio === null)
		{
			$id = AppContext::get_request()->get_getint('id', 0);
			if (!empty($id))
			{
				try {
					$this->radio = RadioService::get_radio('WHERE id=:id', array('id' => $id));
				} catch (RowNotFoundException $e) {
					$error_controller = PHPBoostErrors::unexisting_page();
					DispatchManager::redirect($error_controller);
				}
			}
			else
			{
				$this->is_new_radio = true;
				$this->radio = new Radio();
				$this->radio->init_default_properties(AppContext::get_request()->get_getint('id_category', Category::ROOT_CATEGORY));
			}
		}
		return $this->radio;
	}

	private function check_authorizations()
	{
		$radio = $this->get_radio();

		if ($radio->get_id() === null)
		{
			if (!$radio->is_authorized_to_add())
			{
				$error_controller = PHPBoostErrors::user_not_authorized();
				DispatchManager::redirect($error_controller);
			}
		}
		else
		{
			if (!$radio->is_authorized_to_edit())
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
		$radio = $this->get_radio();

		$radio->set_name($this->form->get_value('name'));

		if (RadioService::get_categories_manager()->get_categories_cache()->has_categories())
			$radio->set_id_cat($this->form->get_value('id_cat')->get_raw_value());


		$radio->set_contents($this->form->get_value('contents'));
		$radio->set_release_day($this->form->get_value('release_day')->get_raw_value());
		$radio->set_picture(new Url($this->form->get_value('picture')));


		$radio->set_author_custom_name(($this->form->get_value('author_custom_name') && $this->form->get_value('author_custom_name') !== $radio->get_author_user()->get_display_name() ? $this->form->get_value('author_custom_name') : ''));



		if (!RadioAuthorizationsService::check_authorizations($radio->get_id_cat())->moderation())
		{
			$radio->set_rewrited_name(Url::encode_rewrite($radio->get_name()));
			$radio->set_approbation_type(Radio::NOT_APPROVAL);
		}
		else
		{


			$rewrited_name = $this->form->get_value('rewrited_name', '');
			$rewrited_name = $this->form->get_value('personalize_rewrited_name') && !empty($rewrited_name) ? $rewrited_name : Url::encode_rewrite($radio->get_name());
			$radio->set_rewrited_name($rewrited_name);
			$radio->set_extra_list_enabled($this->form->get_value('extra_list'));
			$radio->set_start_date($this->form->get_value('start_date'));
			$radio->set_end_date($this->form->get_value('end_date'));
			$radio->set_approbation_type($this->form->get_value('approbation_type')->get_raw_value());

		}

		if ($radio->get_id() === null)
		{
			$radio->set_author_user(AppContext::get_current_user());
			$id_radio = RadioService::add($radio);
		}
		else
		{
			$id_radio = $radio->get_id();
			RadioService::update($radio);
		}

		$this->contribution_actions($radio, $id_radio);

		Feed::clear_cache('radio');
	}

	private function contribution_actions(Radio $radio, $id_radio)
	{
		if ($radio->get_id() === null)
		{
			if ($this->is_contributor_member())
			{
				$contribution = new Contribution();
				$contribution->set_id_in_module($id_radio);
				$contribution->set_description(stripslashes($this->form->get_value('contribution_description')));
				$contribution->set_entitled($radio->get_name());
				$contribution->set_fixing_url(RadioUrlBuilder::edit_radio($id_radio)->relative());
				$contribution->set_poster_id(AppContext::get_current_user()->get_id());
				$contribution->set_module('radio');
				$contribution->set_auth(
					Authorizations::capture_and_shift_bit_auth(
						RadioService::get_categories_manager()->get_heritated_authorizations($radio->get_id_cat(), Category::MODERATION_AUTHORIZATIONS, Authorizations::AUTH_CHILD_PRIORITY),
						Category::MODERATION_AUTHORIZATIONS, Contribution::CONTRIBUTION_AUTH_BIT
					)
				);
				ContributionService::save_contribution($contribution);
			}
		}
		else
		{
			$corresponding_contributions = ContributionService::find_by_criteria('radio', $id_radio);
			if (count($corresponding_contributions) > 0)
			{
				foreach ($corresponding_contributions as $contribution)
				{
					$contribution->set_status(Event::EVENT_STATUS_PROCESSED);
					ContributionService::save_contribution($contribution);
				}
			}
		}
		$radio->set_id($id_radio);
	}

	private function redirect()
	{
		$radio = $this->get_radio();
		$category = $radio->get_category();

		if ($this->is_new_radio && $this->is_contributor_member() && !$radio->is_visible())
		{
			DispatchManager::redirect(new UserContributionSuccessController());
		}
		elseif ($radio->is_visible())
		{
			if ($this->is_new_radio)
				AppContext::get_response()->redirect(RadioUrlBuilder::display_radio($category->get_id(), $category->get_rewrited_name(), $radio->get_id(), $radio->get_rewrited_name()), StringVars::replace_vars($this->lang['radio.message.success.add'], array('name' => $radio->get_name())));
			else
				AppContext::get_response()->redirect(($this->form->get_value('referrer') ? $this->form->get_value('referrer') : RadioUrlBuilder::display_radio($category->get_id(), $category->get_rewrited_name(), $radio->get_id(), $radio->get_rewrited_name())), StringVars::replace_vars($this->lang['radio.message.success.edit'], array('name' => $radio->get_name())));
		}
		else
		{
			if ($this->is_new_radio)
				AppContext::get_response()->redirect(RadioUrlBuilder::display_pending_radio(), StringVars::replace_vars($this->lang['radio.message.success.add'], array('name' => $radio->get_name())));
			else
				AppContext::get_response()->redirect(($this->form->get_value('referrer') ? $this->form->get_value('referrer') : RadioUrlBuilder::display_pending_radio()), StringVars::replace_vars($this->lang['radio.message.success.edit'], array('name' => $radio->get_name())));
		}
	}

	private function generate_response(View $tpl)
	{
		$radio = $this->get_radio();

		$response = new SiteDisplayResponse($tpl);
		$graphical_environment = $response->get_graphical_environment();

		$breadcrumb = $graphical_environment->get_breadcrumb();
		$breadcrumb->add($this->lang['radio'], RadioUrlBuilder::home());

		if ($this->get_radio()->get_id() === null)
		{
			$graphical_environment->set_page_title($this->lang['radio.add'], $this->lang['radio']);
			$breadcrumb->add($this->lang['radio.add'], RadioUrlBuilder::add_radio($radio->get_id_cat()));
			$graphical_environment->get_seo_meta_data()->set_description($this->lang['radio.add']);
			$graphical_environment->get_seo_meta_data()->set_canonical_url(RadioUrlBuilder::add_radio($radio->get_id_cat()));
		}
		else
		{
			$graphical_environment->set_page_title($this->lang['radio.edit'], $this->lang['radio']);
			$graphical_environment->get_seo_meta_data()->set_description($this->lang['radio.edit']);
			$graphical_environment->get_seo_meta_data()->set_canonical_url(RadioUrlBuilder::edit_radio($radio->get_id()));

			$categories = array_reverse(RadioService::get_categories_manager()->get_parents($radio->get_id_cat(), true));
			foreach ($categories as $id => $category)
			{
				if ($category->get_id() != Category::ROOT_CATEGORY)
					$breadcrumb->add($category->get_name(), RadioUrlBuilder::display_category($category->get_id(), $category->get_rewrited_name()));
			}
			$category = $radio->get_category();
			$breadcrumb->add($radio->get_name(), RadioUrlBuilder::display_radio($category->get_id(), $category->get_rewrited_name(), $radio->get_id(), $radio->get_rewrited_name()));
			$breadcrumb->add($this->lang['radio.edit'], RadioUrlBuilder::edit_radio($radio->get_id()));
		}

		return $response;
	}
}
?>
