<?php
/*##################################################
 *                       WikiFormController.class.php
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

class WikiFormController extends ModuleController
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

	private $document;
	private $is_new_document;

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
		$this->lang = LangLoader::get('common', 'wiki');
		$this->tpl = new FileTemplate('wiki/WikiFormController.tpl');
		$this->tpl->add_lang($this->lang);
		$this->common_lang = LangLoader::get('common');
	}

	private function build_form(HTTPRequestCustom $request)
	{
		$form = new HTMLForm(__CLASS__);

		$fieldset = new FormFieldsetHTML('document', $this->get_document()->get_id() === null ? $this->lang['wiki.add'] : $this->lang['wiki.edit']);
		$form->add_fieldset($fieldset);

		$fieldset->add_field(new FormFieldTextEditor('title', $this->common_lang['form.title'], $this->get_document()->get_title(),
			array('required' => true)
		));

		if (WikiAuthorizationsService::check_authorizations($this->get_document()->get_id_category())->moderation())
		{
			$fieldset->add_field(new FormFieldCheckbox('personalize_rewrited_title', $this->common_lang['form.rewrited_name.personalize'], $this->get_document()->rewrited_title_is_personalized(),
				array('events' => array('click' =>'
					if (HTMLForms.getField("personalize_rewrited_title").getValue()) {
						HTMLForms.getField("rewrited_title").enable();
					} else {
						HTMLForms.getField("rewrited_title").disable();
					}'
				))
			));

			$fieldset->add_field(new FormFieldTextEditor('rewrited_title', $this->common_lang['form.rewrited_name'], $this->get_document()->get_rewrited_title(),
				array('description' => $this->common_lang['form.rewrited_name.description'],
				      'hidden' => !$this->get_document()->rewrited_title_is_personalized()),
				array(new FormFieldConstraintRegex('`^[a-z0-9\-]+$`iu'))
			));
		}

		if (WikiService::get_categories_manager()->get_categories_cache()->has_categories())
		{
			$search_category_children_options = new SearchCategoryChildrensOptions();
			$search_category_children_options->add_authorizations_bits(Category::CONTRIBUTION_AUTHORIZATIONS);
			$search_category_children_options->add_authorizations_bits(Category::WRITE_AUTHORIZATIONS);
			$fieldset->add_field(WikiService::get_categories_manager()->get_select_categories_form_field('id_category', $this->common_lang['form.category'], $this->get_document()->get_id_category(), $search_category_children_options));
		}

		$fieldset->add_field(new FormFieldCheckbox('enable_description', $this->lang['wiki.form.description_enabled'], $this->get_document()->get_description_enabled(),
			array('description' => StringVars::replace_vars($this->lang['wiki.form.description_enabled.description'],
			array('number' => WikiConfig::load()->get_number_character_to_cut())),
				'events' => array('click' => '
					if (HTMLForms.getField("enable_description").getValue()) {
						HTMLForms.getField("description").enable();
					} else {
						HTMLForms.getField("description").disable();
					}'
		))));

		$fieldset->add_field(new FormFieldRichTextEditor('description', StringVars::replace_vars($this->lang['wiki.form.description'],
			array('number' =>WikiConfig::load()->get_number_character_to_cut())), $this->get_document()->get_description(),
			array('rows' => 3, 'hidden' => !$this->get_document()->get_description_enabled())
		));

		$fieldset->add_field(new FormFieldRichTextEditor('contents', $this->common_lang['form.contents'], $this->get_document()->get_contents(),
			array('rows' => 15, 'required' => true)
		));

		$fieldset->add_field(new FormFieldActionLink('add_page', $this->lang['wiki.form.add_page'] , 'javascript:bbcode_page();', 'fa-pagebreak'));

		if ($this->get_document()->get_author_name_displayed() == true)
		{
			$fieldset->add_field(new FormFieldCheckbox('author_custom_name_enabled', $this->common_lang['form.author_custom_name_enabled'], $this->get_document()->is_author_custom_name_enabled(),
				array('events' => array('click' => '
				if (HTMLForms.getField("author_custom_name_enabled").getValue()) {
					HTMLForms.getField("author_custom_name").enable();
				} else {
					HTMLForms.getField("author_custom_name").disable();
				}'))
			));

			$fieldset->add_field(new FormFieldTextEditor('author_custom_name', $this->common_lang['form.author_custom_name'], $this->get_document()->get_author_custom_name(), array(
				'hidden' => !$this->get_document()->is_author_custom_name_enabled(),
			)));
		}

		$other_fieldset = new FormFieldsetHTML('other', $this->common_lang['form.other']);
		$form->add_fieldset($other_fieldset);

		$other_fieldset->add_field(new FormFieldCheckbox('author_name_displayed', LangLoader::get_message('config.author_displayed', 'admin-common'), $this->get_document()->get_author_name_displayed()));

		$other_fieldset->add_field(new FormFieldUploadPictureFile('thumbnail', $this->lang['wiki.form.thumbnail'], $this->get_document()->get_thumbnail()->relative()));

		$other_fieldset->add_field(WikiService::get_keywords_manager()->get_form_field($this->get_document()->get_id(), 'keywords', $this->common_lang['form.keywords'],
			array('description' => $this->common_lang['form.keywords.description'])
		));

		if (WikiAuthorizationsService::check_authorizations($this->get_document()->get_id_category())->moderation())
		{
			$publication_fieldset = new FormFieldsetHTML('publication', $this->common_lang['form.approbation']);
			$form->add_fieldset($publication_fieldset);

			$publication_fieldset->add_field(new FormFieldDateTime('date_created', $this->common_lang['form.date.creation'], $this->get_document()->get_date_created(),
				array('required' => true)
			));

			if (!$this->get_document()->is_published())
			{
				$publication_fieldset->add_field(new FormFieldCheckbox('update_creation_date', $this->common_lang['form.update.date.creation'], false, array('hidden' => $this->get_document()->get_status() != Document::NOT_PUBLISHED)
				));
			}

			$publication_fieldset->add_field(new FormFieldSimpleSelectChoice('publishing_state', $this->common_lang['form.approbation'], $this->get_document()->get_publishing_state(),
				array(
					new FormFieldSelectChoiceOption($this->common_lang['form.approbation.not'], Document::NOT_PUBLISHED),
					new FormFieldSelectChoiceOption($this->common_lang['form.approbation.now'], Document::PUBLISHED_NOW),
					new FormFieldSelectChoiceOption($this->common_lang['status.approved.date'], Document::PUBLISHED_DATE),
				),
				array('events' => array('change' => '
				if (HTMLForms.getField("publishing_state").getValue() == 2) {
					jQuery("#' . __CLASS__ . '_publishing_start_date_field").show();
					HTMLForms.getField("end_date_enable").enable();
				} else {
					jQuery("#' . __CLASS__ . '_publishing_start_date_field").hide();
					HTMLForms.getField("end_date_enable").disable();
				}'))
			));

			$publication_fieldset->add_field(new FormFieldDateTime('publishing_start_date', $this->common_lang['form.date.start'],
				($this->get_document()->get_publishing_start_date() === null ? new Date() : $this->get_document()->get_publishing_start_date()),
				array('hidden' => ($this->get_document()->get_publishing_state() != Document::PUBLISHED_DATE))
			));

			$publication_fieldset->add_field(new FormFieldCheckbox('end_date_enable', $this->common_lang['form.date.end.enable'], $this->get_document()->end_date_enabled(),
				array('hidden' => ($this->get_document()->get_publishing_state() != Document::PUBLISHED_DATE),
					'events' => array('click' => '
						if (HTMLForms.getField("end_date_enable").getValue()) {
							HTMLForms.getField("publishing_end_date").enable();
						} else {
							HTMLForms.getField("publishing_end_date").disable();
						}'
				))
			));

			$publication_fieldset->add_field(new FormFieldDateTime('publishing_end_date', $this->common_lang['form.date.end'],
				($this->get_document()->get_publishing_end_date() === null ? new date() : $this->get_document()->get_publishing_end_date()),
				array('hidden' => !$this->get_document()->end_date_enabled())
			));
		}

		$this->build_contribution_fieldset($form);

		$fieldset->add_field(new FormFieldHidden('referrer', $request->get_url_referrer()));

		$this->submit_button = new FormButtonDefaultSubmit();
		$form->add_button($this->submit_button);
		$form->add_button(new FormButtonReset());

		$this->form = $form;

		// Positionnement à la bonne page quand on édite un document avec plusieurs pages
		if ($this->get_document()->get_id() !== null)
		{
			$current_page = $request->get_getstring('page', '');

			$this->tpl->put('C_PAGE', !empty($current_page));

			if (!empty($current_page))
			{
				$document_contents = $this->document->get_contents();

				//If document doesn't begin with a page, we insert one
				if (TextHelper::substr(trim($document_contents), 0, 6) != '[page]')
				{
					$document_contents = '[page]&nbsp;[/page]' . $document_contents;
				}

				//Removing [page] bbcode
				$document_contents_clean = preg_split('`\[page\].+\[/page\](.*)`usU', $document_contents, -1, PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY);

				//Retrieving pages
				preg_match_all('`\[page\]([^[]+)\[/page\]`uU', $document_contents, $array_page);

				$page_name = (isset($array_page[1][$current_page-1]) && $array_page[1][$current_page-1] != '&nbsp;') ? $array_page[1][($current_page-1)] : '';

				$this->tpl->put('PAGE', TextHelper::to_js_string($page_name));
			}
		}
	}

	private function build_contribution_fieldset($form)
	{
		if ($this->get_document()->get_id() === null && $this->is_contributor_member())
		{
			$fieldset = new FormFieldsetHTML('contribution', LangLoader::get_message('contribution', 'user-common'));
			$fieldset->set_description(MessageHelper::display(LangLoader::get_message('contribution.explain', 'user-common'), MessageHelper::WARNING)->render());
			$form->add_fieldset($fieldset);

			$fieldset->add_field(new FormFieldRichTextEditor('contribution_description', LangLoader::get_message('contribution.description', 'user-common'), '',
				array('description' => LangLoader::get_message('contribution.description.explain', 'user-common'))));
		}
		elseif ($this->get_document()->is_published() && $this->get_document()->is_authorized_to_edit() && !AppContext::get_current_user()->check_level(User::ADMIN_LEVEL))
		{
			$fieldset = new FormFieldsetHTML('member_edition', LangLoader::get_message('wiki.form.member.edition', 'common', 'wiki'));
			$fieldset->set_description(MessageHelper::display(LangLoader::get_message('wiki.form.member.edition.explain', 'common', 'wiki'), MessageHelper::WARNING)->render());
			$form->add_fieldset($fieldset);

			$fieldset->add_field(new FormFieldRichTextEditor('edittion_description', LangLoader::get_message('wiki.form.member.edition.description', 'common', 'wiki'), '',
				array('description' => LangLoader::get_message('wiki.form.member.edition.description.desc', 'common', 'wiki'))
			));
		}
	}

	private function is_contributor_member()
	{
		return (!WikiAuthorizationsService::check_authorizations()->write() && WikiAuthorizationsService::check_authorizations()->contribution());
	}

	private function get_document()
	{
		if ($this->document === null)
		{
			$id = AppContext::get_request()->get_getint('id', 0);
			if (!empty($id))
			{
				try
				{
					$this->document = WikiService::get_document('WHERE wiki.id=:id', array('id' => $id));
				}
				catch(RowNotFoundException $e)
				{
					$error_controller = PHPBoostErrors::unexisting_page();
					DispatchManager::redirect($error_controller);
				}
			}
			else
			{
				$this->is_new_document = true;
				$this->document = new Document();
				$this->document->init_default_properties(AppContext::get_request()->get_getint('id_category', Category::ROOT_CATEGORY));
			}
		}
		return $this->document;
	}

	private function check_authorizations()
	{
		$document = $this->get_document();

		if ($document->get_id() === null)
		{
			if (!$document->is_authorized_to_add())
			{
				$error_controller = PHPBoostErrors::user_not_authorized();
				DispatchManager::redirect($error_controller);
			}
		}
		else
		{
			if (!$document->is_authorized_to_edit())
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
		$document = $this->get_document();

		$document->set_title($this->form->get_value('title'));

		if (WikiService::get_categories_manager()->get_categories_cache()->has_categories())
			$document->set_id_category($this->form->get_value('id_category')->get_raw_value());

		if ($document->get_order_id() === null)
		{
			$document_nb = WikiService::count('WHERE id_category = :id_category', array('id_category' => $document->get_id_category()));
			$document->set_order_id($document_nb + 1);
		}

		$document->set_description(($this->form->get_value('enable_description') ? $this->form->get_value('description') : ''));
		$document->set_contents($this->form->get_value('contents'));

		$author_name_displayed = $this->form->get_value('author_name_displayed') ? $this->form->get_value('author_name_displayed') : Document::AUTHOR_NAME_NOTDISPLAYED;
		$document->set_author_name_displayed($author_name_displayed);
		$document->set_thumbnail(new Url($this->form->get_value('thumbnail')));

		if ($this->get_document()->get_author_name_displayed() == true)
			$document->set_author_custom_name(($this->form->get_value('author_custom_name') && $this->form->get_value('author_custom_name') !== $document->get_author_user()->get_display_name() ? $this->form->get_value('author_custom_name') : ''));

		if (!WikiAuthorizationsService::check_authorizations($document->get_id_category())->moderation())
		{
			if ($document->get_id() === null)
				$document->set_date_created(new Date());

			$document->set_rewrited_title(Url::encode_rewrite($document->get_title()));
			$document->clean_publishing_start_and_end_date();

			if (WikiAuthorizationsService::check_authorizations($document->get_id_category())->contribution() && !WikiAuthorizationsService::check_authorizations($document->get_id_category())->write())
				$document->set_publishing_state(Document::NOT_PUBLISHED);
		}
		else
		{
			if ($this->form->get_value('update_creation_date'))
			{
				$document->set_date_created(new Date());
			}
			else
			{
				$document->set_date_created($this->form->get_value('date_created'));
			}

			$rewrited_title = $this->form->get_value('rewrited_title', '');
			$rewrited_title = $this->form->get_value('personalize_rewrited_title') && !empty($rewrited_title) ? $rewrited_title : Url::encode_rewrite($document->get_title());
			$document->set_rewrited_title($rewrited_title);

			$document->set_publishing_state($this->form->get_value('publishing_state')->get_raw_value());
			if ($document->get_publishing_state() == Document::PUBLISHED_DATE)
			{
				$config = WikiConfig::load();
				$deferred_operations = $config->get_deferred_operations();

				$old_start_date = $document->get_publishing_start_date();
				$start_date = $this->form->get_value('publishing_start_date');
				$document->set_publishing_start_date($start_date);

				if ($old_start_date !== null && $old_start_date->get_timestamp() != $start_date->get_timestamp() && in_array($old_start_date->get_timestamp(), $deferred_operations))
				{
					$key = array_search($old_start_date->get_timestamp(), $deferred_operations);
					unset($deferred_operations[$key]);
				}

				if (!in_array($start_date->get_timestamp(), $deferred_operations))
					$deferred_operations[] = $start_date->get_timestamp();

				if ($this->form->get_value('end_date_enable'))
				{
					$old_end_date = $document->get_publishing_end_date();
					$end_date = $this->form->get_value('publishing_end_date');
					$document->set_publishing_end_date($end_date);

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
					$document->clean_publishing_end_date();
				}

				$config->set_deferred_operations($deferred_operations);
				WikiConfig::save();
			}
			else
			{
				$document->clean_publishing_start_and_end_date();
			}
		}

		if ($document->get_id() === null)
		{
			$document->set_author_user(AppContext::get_current_user());
			$id_document = WikiService::add($document);
		}
		else
		{
			$now = new Date();
			$document->set_date_updated($now);
			$id_document = $document->get_id();
			WikiService::update($document);
		}

		$this->contribution_actions($document, $id_document);

		WikiService::get_keywords_manager()->put_relations($id_document, $this->form->get_value('keywords'));

		Feed::clear_cache('wiki');
		WikiCategoriesCache::invalidate();
	}

	private function contribution_actions(Document $document, $id_document)
	{
		if ($this->is_contributor_member())
		{
			$contribution = new Contribution();
			$contribution->set_id_in_module($id_document);
			if ($document->get_id() === null)
				$contribution->set_description(stripslashes($this->form->get_value('contribution_description')));
			else
				$contribution->set_description(stripslashes($this->form->get_value('edittion_description')));

			$contribution->set_entitled($document->get_title());
			$contribution->set_fixing_url(WikiUrlBuilder::edit_item($id_document)->relative());
			$contribution->set_poster_id(AppContext::get_current_user()->get_id());
			$contribution->set_module('wiki');
			$contribution->set_auth(
				Authorizations::capture_and_shift_bit_auth(
					WikiService::get_categories_manager()->get_heritated_authorizations($document->get_id_category(), Category::MODERATION_AUTHORIZATIONS, Authorizations::AUTH_CHILD_PRIORITY),
					Category::MODERATION_AUTHORIZATIONS, Contribution::CONTRIBUTION_AUTH_BIT
				)
			);
			ContributionService::save_contribution($contribution);
		}
		else
		{
			$corresponding_contributions = ContributionService::find_by_criteria('wiki', $id_document);
			if (count($corresponding_contributions) > 0)
			{
				foreach ($corresponding_contributions as $contribution)
				{
					$contribution->set_status(Event::EVENT_STATUS_PROCESSED);
					ContributionService::save_contribution($contribution);
				}
			}
		}
		$document->set_id($id_document);
	}

	private function redirect()
	{
		$document = $this->get_document();
		$category = $document->get_category();

		if ($this->is_new_document && $this->is_contributor_member() && !$document->is_published())
		{
			DispatchManager::redirect(new UserContributionSuccessController());
		}
		elseif ($document->is_published())
		{
			if ($this->is_new_document)
				AppContext::get_response()->redirect(WikiUrlBuilder::display_item($category->get_id(), $category->get_rewrited_name(), $document->get_id(), $document->get_rewrited_title(), AppContext::get_request()->get_getint('page', 1)), StringVars::replace_vars($this->lang['wiki.message.success.add'], array('title' => $document->get_title())));
			else
				AppContext::get_response()->redirect(($this->form->get_value('referrer') ? $this->form->get_value('referrer') : WikiUrlBuilder::display_item($category->get_id(), $category->get_rewrited_name(), $document->get_id(), $document->get_rewrited_title(), AppContext::get_request()->get_getint('page', 1))), StringVars::replace_vars($this->lang['wiki.message.success.edit'], array('title' => $document->get_title())));
		}
		else
		{
			if ($this->is_new_document)
				AppContext::get_response()->redirect(WikiUrlBuilder::display_pending_items(), StringVars::replace_vars($this->lang['wiki.message.success.add'], array('title' => $document->get_title())));
			else
				AppContext::get_response()->redirect(($this->form->get_value('referrer') ? $this->form->get_value('referrer') : WikiUrlBuilder::display_pending_items()), StringVars::replace_vars($this->lang['wiki.message.success.edit'], array('title' => $document->get_title())));
		}
	}

	private function build_response(View $tpl)
	{
		$document = $this->get_document();

		$response = new SiteDisplayResponse($tpl);
		$graphical_environment = $response->get_graphical_environment();

		$breadcrumb = $graphical_environment->get_breadcrumb();
		$breadcrumb->add($this->lang['module.title'], WikiUrlBuilder::home());

		if ($document->get_id() === null)
		{
			$breadcrumb->add($this->lang['wiki.add'], WikiUrlBuilder::add_item($document->get_id_category()));
			$graphical_environment->set_page_title($this->lang['wiki.add'], $this->lang['module.title']);
			$graphical_environment->get_seo_meta_data()->set_description($this->lang['wiki.add']);
			$graphical_environment->get_seo_meta_data()->set_canonical_url(WikiUrlBuilder::add_item($document->get_id_category()));
		}
		else
		{
			$categories = array_reverse(WikiService::get_categories_manager()->get_parents($document->get_id_category(), true));
			foreach ($categories as $id => $category)
			{
				if ($category->get_id() != Category::ROOT_CATEGORY)
					$breadcrumb->add($category->get_name(), WikiUrlBuilder::display_category($category->get_id(), $category->get_rewrited_name()));
			}
			$breadcrumb->add($document->get_title(), WikiUrlBuilder::display_item($category->get_id(), $category->get_rewrited_name(), $document->get_id(), $document->get_rewrited_title()));

			$breadcrumb->add($this->lang['wiki.edit'], WikiUrlBuilder::edit_item($document->get_id()));
			$graphical_environment->set_page_title($this->lang['wiki.edit'], $this->lang['module.title']);
			$graphical_environment->get_seo_meta_data()->set_description($this->lang['wiki.edit']);
			$graphical_environment->get_seo_meta_data()->set_canonical_url(WikiUrlBuilder::edit_item($document->get_id()));
		}

		return $response;
	}
}
?>
