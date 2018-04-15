<?php
/*##################################################
 *                               SponsorsFormPartnerController.class.php
 *                            -------------------
 *   begin                : September 13, 2017
 *   copyright            : (C) 2017 Sebastien LARTIGUE
 *   email                : babsolune@phpboost.com
 *
 *
 ###################################################
 *
 * This program is a free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
 ###################################################*/

 /**
 * @author Sebastien LARTIGUE <babsolune@phpboost.com>
 */

class SponsorsFormPartnerController extends ModuleController
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

	private $partner;
	private $is_new_partner;

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
	}

	private function build_form(HTTPRequestCustom $request)
	{
		$form = new HTMLForm(__CLASS__);

		$fieldset = new FormFieldsetHTML('sponsors', $this->get_partner()->get_id() === null ? $this->lang['sponsors.add'] : $this->lang['sponsors.edit']);
		$form->add_fieldset($fieldset);

		$fieldset->add_field(new FormFieldTextEditor('name', $this->common_lang['form.name'], $this->get_partner()->get_name(), array('required' => true)));

		if (SponsorsService::get_categories_manager()->get_categories_cache()->has_categories())
		{
			$search_category_children_options = new SearchCategoryChildrensOptions();
			$search_category_children_options->add_authorizations_bits(Category::CONTRIBUTION_AUTHORIZATIONS);
			$search_category_children_options->add_authorizations_bits(Category::WRITE_AUTHORIZATIONS);
			$fieldset->add_field(SponsorsService::get_categories_manager()->get_select_categories_form_field('id_category', $this->common_lang['form.category'], $this->get_partner()->get_id_category(), $search_category_children_options));
		}

		$fieldset->add_field(new FormFieldUrlEditor('website_url', $this->lang['partner.form.website.url'], $this->get_partner()->get_website_url()->absolute()));

		$fieldset->add_field(new FormFieldSimpleSelectChoice('partner_type', $this->lang['partner.form.type'], $this->get_partner()->get_partner_type(),
			array(
				new FormFieldSelectChoiceOption('', ''),
				new FormFieldSelectChoiceOption($this->lang['partner.type.platinium'], Partner::PLATINIUM_PARTNER),
				new FormFieldSelectChoiceOption($this->lang['partner.type.gold'], Partner::GOLDEN_PARTNER),
				new FormFieldSelectChoiceOption($this->lang['partner.type.silver'], Partner::SILVER_PARTNER),
				new FormFieldSelectChoiceOption($this->lang['partner.type.bronze'], Partner::BRONZE_PARTNER),
			),
			array('required' => true)
		));

		$activity = $this->get_partner()->get_activity();
		$fieldset->add_field(new FormFieldSimpleSelectChoice('activity', $this->lang['partner.form.activity'], $activity, $this->list_activity(),
			array('required' => true)
		));

		$fieldset->add_field(new FormFieldUploadPictureFile('partner_picture', $this->common_lang['form.picture'], $this->get_partner()->get_partner_picture()->relative(), array('required' => true)));

		$other_fieldset = new FormFieldsetHTML('other', $this->common_lang['form.other']);
		$form->add_fieldset($other_fieldset);

		$other_fieldset->add_field(new FormFieldRichTextEditor('contents', $this->common_lang['form.description'], $this->get_partner()->get_contents(), array('rows' => 15)));

		$other_fieldset->add_field(SponsorsService::get_keywords_manager()->get_form_field($this->get_partner()->get_id(), 'keywords', $this->common_lang['form.keywords'], array('description' => $this->common_lang['form.keywords.description'])));

		if (SponsorsAuthorizationsService::check_authorizations($this->get_partner()->get_id_category())->moderation())
		{
			$publication_fieldset = new FormFieldsetHTML('publication', $this->common_lang['form.approbation']);
			$form->add_fieldset($publication_fieldset);

			$publication_fieldset->add_field(new FormFieldDateTime('creation_date', $this->common_lang['form.date.creation'], $this->get_partner()->get_creation_date(),
				array('required' => true)
			));

			if (!$this->get_partner()->is_visible())
			{
				$publication_fieldset->add_field(new FormFieldCheckbox('update_creation_date', $this->common_lang['form.update.date.creation'], false, array('hidden' => $this->get_partner()->get_status() != Partner::NOT_APPROVAL)
				));
			}

			$publication_fieldset->add_field(new FormFieldSimpleSelectChoice('approbation_type', $this->common_lang['form.approbation'], $this->get_partner()->get_approbation_type(),
				array(
					new FormFieldSelectChoiceOption($this->common_lang['form.approbation.not'], Partner::NOT_APPROVAL),
					new FormFieldSelectChoiceOption($this->common_lang['form.approbation.now'], Partner::APPROVAL_NOW),
				)
			));
		}

		$this->build_contribution_fieldset($form);

		$fieldset->add_field(new FormFieldHidden('referrer', $request->get_url_referrer()));

		$this->submit_button = new FormButtonDefaultSubmit();
		$form->add_button($this->submit_button);
		$form->add_button(new FormButtonReset());

		$this->form = $form;
	}

	private function list_activity()
	{
		$options = array();

		if ($this->get_partner()->get_id() === null)
			$options[] = new FormFieldSelectChoiceOption('', '');

		for ($i = 1; $i <= 8 ; $i++)
		{
			$options[] = new FormFieldSelectChoiceOption($this->lang['activity.' . $i], $i);
		}

		return $options;
	}

	private function build_contribution_fieldset($form)
	{
		if ($this->get_partner()->get_id() === null && $this->is_contributor_member())
		{
			$fieldset = new FormFieldsetHTML('contribution', LangLoader::get_message('contribution', 'user-common'));
			$fieldset->set_description(MessageHelper::display($this->lang['sponsors.form.contribution.explain'] . ' ' . LangLoader::get_message('contribution.explain', 'user-common'), MessageHelper::WARNING)->render());
			$form->add_fieldset($fieldset);

			$fieldset->add_field(new FormFieldRichTextEditor('contribution_description', LangLoader::get_message('contribution.description', 'user-common'), '', array('description' => LangLoader::get_message('contribution.description.explain', 'user-common'))));
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
				try {
					$this->partner = SponsorsService::get_partner('WHERE sponsors.id=:id', array('id' => $id));
				} catch (RowNotFoundException $e) {
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

		$partner->set_name($this->form->get_value('name'));
		$partner->set_rewrited_name(Url::encode_rewrite($partner->get_name()));

		if (SponsorsService::get_categories_manager()->get_categories_cache()->has_categories())
			$partner->set_id_category($this->form->get_value('id_category')->get_raw_value());

		$partner->set_website_url(new Url($this->form->get_value('website_url')));
		$partner->set_contents($this->form->get_value('contents'));
		$partner->set_partner_picture(new Url($this->form->get_value('partner_picture')));

		$partner->set_partner_type($this->form->get_value('partner_type')->get_raw_value());
		$partner->set_activity($this->form->get_value('activity')->get_raw_value());

		if (!SponsorsAuthorizationsService::check_authorizations($partner->get_id_category())->moderation())
		{
			if ($partner->get_id() === null )
				$partner->set_creation_date(new Date());

			$partner->clean_start_and_end_date();

			if (SponsorsAuthorizationsService::check_authorizations($partner->get_id_category())->contribution() && !SponsorsAuthorizationsService::check_authorizations($partner->get_id_category())->write())
				$partner->set_approbation_type(Partner::NOT_APPROVAL);
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
			$partner->set_approbation_type($this->form->get_value('approbation_type')->get_raw_value());

		}

		if ($partner->get_id() === null)
		{
			$id = SponsorsService::add($partner);
		}
		else
		{
			$id = $partner->get_id();
			SponsorsService::update($partner);
		}

		$this->contribution_actions($partner, $id);

		SponsorsService::get_keywords_manager()->put_relations($id, $this->form->get_value('keywords'));

		SponsorsCategoriesCache::invalidate();
	}

	private function contribution_actions(Partner $partner, $id)
	{
		if ($partner->get_id() === null)
		{
			if ($this->is_contributor_member())
			{
				$contribution = new Contribution();
				$contribution->set_id_in_module($id);
				$contribution->set_description(stripslashes($this->form->get_value('contribution_description')));
				$contribution->set_entitled($partner->get_name());
				$contribution->set_fixing_url(SponsorsUrlBuilder::edit($id)->relative());
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
		}
		else
		{
			$corresponding_contributions = ContributionService::find_by_criteria('sponsors', $id);
			if (count($corresponding_contributions) > 0)
			{
				foreach ($corresponding_contributions as $contribution)
				{
					$contribution->set_status(Event::EVENT_STATUS_PROCESSED);
					ContributionService::save_contribution($contribution);
				}
			}
		}
		$partner->set_id($id);
	}

	private function redirect()
	{
		$partner = $this->get_partner();
		$category = $partner->get_category();

		if ($this->is_new_partner && $this->is_contributor_member() && !$partner->is_visible())
		{
			DispatchManager::redirect(new UserContributionSuccessController());
		}
		elseif ($partner->is_visible())
		{
			if ($this->is_new_partner)
				AppContext::get_response()->redirect(SponsorsUrlBuilder::display($category->get_id(), $category->get_rewrited_name(), $partner->get_id(), $partner->get_rewrited_name()), StringVars::replace_vars($this->lang['sponsors.message.success.add'], array('name' => $partner->get_name())));
			else
				AppContext::get_response()->redirect(($this->form->get_value('referrer') ? $this->form->get_value('referrer') : SponsorsUrlBuilder::display($category->get_id(), $category->get_rewrited_name(), $partner->get_id(), $partner->get_rewrited_name())), StringVars::replace_vars($this->lang['sponsors.message.success.edit'], array('name' => $partner->get_name())));
		}
		else
		{
			if ($this->is_new_partner)
				AppContext::get_response()->redirect(SponsorsUrlBuilder::display_pending(), StringVars::replace_vars($this->lang['sponsors.message.success.add'], array('name' => $partner->get_name())));
			else
				AppContext::get_response()->redirect(($this->form->get_value('referrer') ? $this->form->get_value('referrer') : SponsorsUrlBuilder::display_pending()), StringVars::replace_vars($this->lang['sponsors.message.success.edit'], array('name' => $partner->get_name())));
		}
	}

	private function generate_response(View $tpl)
	{
		$partner = $this->get_partner();

		$response = new SiteDisplayResponse($tpl);
		$graphical_environment = $response->get_graphical_environment();

		$breadcrumb = $graphical_environment->get_breadcrumb();
		$breadcrumb->add($this->lang['module_title'], SponsorsUrlBuilder::home());

		if ($partner->get_id() === null)
		{
			$graphical_environment->set_page_title($this->lang['sponsors.add']);
			$breadcrumb->add($this->lang['sponsors.add'], SponsorsUrlBuilder::add($partner->get_id_category()));
			$graphical_environment->get_seo_meta_data()->set_description($this->lang['sponsors.add'], $this->lang['module_title']);
			$graphical_environment->get_seo_meta_data()->set_canonical_url(SponsorsUrlBuilder::add($partner->get_id_category()));
		}
		else
		{
			$graphical_environment->set_page_title($this->lang['sponsors.edit']);
			$graphical_environment->get_seo_meta_data()->set_description($this->lang['sponsors.edit'], $this->lang['module_title']);
			$graphical_environment->get_seo_meta_data()->set_canonical_url(SponsorsUrlBuilder::edit($partner->get_id()));

			$categories = array_reverse(SponsorsService::get_categories_manager()->get_parents($partner->get_id_category(), true));
			foreach ($categories as $id => $category)
			{
				if ($category->get_id() != Category::ROOT_CATEGORY)
					$breadcrumb->add($category->get_name(), SponsorsUrlBuilder::display_category($category->get_id(), $category->get_rewrited_name()));
			}
			$category = $partner->get_category();
			$breadcrumb->add($partner->get_name(), SponsorsUrlBuilder::display($category->get_id(), $category->get_rewrited_name(), $partner->get_id(), $partner->get_rewrited_name()));
			$breadcrumb->add($this->lang['sponsors.edit'], SponsorsUrlBuilder::edit($partner->get_id()));
		}

		return $response;
	}
}
?>
