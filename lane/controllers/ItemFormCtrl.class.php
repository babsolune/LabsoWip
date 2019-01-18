<?php
/**
 * @copyright 	&copy; 2005-2019 PHPBoost https://www.phpboost.com
 * @license 	https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE [babsolune@phpboost.com]
 * @version   	PHPBoost 5.2 - last update: 2018 05 25
 * @since   	PHPBoost 5.1 - 2018 05 25
*/

namespace Wiki\controllers;
use \Wiki\phpboost\ModConfig;
use \Wiki\services\ModAuthorizations;
use \Wiki\services\ModCategoriesCache;
use \Wiki\services\ModItem;
use \Wiki\services\ModKeywordsCache;
use \Wiki\services\ModServices;
use \Wiki\util\ModUrlBuilder;

class ItemFormCtrl extends ModuleController
{
	/**
	 * @var HTMLForm
	 */
	private $form;
	/**
	 * @var FormButtonSubmit
	 */
	private $submit_button;

	private $view;

	private $lang;
	private $common_lang;

	private $moditem;
	private $is_new_moditem;

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

		$this->view->put_all(array(
			'FORM' => $this->form->display(),
			'C_TINYMCE_EDITOR' => AppContext::get_current_user()->get_editor() == 'TinyMCE'
		));

		return $this->build_response($this->view);
	}

	private function init()
	{
		$this->lang = LangLoader::get('common', 'wiki');
		$this->view = new FileTemplate('wiki/ItemFormCtrl.tpl');
		$this->view->add_lang($this->lang);
		$this->common_lang = LangLoader::get('common');
	}

	private function build_form(HTTPRequestCustom $request)
	{
		$form = new HTMLForm(__CLASS__);

		$fieldset = new FormFieldsetHTML('moditem', $this->get_moditem()->get_id() === null ? $this->lang['add.item'] : $this->lang['edit.item']);
		$form->add_fieldset($fieldset);

		$fieldset->add_field(new FormFieldTextEditor('title', $this->common_lang['form.title'], $this->get_moditem()->get_title(),
			array('required' => true)
		));

		if (ModAuthorizations::check_authorizations($this->get_moditem()->get_category_id())->moderation())
		{
			$fieldset->add_field(new FormFieldCheckbox('personalize_rewrited_title', $this->common_lang['form.rewrited_name.personalize'], $this->get_moditem()->rewrited_title_is_personalized(),
				array('events' => array('click' =>'
					if (HTMLForms.getField("personalize_rewrited_title").getValue()) {
						HTMLForms.getField("rewrited_title").enable();
					} else {
						HTMLForms.getField("rewrited_title").disable();
					}'
				))
			));

			$fieldset->add_field(new FormFieldTextEditor('rewrited_title', $this->common_lang['form.rewrited_name'], $this->get_moditem()->get_rewrited_title(),
				array('description' => $this->common_lang['form.rewrited_name.description'],
				      'hidden' => !$this->get_moditem()->rewrited_title_is_personalized()),
				array(new FormFieldConstraintRegex('`^[a-z0-9\-]+$`iu'))
			));
		}

		if (ModServices::get_categories_manager()->get_categories_cache()->has_categories())
		{
			$search_category_children_options = new SearchCategoryChildrensOptions();
			$search_category_children_options->add_authorizations_bits(Category::CONTRIBUTION_AUTHORIZATIONS);
			$search_category_children_options->add_authorizations_bits(Category::WRITE_AUTHORIZATIONS);
			$fieldset->add_field(ModServices::get_categories_manager()->get_select_categories_form_field('category_id', $this->common_lang['form.category'], $this->get_moditem()->get_category_id(), $search_category_children_options));
		}

		$fieldset->add_field(new FormFieldCheckbox('enable_description', $this->lang['form.enable.description'], $this->get_moditem()->get_description_enabled(),
			array('description' => StringVars::replace_vars($this->lang['form.phpboost.description'],
			array('number' => ModConfig::load()->get_number_character_to_cut())),
				'events' => array('click' => '
					if (HTMLForms.getField("enable_description").getValue()) {
						HTMLForms.getField("description").enable();
					} else {
						HTMLForms.getField("description").disable();
					}'
		))));

		$fieldset->add_field(new FormFieldRichTextEditor('description', StringVars::replace_vars($this->lang['form.description'],
			array('number' =>ModConfig::load()->get_number_character_to_cut())), $this->get_moditem()->get_description(),
			array('rows' => 3, 'hidden' => !$this->get_moditem()->get_description_enabled())
		));

		$fieldset->add_field(new FormFieldRichTextEditor('contents', $this->common_lang['form.contents'], $this->get_moditem()->get_contents(),
			array('rows' => 15, 'required' => true)
		));

		$fieldset->add_field(new FormFieldActionLink('add_page', $this->lang['form.add.page'] , 'javascript:bbcode_page();', 'fa-pagebreak'));

		if ($this->get_moditem()->get_author_name_displayed() == true)
		{
			$fieldset->add_field(new FormFieldCheckbox('author_custom_name_enabled', $this->common_lang['form.author_custom_name_enabled'], $this->get_moditem()->is_author_custom_name_enabled(),
				array('events' => array('click' => '
				if (HTMLForms.getField("author_custom_name_enabled").getValue()) {
					HTMLForms.getField("author_custom_name").enable();
				} else {
					HTMLForms.getField("author_custom_name").disable();
				}'))
			));

			$fieldset->add_field(new FormFieldTextEditor('author_custom_name', $this->common_lang['form.author_custom_name'], $this->get_moditem()->get_author_custom_name(), array(
				'hidden' => !$this->get_moditem()->is_author_custom_name_enabled(),
			)));
		}

		$other_fieldset = new FormFieldsetHTML('other', $this->common_lang['form.other']);
		$form->add_fieldset($other_fieldset);

		$other_fieldset->add_field(new FormFieldCheckbox('author_name_displayed', LangLoader::get_message('config.author_displayed', 'admin-common'), $this->get_moditem()->get_author_name_displayed()));

		$other_fieldset->add_field(new FormFieldUploadPictureFile('thumbnail', $this->lang['form.item.thumbnail'], $this->get_moditem()->get_thumbnail()->relative()));

		$other_fieldset->add_field(ModServices::get_keywords_manager()->get_form_field($this->get_moditem()->get_id(), 'keywords', $this->common_lang['form.keywords'],
			array('description' => $this->common_lang['form.keywords.description'])
		));

		if (ModAuthorizations::check_authorizations($this->get_moditem()->get_category_id())->moderation())
		{
			$publication_fieldset = new FormFieldsetHTML('publication', $this->common_lang['form.approbation']);
			$form->add_fieldset($publication_fieldset);

			$publication_fieldset->add_field(new FormFieldDateTime('date_created', $this->common_lang['form.date.creation'], $this->get_moditem()->get_date_created(),
				array('required' => true)
			));

			if (!$this->get_moditem()->is_published())
			{
				$publication_fieldset->add_field(new FormFieldCheckbox('update_creation_date', $this->common_lang['form.update.date.creation'], false, array('hidden' => $this->get_moditem()->get_status() != ModItem::NOT_PUBLISHED)
				));
			}

			$publication_fieldset->add_field(new FormFieldSimpleSelectChoice('publishing_state', $this->common_lang['form.approbation'], $this->get_moditem()->get_publishing_state(),
				array(
					new FormFieldSelectChoiceOption($this->common_lang['form.approbation.not'], ModItem::NOT_PUBLISHED),
					new FormFieldSelectChoiceOption($this->common_lang['form.approbation.now'], ModItem::PUBLISHED_NOW),
					new FormFieldSelectChoiceOption($this->common_lang['status.approved.date'], ModItem::PUBLISHED_DATE),
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
				($this->get_moditem()->get_publishing_start_date() === null ? new Date() : $this->get_moditem()->get_publishing_start_date()),
				array('hidden' => ($this->get_moditem()->get_publishing_state() != ModItem::PUBLISHED_DATE))
			));

			$publication_fieldset->add_field(new FormFieldCheckbox('end_date_enable', $this->common_lang['form.date.end.enable'], $this->get_moditem()->end_date_enabled(),
				array('hidden' => ($this->get_moditem()->get_publishing_state() != ModItem::PUBLISHED_DATE),
					'events' => array('click' => '
						if (HTMLForms.getField("end_date_enable").getValue()) {
							HTMLForms.getField("publishing_end_date").enable();
						} else {
							HTMLForms.getField("publishing_end_date").disable();
						}'
				))
			));

			$publication_fieldset->add_field(new FormFieldDateTime('publishing_end_date', $this->common_lang['form.date.end'],
				($this->get_moditem()->get_publishing_end_date() === null ? new date() : $this->get_moditem()->get_publishing_end_date()),
				array('hidden' => !$this->get_moditem()->end_date_enabled())
			));
		}

		$this->build_contribution_fieldset($form);

		$fieldset->add_field(new FormFieldHidden('referrer', $request->get_url_referrer()));

		$this->submit_button = new FormButtonDefaultSubmit();
		$form->add_button($this->submit_button);
		$form->add_button(new FormButtonReset());

		$this->form = $form;

		// Positionnement à la bonne page quand on édite un article avec plusieurs pages
		if ($this->get_moditem()->get_id() !== null)
		{
			$current_page = $request->get_getstring('page', '');

			$this->view->put('C_PAGE', !empty($current_page));

			if (!empty($current_page))
			{
				$moditem_contents = $this->moditem->get_contents();

				//If the article doesn't begin with a page, we insert one
				if (TextHelper::substr(trim($moditem_contents), 0, 6) != '[page]')
				{
					$moditem_contents = '[page]&nbsp;[/page]' . $moditem_contents;
				}

				//Removing [page] bbcode
				$moditem_contents_clean = preg_split('`\[page\].+\[/page\](.*)`usU', $moditem_contents, -1, PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY);

				//Retrieving pages
				preg_match_all('`\[page\]([^[]+)\[/page\]`uU', $moditem_contents, $array_page);

				$page_name = (isset($array_page[1][$current_page-1]) && $array_page[1][$current_page-1] != '&nbsp;') ? $array_page[1][($current_page-1)] : '';

				$this->view->put('PAGE', TextHelper::to_js_string($page_name));
			}
		}
	}

	private function build_contribution_fieldset($form)
	{
		if ($this->get_moditem()->get_id() === null && $this->is_contributor_member())
		{
			$fieldset = new FormFieldsetHTML('contribution', LangLoader::get_message('contribution', 'user-common'));
			$fieldset->set_description(MessageHelper::display(LangLoader::get_message('contribution.explain', 'user-common'), MessageHelper::WARNING)->render());
			$form->add_fieldset($fieldset);

			$fieldset->add_field(new FormFieldRichTextEditor('contribution_description', LangLoader::get_message('contribution.description', 'user-common'), '',
				array('description' => LangLoader::get_message('contribution.description.explain', 'user-common'))));
		}
		elseif ($this->get_moditem()->is_published() && $this->get_moditem()->is_authorized_to_edit() && !AppContext::get_current_user()->check_level(User::ADMIN_LEVEL))
		{
			$fieldset = new FormFieldsetHTML('member_edition', LangLoader::get_message('form.member.edition', 'common', 'wiki'));
			$fieldset->set_description(MessageHelper::display(LangLoader::get_message('form.member.edition.explain', 'common', 'wiki'), MessageHelper::WARNING)->render());
			$form->add_fieldset($fieldset);

			$fieldset->add_field(new FormFieldRichTextEditor('edittion_description', LangLoader::get_message('form.member.edition.more', 'common', 'wiki'), '',
				array('description' => LangLoader::get_message('form.member.edition.more.desc', 'common', 'wiki'))
			));
		}
	}

	private function is_contributor_member()
	{
		return (!ModAuthorizations::check_authorizations()->write() && ModAuthorizations::check_authorizations()->contribution());
	}

	private function get_moditem()
	{
		if ($this->moditem === null)
		{
			$id = AppContext::get_request()->get_getint('id', 0);
			if (!empty($id))
			{
				try
				{
					$this->moditem = ModServices::get_moditem('WHERE item.id=:id', array('id' => $id));
				}
				catch(RowNotFoundException $e)
				{
					$error_controller = PHPBoostErrors::unexisting_page();
					DispatchManager::redirect($error_controller);
				}
			}
			else
			{
				$this->is_new_moditem = true;
				$this->moditem = new ModItem();
				$this->moditem->init_default_properties(AppContext::get_request()->get_getint('category_id', Category::ROOT_CATEGORY));
			}
		}
		return $this->moditem;
	}

	private function check_authorizations()
	{
		$moditem = $this->get_moditem();

		if ($moditem->get_id() === null)
		{
			if (!$moditem->is_authorized_to_add())
			{
				$error_controller = PHPBoostErrors::user_not_authorized();
				DispatchManager::redirect($error_controller);
			}
		}
		else
		{
			if (!$moditem->is_authorized_to_edit())
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
		$moditem = $this->get_moditem();

		$moditem->set_title($this->form->get_value('title'));

		if (ModServices::get_categories_manager()->get_categories_cache()->has_categories())
			$moditem->set_category_id($this->form->get_value('category_id')->get_raw_value());

		if ($moditem->get_order_id() === null)
		{
			$moditem_nb = ModServices::count('WHERE category_id = :category_id', array('category_id' => $moditem->get_category_id()));
			$moditem->set_order_id($moditem_nb + 1);
		}

		$moditem->set_description(($this->form->get_value('enable_description') ? $this->form->get_value('description') : ''));
		$moditem->set_contents($this->form->get_value('contents'));

		$author_name_displayed = $this->form->get_value('author_name_displayed') ? $this->form->get_value('author_name_displayed') : ModItem::AUTHOR_NAME_NOTDISPLAYED;
		$moditem->set_author_name_displayed($author_name_displayed);
		$moditem->set_thumbnail(new Url($this->form->get_value('thumbnail')));

		if ($this->get_moditem()->get_author_name_displayed() == true)
			$moditem->set_author_custom_name(($this->form->get_value('author_custom_name') && $this->form->get_value('author_custom_name') !== $moditem->get_author_user()->get_display_name() ? $this->form->get_value('author_custom_name') : ''));

		if (!ModAuthorizations::check_authorizations($moditem->get_category_id())->moderation())
		{
			if ($moditem->get_id() === null)
				$moditem->set_date_created(new Date());

			$moditem->set_rewrited_title(Url::encode_rewrite($moditem->get_title()));
			$moditem->clean_publishing_start_and_end_date();

			if (ModAuthorizations::check_authorizations($moditem->get_category_id())->contribution() && !ModAuthorizations::check_authorizations($moditem->get_category_id())->write())
				$moditem->set_publishing_state(ModItem::NOT_PUBLISHED);
		}
		else
		{
			if ($this->form->get_value('update_creation_date'))
			{
				$moditem->set_date_created(new Date());
			}
			else
			{
				$moditem->set_date_created($this->form->get_value('date_created'));
			}

			$rewrited_title = $this->form->get_value('rewrited_title', '');
			$rewrited_title = $this->form->get_value('personalize_rewrited_title') && !empty($rewrited_title) ? $rewrited_title : Url::encode_rewrite($moditem->get_title());
			$moditem->set_rewrited_title($rewrited_title);

			$moditem->set_publishing_state($this->form->get_value('publishing_state')->get_raw_value());
			if ($moditem->get_publishing_state() == ModItem::PUBLISHED_DATE)
			{
				$config = ModConfig::load();
				$deferred_operations = $config->get_deferred_operations();

				$old_start_date = $moditem->get_publishing_start_date();
				$start_date = $this->form->get_value('publishing_start_date');
				$moditem->set_publishing_start_date($start_date);

				if ($old_start_date !== null && $old_start_date->get_timestamp() != $start_date->get_timestamp() && in_array($old_start_date->get_timestamp(), $deferred_operations))
				{
					$key = array_search($old_start_date->get_timestamp(), $deferred_operations);
					unset($deferred_operations[$key]);
				}

				if (!in_array($start_date->get_timestamp(), $deferred_operations))
					$deferred_operations[] = $start_date->get_timestamp();

				if ($this->form->get_value('end_date_enable'))
				{
					$old_end_date = $moditem->get_publishing_end_date();
					$end_date = $this->form->get_value('publishing_end_date');
					$moditem->set_publishing_end_date($end_date);

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
					$moditem->clean_publishing_end_date();
				}

				$config->set_deferred_operations($deferred_operations);
				ModConfig::save();
			}
			else
			{
				$moditem->clean_publishing_start_and_end_date();
			}
		}

		if ($moditem->get_id() === null)
		{
			$moditem->set_author_user(AppContext::get_current_user());
			$moditem_id = ModServices::add($moditem);
		}
		else
		{
			$now = new Date();
			$moditem->set_date_updated($now);
			$moditem_id = $moditem->get_id();
			ModServices::update($moditem);
		}

		$this->contribution_actions($moditem, $moditem_id);

		ModServices::get_keywords_manager()->put_relations($moditem_id, $this->form->get_value('keywords'));

		Feed::clear_cache('wiki');
		ModCategoriesCache::invalidate();
		ModKeywordsCache::invalidate();
	}

	private function contribution_actions(ModItem $moditem, $moditem_id)
	{
		if ($this->is_contributor_member())
		{
			$contribution = new Contribution();
			$contribution->set_id_in_module($moditem_id);
			if ($moditem->get_id() === null)
				$contribution->set_description(stripslashes($this->form->get_value('contribution_description')));
			else
				$contribution->set_description(stripslashes($this->form->get_value('edittion_description')));

			$contribution->set_entitled($moditem->get_title());
			$contribution->set_fixing_url(ModUrlBuilder::edit_item($moditem_id)->relative());
			$contribution->set_poster_id(AppContext::get_current_user()->get_id());
			$contribution->set_module('wiki');
			$contribution->set_auth(
				Authorizations::capture_and_shift_bit_auth(
					ModServices::get_categories_manager()->get_heritated_authorizations($moditem->get_category_id(), Category::MODERATION_AUTHORIZATIONS, Authorizations::AUTH_CHILD_PRIORITY),
					Category::MODERATION_AUTHORIZATIONS, Contribution::CONTRIBUTION_AUTH_BIT
				)
			);
			ContributionService::save_contribution($contribution);
		}
		else
		{
			$corresponding_contributions = ContributionService::find_by_criteria('wiki', $moditem_id);
			if (count($corresponding_contributions) > 0)
			{
				foreach ($corresponding_contributions as $contribution)
				{
					$contribution->set_status(Event::EVENT_STATUS_PROCESSED);
					ContributionService::save_contribution($contribution);
				}
			}
		}
		$moditem->set_id($moditem_id);
	}

	private function redirect()
	{
		$moditem = $this->get_moditem();
		$category = $moditem->get_category();

		if ($this->is_new_moditem && $this->is_contributor_member() && !$moditem->is_published())
		{
			DispatchManager::redirect(new UserContributionSuccessController());
		}
		elseif ($moditem->is_published())
		{
			if ($this->is_new_moditem)
				AppContext::get_response()->redirect(ModUrlBuilder::display_item($category->get_id(), $category->get_rewrited_name(), $moditem->get_id(), $moditem->get_rewrited_title(), AppContext::get_request()->get_getint('page', 1)), StringVars::replace_vars($this->lang['add.success.message.helper'], array('title' => $moditem->get_title())));
			else
				AppContext::get_response()->redirect(($this->form->get_value('referrer') ? $this->form->get_value('referrer') : ModUrlBuilder::display_item($category->get_id(), $category->get_rewrited_name(), $moditem->get_id(), $moditem->get_rewrited_title(), AppContext::get_request()->get_getint('page', 1))), StringVars::replace_vars($this->lang['edit.success.message.helper'], array('title' => $moditem->get_title())));
		}
		else
		{
			if ($this->is_new_moditem)
				AppContext::get_response()->redirect(ModUrlBuilder::display_pending_items(), StringVars::replace_vars($this->lang['add.success.message.helper'], array('title' => $moditem->get_title())));
			else
				AppContext::get_response()->redirect(($this->form->get_value('referrer') ? $this->form->get_value('referrer') : ModUrlBuilder::display_pending_items()), StringVars::replace_vars($this->lang['edit.success.message.helper'], array('title' => $moditem->get_title())));
		}
	}

	private function build_response(View $view)
	{
		$moditem = $this->get_moditem();

		$location_id = $moditem->get_id() ? 'edit-item-'. $moditem->get_id() : '';

		$response = new SiteDisplayResponse($view, $location_id);
		$graphical_environment = $response->get_graphical_environment();

		$breadcrumb = $graphical_environment->get_breadcrumb();
		$breadcrumb->add($this->lang['module.title'], ModUrlBuilder::home());

		if ($moditem->get_id() === null)
		{
			$breadcrumb->add($this->lang['add.item'], ModUrlBuilder::add_item($moditem->get_category_id()));
			$graphical_environment->set_page_title($this->lang['add.item'], $this->lang['module.title']);
			$graphical_environment->get_seo_meta_data()->set_description($this->lang['add.item']);
			$graphical_environment->get_seo_meta_data()->set_canonical_url(ModUrlBuilder::add_item($moditem->get_category_id()));
		}
		else
		{
			$categories = array_reverse(ModServices::get_categories_manager()->get_parents($moditem->get_category_id(), true));
			foreach ($categories as $id => $category)
			{
				if ($category->get_id() != Category::ROOT_CATEGORY)
					$breadcrumb->add($category->get_name(), ModUrlBuilder::display_category($category->get_id(), $category->get_rewrited_name()));
			}
			$breadcrumb->add($moditem->get_title(), ModUrlBuilder::display_item($category->get_id(), $category->get_rewrited_name(), $moditem->get_id(), $moditem->get_rewrited_title()));

			$breadcrumb->add($this->lang['edit.item'], ModUrlBuilder::edit_item($moditem->get_id()));
			if (!AppContext::get_session()->location_id_already_exists($location_id))
				$graphical_environment->set_location_id($location_id);

			$graphical_environment->set_page_title($this->lang['edit.item'], $this->lang['module.title']);
			$graphical_environment->get_seo_meta_data()->set_description($this->lang['edit.item']);
			$graphical_environment->get_seo_meta_data()->set_canonical_url(ModUrlBuilder::edit_item($moditem->get_id()));
		}

		return $response;
	}
}
?>
