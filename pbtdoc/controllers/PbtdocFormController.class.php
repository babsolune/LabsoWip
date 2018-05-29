<?php
/*##################################################
 *                       PbtdocFormController.class.php
 *                            -------------------
 *   begin                : February 27, 2013
 *   copyright            : (C) 2013 Patrick DUBEAU
 *   email                : daaxwizeman@gmail.com
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
 * @author Patrick DUBEAU <daaxwizeman@gmail.com>
 */
class PbtdocFormController extends ModuleController
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

	private $course;
	private $is_new_course;

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
		$this->lang = LangLoader::get('common', 'pbtdoc');
		$this->tpl = new FileTemplate('pbtdoc/PbtdocFormController.tpl');
		$this->tpl->add_lang($this->lang);
		$this->common_lang = LangLoader::get('common');
	}

	private function build_form(HTTPRequestCustom $request)
	{
		$form = new HTMLForm(__CLASS__);

		$fieldset = new FormFieldsetHTML('course', $this->get_course()->get_id() === null ? $this->lang['pbtdoc.add'] : $this->lang['pbtdoc.edit']);
		$form->add_fieldset($fieldset);

		$fieldset->add_field(new FormFieldTextEditor('title', $this->common_lang['form.title'], $this->get_course()->get_title(),
			array('required' => true)
		));

		if (PbtdocAuthorizationsService::check_authorizations($this->get_course()->get_id_category())->moderation())
		{
			$fieldset->add_field(new FormFieldCheckbox('personalize_rewrited_title', $this->common_lang['form.rewrited_name.personalize'], $this->get_course()->rewrited_title_is_personalized(),
				array('events' => array('click' =>'
					if (HTMLForms.getField("personalize_rewrited_title").getValue()) {
						HTMLForms.getField("rewrited_title").enable();
					} else {
						HTMLForms.getField("rewrited_title").disable();
					}'
				))
			));

			$fieldset->add_field(new FormFieldTextEditor('rewrited_title', $this->common_lang['form.rewrited_name'], $this->get_course()->get_rewrited_title(),
				array('description' => $this->common_lang['form.rewrited_name.description'],
				      'hidden' => !$this->get_course()->rewrited_title_is_personalized()),
				array(new FormFieldConstraintRegex('`^[a-z0-9\-]+$`iu'))
			));
		}

		if (PbtdocService::get_categories_manager()->get_categories_cache()->has_categories())
		{
			$search_category_children_options = new SearchCategoryChildrensOptions();
			$search_category_children_options->add_authorizations_bits(Category::CONTRIBUTION_AUTHORIZATIONS);
			$search_category_children_options->add_authorizations_bits(Category::WRITE_AUTHORIZATIONS);
			$fieldset->add_field(PbtdocService::get_categories_manager()->get_select_categories_form_field('id_category', $this->common_lang['form.category'], $this->get_course()->get_id_category(), $search_category_children_options));
		}

		$fieldset->add_field(new FormFieldCheckbox('enable_description', $this->lang['pbtdoc.form.description_enabled'], $this->get_course()->get_description_enabled(),
			array('description' => StringVars::replace_vars($this->lang['pbtdoc.form.description_enabled.description'],
			array('number' => PbtdocConfig::load()->get_number_character_to_cut())),
				'events' => array('click' => '
					if (HTMLForms.getField("enable_description").getValue()) {
						HTMLForms.getField("description").enable();
					} else {
						HTMLForms.getField("description").disable();
					}'
		))));

		$fieldset->add_field(new FormFieldRichTextEditor('description', StringVars::replace_vars($this->lang['pbtdoc.form.description'],
			array('number' =>PbtdocConfig::load()->get_number_character_to_cut())), $this->get_course()->get_description(),
			array('rows' => 3, 'hidden' => !$this->get_course()->get_description_enabled())
		));

		$fieldset->add_field(new FormFieldRichTextEditor('contents', $this->common_lang['form.contents'], $this->get_course()->get_contents(),
			array('rows' => 15, 'required' => true)
		));

		$fieldset->add_field(new FormFieldActionLink('add_page', $this->lang['pbtdoc.form.add_page'] , 'javascript:bbcode_page();', 'fa-pagebreak'));

		if ($this->get_course()->get_author_name_displayed() == true)
		{
			$fieldset->add_field(new FormFieldCheckbox('author_custom_name_enabled', $this->common_lang['form.author_custom_name_enabled'], $this->get_course()->is_author_custom_name_enabled(),
				array('events' => array('click' => '
				if (HTMLForms.getField("author_custom_name_enabled").getValue()) {
					HTMLForms.getField("author_custom_name").enable();
				} else {
					HTMLForms.getField("author_custom_name").disable();
				}'))
			));

			$fieldset->add_field(new FormFieldTextEditor('author_custom_name', $this->common_lang['form.author_custom_name'], $this->get_course()->get_author_custom_name(), array(
				'hidden' => !$this->get_course()->is_author_custom_name_enabled(),
			)));
		}

		$other_fieldset = new FormFieldsetHTML('other', $this->common_lang['form.other']);
		$form->add_fieldset($other_fieldset);

		$other_fieldset->add_field(new FormFieldCheckbox('author_name_displayed', LangLoader::get_message('config.author_displayed', 'admin-common'), $this->get_course()->get_author_name_displayed()));

		$other_fieldset->add_field(new FormFieldUploadPictureFile('thumbnail', $this->lang['pbtdoc.form.thumbnail'], $this->get_course()->get_thumbnail()->relative()));

		$other_fieldset->add_field(PbtdocService::get_keywords_manager()->get_form_field($this->get_course()->get_id(), 'keywords', $this->common_lang['form.keywords'],
			array('description' => $this->common_lang['form.keywords.description'])
		));

		if (PbtdocAuthorizationsService::check_authorizations($this->get_course()->get_id_category())->moderation())
		{
			$publication_fieldset = new FormFieldsetHTML('publication', $this->common_lang['form.approbation']);
			$form->add_fieldset($publication_fieldset);

			$publication_fieldset->add_field(new FormFieldDateTime('date_created', $this->common_lang['form.date.creation'], $this->get_course()->get_date_created(),
				array('required' => true)
			));

			if (!$this->get_course()->is_published())
			{
				$publication_fieldset->add_field(new FormFieldCheckbox('update_creation_date', $this->common_lang['form.update.date.creation'], false, array('hidden' => $this->get_course()->get_status() != Course::NOT_PUBLISHED)
				));
			}

			$publication_fieldset->add_field(new FormFieldSimpleSelectChoice('publishing_state', $this->common_lang['form.approbation'], $this->get_course()->get_publishing_state(),
				array(
					new FormFieldSelectChoiceOption($this->common_lang['form.approbation.not'], Course::NOT_PUBLISHED),
					new FormFieldSelectChoiceOption($this->common_lang['form.approbation.now'], Course::PUBLISHED_NOW),
					new FormFieldSelectChoiceOption($this->common_lang['status.approved.date'], Course::PUBLISHED_DATE),
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
				($this->get_course()->get_publishing_start_date() === null ? new Date() : $this->get_course()->get_publishing_start_date()),
				array('hidden' => ($this->get_course()->get_publishing_state() != Course::PUBLISHED_DATE))
			));

			$publication_fieldset->add_field(new FormFieldCheckbox('end_date_enable', $this->common_lang['form.date.end.enable'], $this->get_course()->end_date_enabled(),
				array('hidden' => ($this->get_course()->get_publishing_state() != Course::PUBLISHED_DATE),
					'events' => array('click' => '
						if (HTMLForms.getField("end_date_enable").getValue()) {
							HTMLForms.getField("publishing_end_date").enable();
						} else {
							HTMLForms.getField("publishing_end_date").disable();
						}'
				))
			));

			$publication_fieldset->add_field(new FormFieldDateTime('publishing_end_date', $this->common_lang['form.date.end'],
				($this->get_course()->get_publishing_end_date() === null ? new date() : $this->get_course()->get_publishing_end_date()),
				array('hidden' => !$this->get_course()->end_date_enabled())
			));
		}

		$this->build_contribution_fieldset($form);

		$fieldset->add_field(new FormFieldHidden('referrer', $request->get_url_referrer()));

		$this->submit_button = new FormButtonDefaultSubmit();
		$form->add_button($this->submit_button);
		$form->add_button(new FormButtonReset());

		$this->form = $form;

		// Positionnement à la bonne page quand on édite un course avec plusieurs pages
		if ($this->get_course()->get_id() !== null)
		{
			$current_page = $request->get_getstring('page', '');

			$this->tpl->put('C_PAGE', !empty($current_page));

			if (!empty($current_page))
			{
				$course_contents = $this->course->get_contents();

				//If course doesn't begin with a page, we insert one
				if (TextHelper::substr(trim($course_contents), 0, 6) != '[page]')
				{
					$course_contents = '[page]&nbsp;[/page]' . $course_contents;
				}

				//Removing [page] bbcode
				$course_contents_clean = preg_split('`\[page\].+\[/page\](.*)`usU', $course_contents, -1, PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY);

				//Retrieving pages
				preg_match_all('`\[page\]([^[]+)\[/page\]`uU', $course_contents, $array_page);

				$page_name = (isset($array_page[1][$current_page-1]) && $array_page[1][$current_page-1] != '&nbsp;') ? $array_page[1][($current_page-1)] : '';

				$this->tpl->put('PAGE', TextHelper::to_js_string($page_name));
			}
		}
	}

	private function build_contribution_fieldset($form)
	{
		if ($this->get_course()->get_id() === null && $this->is_contributor_member())
		{
			$fieldset = new FormFieldsetHTML('contribution', LangLoader::get_message('contribution', 'user-common'));
			$fieldset->set_description(MessageHelper::display(LangLoader::get_message('contribution.explain', 'user-common'), MessageHelper::WARNING)->render());
			$form->add_fieldset($fieldset);

			$fieldset->add_field(new FormFieldRichTextEditor('contribution_description', LangLoader::get_message('contribution.description', 'user-common'), '',
				array('description' => LangLoader::get_message('contribution.description.explain', 'user-common'))));
		}
		elseif ($this->get_course()->is_published() && $this->get_course()->is_authorized_to_edit() && !AppContext::get_current_user()->check_level(User::ADMIN_LEVEL))
		{
			$fieldset = new FormFieldsetHTML('member_edition', LangLoader::get_message('pbtdoc.form.member.edition', 'common', 'pbtdoc'));
			$fieldset->set_description(MessageHelper::display(LangLoader::get_message('pbtdoc.form.member.edition.explain', 'common', 'pbtdoc'), MessageHelper::WARNING)->render());
			$form->add_fieldset($fieldset);

			$fieldset->add_field(new FormFieldRichTextEditor('edittion_description', LangLoader::get_message('pbtdoc.form.member.edition.description', 'common', 'pbtdoc'), '',
				array('description' => LangLoader::get_message('pbtdoc.form.member.edition.description.desc', 'common', 'pbtdoc'))
			));
		}
	}

	private function is_contributor_member()
	{
		return (!PbtdocAuthorizationsService::check_authorizations()->write() && PbtdocAuthorizationsService::check_authorizations()->contribution());
	}

	private function get_course()
	{
		if ($this->course === null)
		{
			$id = AppContext::get_request()->get_getint('id', 0);
			if (!empty($id))
			{
				try
				{
					$this->course = PbtdocService::get_course('WHERE pbtdoc.id=:id', array('id' => $id));
				}
				catch(RowNotFoundException $e)
				{
					$error_controller = PHPBoostErrors::unexisting_page();
					DispatchManager::redirect($error_controller);
				}
			}
			else
			{
				$this->is_new_course = true;
				$this->course = new Course();
				$this->course->init_default_properties(AppContext::get_request()->get_getint('id_category', Category::ROOT_CATEGORY));
			}
		}
		return $this->course;
	}

	private function check_authorizations()
	{
		$course = $this->get_course();

		if ($course->get_id() === null)
		{
			if (!$course->is_authorized_to_add())
			{
				$error_controller = PHPBoostErrors::user_not_authorized();
				DispatchManager::redirect($error_controller);
			}
		}
		else
		{
			if (!$course->is_authorized_to_edit())
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
		$course = $this->get_course();

		$course->set_title($this->form->get_value('title'));

		if (PbtdocService::get_categories_manager()->get_categories_cache()->has_categories())
			$course->set_id_category($this->form->get_value('id_category')->get_raw_value());

		if ($course->get_order_id() === null)
		{
			$course_nb = PbtdocService::count('WHERE id_category = :id_category', array('id_category' => $course->get_id_category()));
			$course->set_order_id($course_nb + 1);
		}

		$course->set_description(($this->form->get_value('enable_description') ? $this->form->get_value('description') : ''));
		$course->set_contents($this->form->get_value('contents'));

		$author_name_displayed = $this->form->get_value('author_name_displayed') ? $this->form->get_value('author_name_displayed') : Course::AUTHOR_NAME_NOTDISPLAYED;
		$course->set_author_name_displayed($author_name_displayed);
		$course->set_thumbnail(new Url($this->form->get_value('thumbnail')));

		if ($this->get_course()->get_author_name_displayed() == true)
			$course->set_author_custom_name(($this->form->get_value('author_custom_name') && $this->form->get_value('author_custom_name') !== $course->get_author_user()->get_display_name() ? $this->form->get_value('author_custom_name') : ''));

		if (!PbtdocAuthorizationsService::check_authorizations($course->get_id_category())->moderation())
		{
			if ($course->get_id() === null)
				$course->set_date_created(new Date());

			$course->set_rewrited_title(Url::encode_rewrite($course->get_title()));
			$course->clean_publishing_start_and_end_date();

			if (PbtdocAuthorizationsService::check_authorizations($course->get_id_category())->contribution() && !PbtdocAuthorizationsService::check_authorizations($course->get_id_category())->write())
				$course->set_publishing_state(Course::NOT_PUBLISHED);
		}
		else
		{
			if ($this->form->get_value('update_creation_date'))
			{
				$course->set_date_created(new Date());
			}
			else
			{
				$course->set_date_created($this->form->get_value('date_created'));
			}

			$rewrited_title = $this->form->get_value('rewrited_title', '');
			$rewrited_title = $this->form->get_value('personalize_rewrited_title') && !empty($rewrited_title) ? $rewrited_title : Url::encode_rewrite($course->get_title());
			$course->set_rewrited_title($rewrited_title);

			$course->set_publishing_state($this->form->get_value('publishing_state')->get_raw_value());
			if ($course->get_publishing_state() == Course::PUBLISHED_DATE)
			{
				$config = PbtdocConfig::load();
				$deferred_operations = $config->get_deferred_operations();

				$old_start_date = $course->get_publishing_start_date();
				$start_date = $this->form->get_value('publishing_start_date');
				$course->set_publishing_start_date($start_date);

				if ($old_start_date !== null && $old_start_date->get_timestamp() != $start_date->get_timestamp() && in_array($old_start_date->get_timestamp(), $deferred_operations))
				{
					$key = array_search($old_start_date->get_timestamp(), $deferred_operations);
					unset($deferred_operations[$key]);
				}

				if (!in_array($start_date->get_timestamp(), $deferred_operations))
					$deferred_operations[] = $start_date->get_timestamp();

				if ($this->form->get_value('end_date_enable'))
				{
					$old_end_date = $course->get_publishing_end_date();
					$end_date = $this->form->get_value('publishing_end_date');
					$course->set_publishing_end_date($end_date);

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
					$course->clean_publishing_end_date();
				}

				$config->set_deferred_operations($deferred_operations);
				PbtdocConfig::save();
			}
			else
			{
				$course->clean_publishing_start_and_end_date();
			}
		}

		if ($course->get_id() === null)
		{
			$course->set_author_user(AppContext::get_current_user());
			$id_course = PbtdocService::add($course);
		}
		else
		{
			$now = new Date();
			$course->set_date_updated($now);
			$id_course = $course->get_id();
			PbtdocService::update($course);
		}

		$this->contribution_actions($course, $id_course);

		PbtdocService::get_keywords_manager()->put_relations($id_course, $this->form->get_value('keywords'));

		Feed::clear_cache('pbtdoc');
		PbtdocCategoriesCache::invalidate();
	}

	private function contribution_actions(Course $course, $id_course)
	{
		if ($this->is_contributor_member())
		{
			$contribution = new Contribution();
			$contribution->set_id_in_module($id_course);
			if ($course->get_id() === null)
				$contribution->set_description(stripslashes($this->form->get_value('contribution_description')));
			else
				$contribution->set_description(stripslashes($this->form->get_value('edittion_description')));

			$contribution->set_entitled($course->get_title());
			$contribution->set_fixing_url(PbtdocUrlBuilder::edit_item($id_course)->relative());
			$contribution->set_poster_id(AppContext::get_current_user()->get_id());
			$contribution->set_module('pbtdoc');
			$contribution->set_auth(
				Authorizations::capture_and_shift_bit_auth(
					PbtdocService::get_categories_manager()->get_heritated_authorizations($course->get_id_category(), Category::MODERATION_AUTHORIZATIONS, Authorizations::AUTH_CHILD_PRIORITY),
					Category::MODERATION_AUTHORIZATIONS, Contribution::CONTRIBUTION_AUTH_BIT
				)
			);
			ContributionService::save_contribution($contribution);
		}
		else
		{
			$corresponding_contributions = ContributionService::find_by_criteria('pbtdoc', $id_course);
			if (count($corresponding_contributions) > 0)
			{
				foreach ($corresponding_contributions as $contribution)
				{
					$contribution->set_status(Event::EVENT_STATUS_PROCESSED);
					ContributionService::save_contribution($contribution);
				}
			}
		}
		$course->set_id($id_course);
	}

	private function redirect()
	{
		$course = $this->get_course();
		$category = $course->get_category();

		if ($this->is_new_course && $this->is_contributor_member() && !$course->is_published())
		{
			DispatchManager::redirect(new UserContributionSuccessController());
		}
		elseif ($course->is_published())
		{
			if ($this->is_new_course)
				AppContext::get_response()->redirect(PbtdocUrlBuilder::display_item($category->get_id(), $category->get_rewrited_name(), $course->get_id(), $course->get_rewrited_title(), AppContext::get_request()->get_getint('page', 1)), StringVars::replace_vars($this->lang['pbtdoc.message.success.add'], array('title' => $course->get_title())));
			else
				AppContext::get_response()->redirect(($this->form->get_value('referrer') ? $this->form->get_value('referrer') : PbtdocUrlBuilder::display_item($category->get_id(), $category->get_rewrited_name(), $course->get_id(), $course->get_rewrited_title(), AppContext::get_request()->get_getint('page', 1))), StringVars::replace_vars($this->lang['pbtdoc.message.success.edit'], array('title' => $course->get_title())));
		}
		else
		{
			if ($this->is_new_course)
				AppContext::get_response()->redirect(PbtdocUrlBuilder::display_pending_items(), StringVars::replace_vars($this->lang['pbtdoc.message.success.add'], array('title' => $course->get_title())));
			else
				AppContext::get_response()->redirect(($this->form->get_value('referrer') ? $this->form->get_value('referrer') : PbtdocUrlBuilder::display_pending_items()), StringVars::replace_vars($this->lang['pbtdoc.message.success.edit'], array('title' => $course->get_title())));
		}
	}

	private function build_response(View $tpl)
	{
		$course = $this->get_course();

		$response = new SiteDisplayResponse($tpl);
		$graphical_environment = $response->get_graphical_environment();

		$breadcrumb = $graphical_environment->get_breadcrumb();
		$breadcrumb->add($this->lang['module.title'], PbtdocUrlBuilder::home());

		if ($course->get_id() === null)
		{
			$breadcrumb->add($this->lang['pbtdoc.add'], PbtdocUrlBuilder::add_item($course->get_id_category()));
			$graphical_environment->set_page_title($this->lang['pbtdoc.add'], $this->lang['module.title']);
			$graphical_environment->get_seo_meta_data()->set_description($this->lang['pbtdoc.add']);
			$graphical_environment->get_seo_meta_data()->set_canonical_url(PbtdocUrlBuilder::add_item($course->get_id_category()));
		}
		else
		{
			$categories = array_reverse(PbtdocService::get_categories_manager()->get_parents($course->get_id_category(), true));
			foreach ($categories as $id => $category)
			{
				if ($category->get_id() != Category::ROOT_CATEGORY)
					$breadcrumb->add($category->get_name(), PbtdocUrlBuilder::display_category($category->get_id(), $category->get_rewrited_name()));
			}
			$breadcrumb->add($course->get_title(), PbtdocUrlBuilder::display_item($category->get_id(), $category->get_rewrited_name(), $course->get_id(), $course->get_rewrited_title()));

			$breadcrumb->add($this->lang['pbtdoc.edit'], PbtdocUrlBuilder::edit_item($course->get_id()));
			$graphical_environment->set_page_title($this->lang['pbtdoc.edit'], $this->lang['module.title']);
			$graphical_environment->get_seo_meta_data()->set_description($this->lang['pbtdoc.edit']);
			$graphical_environment->get_seo_meta_data()->set_canonical_url(PbtdocUrlBuilder::edit_item($course->get_id()));
		}

		return $response;
	}
}
?>
