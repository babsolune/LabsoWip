<?php
/*##################################################
 *                       PortfolioItemFormController.class.php
 *                            -------------------
 *   begin                : November 29, 2017
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

class PortfolioItemFormController extends ModuleController
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

	private $work;
	private $is_new_work;

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
			'FORM' => $this->form->display(),
			'C_TINYMCE_EDITOR' => AppContext::get_current_user()->get_editor() == 'TinyMCE'
		));

		return $this->build_response($this->tpl);
	}

	private function init()
	{
		$this->lang = LangLoader::get('common', 'portfolio');
		$this->tpl = new FileTemplate('portfolio/PortfolioItemFormController.tpl');
		$this->tpl->add_lang($this->lang);
		$this->common_lang = LangLoader::get('common');
	}

	private function build_form(HTTPRequestCustom $request)
	{
		$form = new HTMLForm(__CLASS__);

		$fieldset = new FormFieldsetHTML('portfolio', $this->lang['portfolio.module.title']);
		$form->add_fieldset($fieldset);

		$fieldset->add_field(new FormFieldTextEditor('title', $this->common_lang['form.title'], $this->get_work()->get_title(),
			array('required' => true)
		));

		if (PortfolioAuthorizationsService::check_authorizations($this->get_work()->get_category_id())->moderation())
		{
			$fieldset->add_field(new FormFieldCheckbox('personalize_rewrited_title', $this->common_lang['form.rewrited_name.personalize'], $this->get_work()->rewrited_title_is_personalized(),
				array('events' => array('click' =>'
					if (HTMLForms.getField("personalize_rewrited_title").getValue()) {
						HTMLForms.getField("rewrited_title").enable();
					} else {
						HTMLForms.getField("rewrited_title").disable();
					}'
				))
			));

			$fieldset->add_field(new FormFieldTextEditor('rewrited_title', $this->common_lang['form.rewrited_name'], $this->get_work()->get_rewrited_title(),
				array('description' => $this->common_lang['form.rewrited_name.description'],
				      'hidden' => !$this->get_work()->rewrited_title_is_personalized()),
				array(new FormFieldConstraintRegex('`^[a-z0-9\-]+$`iu'))
			));
		}

		if (PortfolioService::get_categories_manager()->get_categories_cache()->has_categories())
		{
			$search_category_children_options = new SearchCategoryChildrensOptions();
			$search_category_children_options->add_authorizations_bits(Category::CONTRIBUTION_AUTHORIZATIONS);
			$search_category_children_options->add_authorizations_bits(Category::WRITE_AUTHORIZATIONS);
			$fieldset->add_field(PortfolioService::get_categories_manager()->get_select_categories_form_field('category_id', $this->common_lang['form.category'], $this->get_work()->get_category_id(), $search_category_children_options));
		}

		$fieldset->add_field(new FormFieldCheckbox('enable_description', $this->lang['portfolio.form.enabled.description'], $this->get_work()->get_description_enabled(),
			array('description' => StringVars::replace_vars($this->lang['portfolio.form.enabled.description.description'],
			array('number' => PortfolioConfig::load()->get_characters_number_to_cut())),
				'events' => array('click' => '
					if (HTMLForms.getField("enable_description").getValue()) {
						HTMLForms.getField("description").enable();
					} else {
						HTMLForms.getField("description").disable();
					}'
		))));

		$fieldset->add_field(new FormFieldRichTextEditor('description', StringVars::replace_vars($this->lang['portfolio.form.description'],
			array('number' =>PortfolioConfig::load()->get_characters_number_to_cut())), $this->get_work()->get_description(),
			array('rows' => 3, 'hidden' => !$this->get_work()->get_description_enabled())
		));

		$fieldset->add_field(new FormFieldRichTextEditor('contents', $this->common_lang['form.contents'], $this->get_work()->get_contents(),
			array('rows' => 15, 'required' => true)
		));

	$fieldset->add_field(new FormFieldActionLink('add_page', $this->lang['portfolio.form.add.page'] , 'javascript:bbcode_page();', 'fa-pagebreak'));

		if ($this->get_work()->get_displayed_author_name() == true)
		{
			$fieldset->add_field(new FormFieldCheckbox('enabled_author_name_customization', $this->lang['portfolio.form.enabled.author.name.customisation'], $this->get_work()->is_enabled_author_name_customization(),
				array('events' => array('click' => '
				if (HTMLForms.getField("enabled_author_name_customization").getValue()) {
					HTMLForms.getField("custom_author_name").enable();
				} else {
					HTMLForms.getField("custom_author_name").disable();
				}'))
			));

			$fieldset->add_field(new FormFieldTextEditor('custom_author_name', $this->lang['portfolio.form.custom.author.name'], $this->get_work()->get_custom_author_name(), array(
				'hidden' => !$this->get_work()->is_enabled_author_name_customization(),
			)));
		}

		$other_fieldset = new FormFieldsetHTML('other', $this->common_lang['form.other']);
		$form->add_fieldset($other_fieldset);

		$other_fieldset->add_field(new FormFieldCheckbox('links_visibility', $this->lang['portfolio.form.enable.links.visibility'], $this->get_work()->get_links_visibility()));

		$other_fieldset->add_field(new FormFieldUploadFile('file_url', $this->lang['portfolio.form.file.url'], $this->get_work()->get_file_url()->relative()));

		if ($this->get_work()->get_id() !== null && $this->get_work()->get_downloads_number() > 0)
		{
			$other_fieldset->add_field(new FormFieldCheckbox('reset_downloads_number', $this->lang['portfolio.form.reset.downloads.number']));
		}

		$other_fieldset->add_field(new FormFieldUrlEditor('website_url', $this->lang['portfolio.form.website.url'], $this->get_work()->get_website_url()->absolute()));

		$other_fieldset->add_field(new FormFieldCheckbox('displayed_author_name', LangLoader::get_message('config.author_displayed', 'admin-common'), $this->get_work()->get_displayed_author_name()));

		$other_fieldset->add_field(new FormFieldUploadPictureFile('thumbnail', $this->common_lang['form.picture'], $this->get_work()->get_thumbnail()->relative()));

		$other_fieldset->add_field(PortfolioService::get_keywords_manager()->get_form_field($this->get_work()->get_id(), 'keywords', $this->common_lang['form.keywords'],
			array('description' => $this->common_lang['form.keywords.description'])
		));

		$other_fieldset->add_field(new PortfolioFormFieldSelectSources('sources', $this->common_lang['form.sources'], $this->get_work()->get_sources()));

		$other_fieldset->add_field(new PortfolioFormFieldCarousel('carousel', $this->lang['portfolio.form.carousel'], $this->get_work()->get_carousel()));

		if (PortfolioAuthorizationsService::check_authorizations($this->get_work()->get_category_id())->moderation())
		{
			$publication_fieldset = new FormFieldsetHTML('publication', $this->common_lang['form.approbation']);
			$form->add_fieldset($publication_fieldset);

			$publication_fieldset->add_field(new FormFieldDateTime('creation_date', $this->common_lang['form.date.creation'], $this->get_work()->get_creation_date(),
				array('required' => true)
			));

			if (!$this->get_work()->is_published())
			{
				$publication_fieldset->add_field(new FormFieldCheckbox('update_creation_date', $this->common_lang['form.update.date.creation'], false, array('hidden' => $this->get_work()->get_status() != Work::NOT_PUBLISHED)
				));
			}

			$publication_fieldset->add_field(new FormFieldSimpleSelectChoice('publication_state', $this->common_lang['form.approbation'], $this->get_work()->get_publication_state(),
				array(
					new FormFieldSelectChoiceOption($this->common_lang['form.approbation.not'], Work::NOT_PUBLISHED),
					new FormFieldSelectChoiceOption($this->common_lang['form.approbation.now'], Work::PUBLISHED_NOW),
					new FormFieldSelectChoiceOption($this->common_lang['status.approved.date'], Work::PUBLICATION_DATE),
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
				($this->get_work()->get_publication_start_date() === null ? new Date() : $this->get_work()->get_publication_start_date()),
				array('hidden' => ($this->get_work()->get_publication_state() != Work::PUBLICATION_DATE))
			));

			$publication_fieldset->add_field(new FormFieldCheckbox('end_date_enable', $this->common_lang['form.date.end.enable'], $this->get_work()->enabled_end_date(),
				array('hidden' => ($this->get_work()->get_publication_state() != Work::PUBLICATION_DATE),
					'events' => array('click' => '
						if (HTMLForms.getField("end_date_enable").getValue()) {
							HTMLForms.getField("publication_end_date").enable();
						} else {
							HTMLForms.getField("publication_end_date").disable();
						}'
				))
			));

			$publication_fieldset->add_field(new FormFieldDateTime('publication_end_date', $this->common_lang['form.date.end'],
				($this->get_work()->get_publication_end_date() === null ? new date() : $this->get_work()->get_publication_end_date()),
				array('hidden' => !$this->get_work()->enabled_end_date())
			));
		}

		$this->build_contribution_fieldset($form);

		$fieldset->add_field(new FormFieldHidden('referrer', $request->get_url_referrer()));

		$this->submit_button = new FormButtonDefaultSubmit();
		$form->add_button($this->submit_button);
		$form->add_button(new FormButtonReset());

		$this->form = $form;

		// Positionnement à la bonne page quand on édite un item avec plusieurs pages
		if ($this->get_work()->get_id() !== null)
		{
			$current_page = $request->get_getstring('page', '');

			$this->tpl->put('C_PAGE', !empty($current_page));

			if (!empty($current_page))
			{
				$work_contents = $this->work->get_contents();

				//If item doesn't begin with a page, we insert one
				if (TextHelper::substr(trim($work_contents), 0, 6) != '[page]')
				{
					$work_contents = '[page]&nbsp;[/page]' . $work_contents;
				}

				//Removing [page] bbcode
				$work_contents_clean = preg_split('`\[page\].+\[/page\](.*)`usU', $work_contents, -1, PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY);

				//Retrieving pages
				preg_match_all('`\[page\]([^[]+)\[/page\]`uU', $work_contents, $array_page);

				$page_name = (isset($array_page[1][$current_page-1]) && $array_page[1][$current_page-1] != '&nbsp;') ? $array_page[1][($current_page-1)] : '';

				$this->tpl->put('PAGE', TextHelper::to_js_string($page_name));
			}
		}
	}

	private function build_contribution_fieldset($form)
	{
		if ($this->get_work()->get_id() === null && $this->is_contributor_member())
		{
			$fieldset = new FormFieldsetHTML('contribution', LangLoader::get_message('contribution', 'user-common'));
			$fieldset->set_description(MessageHelper::display(LangLoader::get_message('contribution.explain', 'user-common'), MessageHelper::WARNING)->render());
			$form->add_fieldset($fieldset);

			$fieldset->add_field(new FormFieldRichTextEditor('contribution_description', LangLoader::get_message('contribution.description', 'user-common'), '',
				array('description' => LangLoader::get_message('contribution.description.explain', 'user-common'))));
		}
	}

	private function is_contributor_member()
	{
		return (!PortfolioAuthorizationsService::check_authorizations()->write() && PortfolioAuthorizationsService::check_authorizations()->contribution());
	}

	private function get_work()
	{
		if ($this->work === null)
		{
			$id = AppContext::get_request()->get_getint('id', 0);
			if (!empty($id))
			{
				try
				{
					$this->work = PortfolioService::get_work('WHERE portfolio.id=:id', array('id' => $id));
				}
				catch(RowNotFoundException $e)
				{
					$error_controller = PHPBoostErrors::unexisting_page();
					DispatchManager::redirect($error_controller);
				}
			}
			else
			{
				$this->is_new_work = true;
				$this->work = new Work();
				$this->work->init_default_properties(AppContext::get_request()->get_getint('category_id', Category::ROOT_CATEGORY));
			}
		}
		return $this->work;
	}

	private function check_authorizations()
	{
		$work = $this->get_work();

		if ($work->get_id() === null)
		{
			if (!$work->is_authorized_to_add())
			{
				$error_controller = PHPBoostErrors::user_not_authorized();
				DispatchManager::redirect($error_controller);
			}
		}
		else
		{
			if (!$work->is_authorized_to_edit())
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
		$work = $this->get_work();

		$work->set_title($this->form->get_value('title'));

		if (PortfolioService::get_categories_manager()->get_categories_cache()->has_categories())
			$work->set_category_id($this->form->get_value('category_id')->get_raw_value());

		$work->set_description(($this->form->get_value('enable_description') ? $this->form->get_value('description') : ''));
		$work->set_contents($this->form->get_value('contents'));

		$links_visibility = $this->form->get_value('links_visibility') ? $this->form->get_value('links_visibility') : Work::DISABLE_LINKS_VISIBILITY;
		$work->set_links_visibility($links_visibility);

		$work->set_website_url(new Url($this->form->get_value('website_url')));
		
		$work->set_file_url(new Url($this->form->get_value('file_url')));
		$file_size = (empty($file_size) && $work->get_file_size()) ? $work->get_file_size() : $file_size;
		$work->set_file_size($file_size);

		if ($work->get_id() !== null && $work->get_downloads_number() > 0 && $this->form->get_value('reset_downloads_number'))
			$work->set_downloads_number(0);

		$displayed_author_name = $this->form->get_value('displayed_author_name') ? $this->form->get_value('displayed_author_name') : Work::NOTDISPLAYED_AUTHOR_NAME;
		$work->set_displayed_author_name($displayed_author_name);
		$work->set_thumbnail(new Url($this->form->get_value('thumbnail')));

		if ($this->get_work()->get_displayed_author_name() == true)
			$work->set_custom_author_name(($this->form->get_value('custom_author_name') && $this->form->get_value('custom_author_name') !== $work->get_author_user()->get_display_name() ? $this->form->get_value('custom_author_name') : ''));

		$work->set_sources($this->form->get_value('sources'));
		$work->set_carousel($this->form->get_value('carousel'));

		if (!PortfolioAuthorizationsService::check_authorizations($work->get_category_id())->moderation())
		{
			if ($work->get_id() === null)
				$work->set_creation_date(new Date());

			$work->set_rewrited_title(Url::encode_rewrite($work->get_title()));
			$work->clean_publication_start_and_end_date();

			if (PortfolioAuthorizationsService::check_authorizations($work->get_category_id())->contribution() && !PortfolioAuthorizationsService::check_authorizations($work->get_category_id())->write())
				$work->set_publication_state(Work::NOT_PUBLISHED);
		}
		else
		{
			if ($this->form->get_value('update_creation_date'))
			{
				$work->set_creation_date(new Date());
			}
			else
			{
				$work->set_creation_date($this->form->get_value('creation_date'));
			}

			$rewrited_title = $this->form->get_value('rewrited_title', '');
			$rewrited_title = $this->form->get_value('personalize_rewrited_title') && !empty($rewrited_title) ? $rewrited_title : Url::encode_rewrite($work->get_title());
			$work->set_rewrited_title($rewrited_title);

			$work->set_publication_state($this->form->get_value('publication_state')->get_raw_value());
			if ($work->get_publication_state() == Work::PUBLICATION_DATE)
			{
				$config = PortfolioConfig::load();
				$deferred_operations = $config->get_deferred_operations();

				$old_start_date = $work->get_publication_start_date();
				$start_date = $this->form->get_value('publication_start_date');
				$work->set_publication_start_date($start_date);

				if ($old_start_date !== null && $old_start_date->get_timestamp() != $start_date->get_timestamp() && in_array($old_start_date->get_timestamp(), $deferred_operations))
				{
					$key = array_search($old_start_date->get_timestamp(), $deferred_operations);
					unset($deferred_operations[$key]);
				}

				if (!in_array($start_date->get_timestamp(), $deferred_operations))
					$deferred_operations[] = $start_date->get_timestamp();

				if ($this->form->get_value('end_date_enable'))
				{
					$old_end_date = $work->get_publication_end_date();
					$end_date = $this->form->get_value('publication_end_date');
					$work->set_publication_end_date($end_date);

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
					$work->clean_publication_end_date();
				}

				$config->set_deferred_operations($deferred_operations);
				PortfolioConfig::save();
			}
			else
			{
				$work->clean_publication_start_and_end_date();
			}
		}

		if ($work->get_id() === null)
		{
			$work->set_author_user(AppContext::get_current_user());
			$id_work = PortfolioService::add($work);
		}
		else
		{
			$now = new Date();
			$work->set_updated_date($now);
			$id_work = $work->get_id();
			PortfolioService::update($work);
		}

		$this->contribution_actions($work, $id_work);

		PortfolioService::get_keywords_manager()->put_relations($id_work, $this->form->get_value('keywords'));

		Feed::clear_cache('portfolio');
		PortfolioCategoriesCache::invalidate();
	}

	private function contribution_actions(Work $work, $id_work)
	{
		if ($work->get_id() === null)
		{
			if ($this->is_contributor_member())
			{
				$contribution = new Contribution();
				$contribution->set_id_in_module($id_work);
				$contribution->set_description(stripslashes($this->form->get_value('contribution_description')));
				$contribution->set_entitled($work->get_title());
				$contribution->set_fixing_url(PortfolioUrlBuilder::edit_item($id_work)->relative());
				$contribution->set_poster_id(AppContext::get_current_user()->get_id());
				$contribution->set_module('portfolio');
				$contribution->set_auth(
					Authorizations::capture_and_shift_bit_auth(
						PortfolioService::get_categories_manager()->get_heritated_authorizations($work->get_category_id(), Category::MODERATION_AUTHORIZATIONS, Authorizations::AUTH_CHILD_PRIORITY),
						Category::MODERATION_AUTHORIZATIONS, Contribution::CONTRIBUTION_AUTH_BIT
					)
				);
				ContributionService::save_contribution($contribution);
			}
		}
		else
		{
			$corresponding_contributions = ContributionService::find_by_criteria('portfolio', $id_work);
			if (count($corresponding_contributions) > 0)
			{
				foreach ($corresponding_contributions as $contribution)
				{
					$contribution->set_status(Event::EVENT_STATUS_PROCESSED);
					ContributionService::save_contribution($contribution);
				}
			}
		}
		$work->set_id($id_work);
	}

	private function redirect()
	{
		$work = $this->get_work();
		$category = $work->get_category();

		if ($this->is_new_work && $this->is_contributor_member() && !$work->is_published())
		{
			DispatchManager::redirect(new UserContributionSuccessController());
		}
		elseif ($work->is_published())
		{
			if ($this->is_new_work)
				AppContext::get_response()->redirect(PortfolioUrlBuilder::display_item($category->get_id(), $category->get_rewrited_name(), $work->get_id(), $work->get_rewrited_title(), AppContext::get_request()->get_getint('page', 1)), StringVars::replace_vars($this->lang['portfolio.message.success.add'], array('title' => $work->get_title())));
			else
				AppContext::get_response()->redirect(($this->form->get_value('referrer') ? $this->form->get_value('referrer') : PortfolioUrlBuilder::display_item($category->get_id(), $category->get_rewrited_name(), $work->get_id(), $work->get_rewrited_title(), AppContext::get_request()->get_getint('page', 1))), StringVars::replace_vars($this->lang['portfolio.message.success.edit'], array('title' => $work->get_title())));
		}
		else
		{
			if ($this->is_new_work)
				AppContext::get_response()->redirect(PortfolioUrlBuilder::display_pending_items(), StringVars::replace_vars($this->lang['portfolio.message.success.add'], array('title' => $work->get_title())));
			else
				AppContext::get_response()->redirect(($this->form->get_value('referrer') ? $this->form->get_value('referrer') : PortfolioUrlBuilder::display_pending_items()), StringVars::replace_vars($this->lang['portfolio.message.success.edit'], array('title' => $work->get_title())));
		}
	}

	private function build_response(View $tpl)
	{
		$work = $this->get_work();

		$response = new SiteDisplayResponse($tpl);
		$graphical_environment = $response->get_graphical_environment();

		$breadcrumb = $graphical_environment->get_breadcrumb();
		$breadcrumb->add($this->lang['portfolio.module.title'], PortfolioUrlBuilder::home());

		if ($work->get_id() === null)
		{
			$breadcrumb->add($this->lang['portfolio.add'], PortfolioUrlBuilder::add_item($work->get_category_id()));
			$graphical_environment->set_page_title($this->lang['portfolio.add'], $this->lang['portfolio.module.title']);
			$graphical_environment->get_seo_meta_data()->set_description($this->lang['portfolio.add']);
			$graphical_environment->get_seo_meta_data()->set_canonical_url(PortfolioUrlBuilder::add_item($work->get_category_id()));
		}
		else
		{
			$categories = array_reverse(PortfolioService::get_categories_manager()->get_parents($work->get_category_id(), true));
			foreach ($categories as $id => $category)
			{
				if ($category->get_id() != Category::ROOT_CATEGORY)
					$breadcrumb->add($category->get_name(), PortfolioUrlBuilder::display_category($category->get_id(), $category->get_rewrited_name()));
			}
			$breadcrumb->add($work->get_title(), PortfolioUrlBuilder::display_item($category->get_id(), $category->get_rewrited_name(), $work->get_id(), $work->get_rewrited_title()));

			$breadcrumb->add($this->lang['portfolio.edit'], PortfolioUrlBuilder::edit_item($work->get_id()));
			$graphical_environment->set_page_title($this->lang['portfolio.edit'], $this->lang['portfolio.module.title']);
			$graphical_environment->get_seo_meta_data()->set_description($this->lang['portfolio.edit']);
			$graphical_environment->get_seo_meta_data()->set_canonical_url(PortfolioUrlBuilder::edit_item($work->get_id()));
		}

		return $response;
	}
}
?>
