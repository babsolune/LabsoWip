<?php
/*##################################################
 *                               CatalogFormProductController.class.php
 *                            -------------------
 *   begin                : August 24, 2014
 *   copyright            : (C) 2014 Julien BRISWALTER
 *   email                : j1.seth@phpboost.com
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
 * @author Julien BRISWALTER <j1.seth@phpboost.com>
 */

class CatalogFormProductController extends ModuleController
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

	private $config;

	private $product;
	private $is_new_product;

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
		$this->config = CatalogConfig::load();
		$this->lang = LangLoader::get('common', 'catalog');
		$this->common_lang = LangLoader::get('common');
	}

	private function build_form(HTTPRequestCustom $request)
	{
		$form = new HTMLForm(__CLASS__);

		$fieldset = new FormFieldsetHTML('product',  $this->get_product()->get_id() === null ? $this->lang['catalog.add'] : $this->lang['catalog.edit']);
		$form->add_fieldset($fieldset);

		$fieldset->add_field(new FormFieldTextEditor('name', $this->common_lang['form.name'], $this->get_product()->get_name(), array('required' => true)));

		if (CatalogService::get_categories_manager()->get_categories_cache()->has_categories())
		{
			$search_category_children_options = new SearchCategoryChildrensOptions();
			$search_category_children_options->add_authorizations_bits(Category::CONTRIBUTION_AUTHORIZATIONS);
			$search_category_children_options->add_authorizations_bits(Category::WRITE_AUTHORIZATIONS);
			$fieldset->add_field(CatalogService::get_categories_manager()->get_select_categories_form_field('id_category', $this->common_lang['form.category'], $this->get_product()->get_id_category(), $search_category_children_options));
		}

		$fieldset->add_field(new FormFieldCheckbox('download_enabled', $this->lang['catalog.form.download.enabled'], $this->get_product()->is_downloadable(),
			array('events' => array('click' => '
			if (HTMLForms.getField("download_enabled").getValue()) {
				HTMLForms.getField("product_url").enable();
			} else {
				HTMLForms.getField("product_url").disable();
			}'))
		));

		$fieldset->add_field(new FormFieldUploadFile('product_url', $this->lang['catalog.form.product.url'], $this->get_product()->get_product_url()->relative(),
			array('hidden' => !$this->get_product()->is_downloadable())
		));

		$fieldset->add_field(new FormFieldDecimalNumberEditor('price', $this->lang['catalog.form.price'], $this->get_product()->get_price(),
			array(
				'min' => 0,
				'step' => 0.01,
				'description' => $this->lang['catalog.form.decimal'],
				'required' => true
		)));

		$fieldset->add_field(new FormFieldCheckbox('promotion_enabled', $this->lang['catalog.form.promotion.enabled'], $this->get_product()->get_promotion_enabled(),
			array('events' => array('click' => '
			if (HTMLForms.getField("promotion_enabled").getValue()) {
				HTMLForms.getField("promotion").enable();
			} else {
				HTMLForms.getField("promotion").disable();
			}'))
		));

		$fieldset->add_field(new FormFieldDecimalNumberEditor('promotion', $this->lang['catalog.form.promotion'], $this->get_product()->get_promotion(),
			array(
				'min' => 0, 'step' => 0.01,
				'description' => $this->lang['catalog.form.decimal'],
				'hidden' => !$this->get_product()->get_promotion_enabled()
		)));

		$fieldset->add_field(new FormFieldCheckbox('flash_sales_enabled', $this->lang['product.in.flash.sales'], $this->get_product()->get_flash_sales_enabled()));

		if ($this->get_product()->get_id() !== null && $this->get_product()->is_downloadable() && $this->get_product()->get_number_downloads() > 0)
		{
			$fieldset->add_field(new FormFieldCheckbox('reset_number_downloads', $this->lang['catalog.form.reset_number_downloads']));
		}

		$fieldset->add_field(new FormFieldRichTextEditor('contents', $this->lang['catalog.form.full.description'], $this->get_product()->get_contents(), array('rows' => 15, 'required' => true)));

		$fieldset->add_field(new FormFieldCheckbox('description_enabled', $this->lang['catalog.form.description.enabled'], $this->get_product()->is_description_enabled(),
			array('description' => StringVars::replace_vars($this->lang['catalog.form.description.enabled.desc'], array('number' => CatalogConfig::NUMBER_CARACTERS_BEFORE_CUT)), 'events' => array('click' => '
			if (HTMLForms.getField("description_enabled").getValue()) {
				HTMLForms.getField("description").enable();
			} else {
				HTMLForms.getField("description").disable();
			}'))
		));

		$fieldset->add_field(new FormFieldRichTextEditor('description', $this->lang['catalog.form.short.description'], $this->get_product()->get_description(), array(
			'hidden' => !$this->get_product()->is_description_enabled(),
		)));

		if ($this->config->is_author_displayed())
		{
			$fieldset->add_field(new FormFieldCheckbox('author_custom_name_enabled', $this->lang['catalog.form.author_custom_name_enabled'], $this->get_product()->is_author_custom_name_enabled(),
				array('events' => array('click' => '
				if (HTMLForms.getField("author_custom_name_enabled").getValue()) {
					HTMLForms.getField("author_custom_name").enable();
				} else {
					HTMLForms.getField("author_custom_name").disable();
				}'))
			));

			$fieldset->add_field(new FormFieldTextEditor('author_custom_name', $this->lang['catalog.form.author_custom_name'], $this->get_product()->get_author_custom_name(), array(
				'hidden' => !$this->get_product()->is_author_custom_name_enabled(),
			)));
		}

		$other_fieldset = new FormFieldsetHTML('other', $this->common_lang['form.other']);
		$form->add_fieldset($other_fieldset);

		$other_fieldset->add_field(new FormFieldUploadPictureFile('picture', $this->common_lang['form.picture'], $this->get_product()->get_picture()->relative()));

		$other_fieldset->add_field(new CatalogFormFieldCarousel('carousel', $this->lang['catalog.form.carousel'], $this->get_product()->get_carousel()));

		$other_fieldset->add_field(new CatalogFormFieldColor('product_color', $this->lang['catalog.form.color'], $this->get_product()->get_product_color()));

		$other_fieldset->add_field(new CatalogFormFieldSize('product_size', $this->lang['catalog.form.size'], $this->get_product()->get_product_size()));

		$other_fieldset->add_field(new CatalogFormFieldDetails('product_details', $this->lang['catalog.form.details'], $this->get_product()->get_product_details()));

		$other_fieldset->add_field(CatalogService::get_keywords_manager()->get_form_field($this->get_product()->get_id(), 'keywords', $this->common_lang['form.keywords'], array('description' => $this->common_lang['form.keywords.description'])));

		if (CatalogAuthorizationsService::check_authorizations($this->get_product()->get_id_category())->moderation())
		{
			$publication_fieldset = new FormFieldsetHTML('publication', $this->common_lang['form.approbation']);
			$form->add_fieldset($publication_fieldset);

			$publication_fieldset->add_field(new FormFieldDateTime('creation_date', $this->common_lang['form.date.creation'], $this->get_product()->get_creation_date(),
				array('required' => true)
			));

			if (!$this->get_product()->is_visible())
			{
				$publication_fieldset->add_field(new FormFieldCheckbox('update_creation_date', $this->common_lang['form.update.date.creation'], false, array('hidden' => $this->get_product()->get_status() != Product::NOT_APPROVAL)
				));
			}

			$publication_fieldset->add_field(new FormFieldSimpleSelectChoice('approbation_type', $this->common_lang['form.approbation'], $this->get_product()->get_approbation_type(),
				array(
					new FormFieldSelectChoiceOption($this->common_lang['form.approbation.not'], Product::NOT_APPROVAL),
					new FormFieldSelectChoiceOption($this->common_lang['form.approbation.now'], Product::APPROVAL_NOW),
					new FormFieldSelectChoiceOption($this->common_lang['status.approved.date'], Product::APPROVAL_DATE),
				),
				array('events' => array('change' => '
				if (HTMLForms.getField("approbation_type").getValue() == 2) {
					jQuery("#' . __CLASS__ . '_start_date_field").show();
					HTMLForms.getField("end_date_enabled").enable();
				} else {
					jQuery("#' . __CLASS__ . '_start_date_field").hide();
					HTMLForms.getField("end_date_enabled").disable();
				}'))
			));

			$publication_fieldset->add_field(new FormFieldDateTime('start_date', $this->common_lang['form.date.start'], ($this->get_product()->get_start_date() === null ? new Date() : $this->get_product()->get_start_date()), array('hidden' => ($this->get_product()->get_approbation_type() != Product::APPROVAL_DATE))));

			$publication_fieldset->add_field(new FormFieldCheckbox('end_date_enabled', $this->common_lang['form.date.end.enable'], $this->get_product()->is_end_date_enabled(), array(
			'hidden' => ($this->get_product()->get_approbation_type() != Product::APPROVAL_DATE),
			'events' => array('click' => '
			if (HTMLForms.getField("end_date_enabled").getValue()) {
				HTMLForms.getField("end_date").enable();
			} else {
				HTMLForms.getField("end_date").disable();
			}'
			))));

			$publication_fieldset->add_field(new FormFieldDateTime('end_date', $this->common_lang['form.date.end'], ($this->get_product()->get_end_date() === null ? new Date() : $this->get_product()->get_end_date()), array('hidden' => !$this->get_product()->is_end_date_enabled())));
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
		if ($this->is_contributor_member())
		{
			$fieldset = new FormFieldsetHTML('contribution', LangLoader::get_message('contribution', 'user-common'));
			$fieldset->set_description(MessageHelper::display($this->lang['catalog.form.contribution.explain'] . ' ' . LangLoader::get_message('contribution.explain', 'user-common'), MessageHelper::WARNING)->render());
			$form->add_fieldset($fieldset);

			$fieldset->add_field(new FormFieldRichTextEditor('contribution_description', LangLoader::get_message('contribution.description', 'user-common'), '', array('description' => LangLoader::get_message('contribution.description.explain', 'user-common'))));
		}
	}

	private function is_contributor_member()
	{
		return (!CatalogAuthorizationsService::check_authorizations()->write() && CatalogAuthorizationsService::check_authorizations()->contribution());
	}

	private function get_product()
	{
		if ($this->product === null)
		{
			$id = AppContext::get_request()->get_getint('id', 0);
			if (!empty($id))
			{
				try {
					$this->product = CatalogService::get_product('WHERE catalog.id=:id', array('id' => $id));
				} catch (RowNotFoundException $e) {
					$error_controller = PHPBoostErrors::unexisting_page();
					DispatchManager::redirect($error_controller);
				}
			}
			else
			{
				$this->is_new_product = true;
				$this->product = new Product();
				$this->product->init_default_properties(AppContext::get_request()->get_getint('id_category', Category::ROOT_CATEGORY));
			}
		}
		return $this->product;
	}

	private function check_authorizations()
	{
		$product = $this->get_product();

		if ($product->get_id() === null)
		{
			if (!$product->is_authorized_to_add())
			{
				$error_controller = PHPBoostErrors::user_not_authorized();
				DispatchManager::redirect($error_controller);
			}
		}
		else
		{
			if (!$product->is_authorized_to_edit())
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
		$product = $this->get_product();

		$product->set_name($this->form->get_value('name'));
		$product->set_rewrited_name(Url::encode_rewrite($product->get_name()));

		if (CatalogService::get_categories_manager()->get_categories_cache()->has_categories())
			$product->set_id_category($this->form->get_value('id_category')->get_raw_value());

		$product->set_product_url(new Url($this->form->get_value('product_url')));


		$product->set_contents($this->form->get_value('contents'));
		$product->set_description(($this->form->get_value('description_enabled') ? $this->form->get_value('description') : ''));
		$product->set_picture(new Url($this->form->get_value('picture')));

		$product->set_product_url(new Url($this->form->get_value('product_url')));
		$product->set_promotion($this->form->get_value('promotion'));
		$product->set_promotion_enabled(!empty($this->form->get_value('promotion')));
		$product->set_flash_sales_enabled($this->form->get_value('flash_sales_enabled'));
		$product->set_price($this->form->get_value('price'));

		$product->set_carousel($this->form->get_value('carousel'));
		$product->set_product_color($this->form->get_value('product_color'));
		$product->set_product_size($this->form->get_value('product_size'));
		$product->set_product_details($this->form->get_value('product_details'));

		if ($this->config->is_author_displayed())
			$product->set_author_custom_name(($this->form->get_value('author_custom_name') && $this->form->get_value('author_custom_name') !== $product->get_author_user()->get_display_name() ? $this->form->get_value('author_custom_name') : ''));

		if ($this->form->get_value('download_enable'))
		{
			$product_size = Url::get_url_product_size($product->get_product_url());
			$product_size = (empty($product_size) && $product->get_size()) ? $product->get_size() : $product_size;

			$product->set_size($product_size);

			if ($product->get_id() !== null && $this->get_product()->is_downloadable() && $product->get_number_downloads() > 0 && $this->form->get_value('reset_number_downloads'))
			{
				$product->set_number_downloads(0);
			}
		}

		if (!CatalogAuthorizationsService::check_authorizations($product->get_id_category())->moderation())
		{
			$product->clean_start_and_end_date();

			if (CatalogAuthorizationsService::check_authorizations($product->get_id_category())->contribution() && !CatalogAuthorizationsService::check_authorizations($product->get_id_category())->write())
				$product->set_approbation_type(Product::NOT_APPROVAL);
		}
		else
		{

			if ($this->form->get_value('update_creation_date'))
			{
				$product->set_creation_date(new Date());
			}
			else
			{
				$product->set_creation_date($this->form->get_value('creation_date'));
			}

			$product->set_approbation_type($this->form->get_value('approbation_type')->get_raw_value());
			if ($product->get_approbation_type() == Product::APPROVAL_DATE)
			{
				$deferred_operations = $this->config->get_deferred_operations();

				$old_start_date = $product->get_start_date();
				$start_date = $this->form->get_value('start_date');
				$product->set_start_date($start_date);

				if ($old_start_date !== null && $old_start_date->get_timestamp() != $start_date->get_timestamp() && in_array($old_start_date->get_timestamp(), $deferred_operations))
				{
					$key = array_search($old_start_date->get_timestamp(), $deferred_operations);
					unset($deferred_operations[$key]);
				}

				if (!in_array($start_date->get_timestamp(), $deferred_operations))
					$deferred_operations[] = $start_date->get_timestamp();

				if ($this->form->get_value('end_date_enabled'))
				{
					$old_end_date = $product->get_end_date();
					$end_date = $this->form->get_value('end_date');
					$product->set_end_date($end_date);

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
					$product->clean_end_date();
				}

				$this->config->set_deferred_operations($deferred_operations);
				CatalogConfig::save();
			}
			else
			{
				$product->clean_start_and_end_date();
			}
		}

		if ($this->is_new_product)
		{
			$id = CatalogService::add($product);
		}
		else
		{
			$product->set_updated_date(new Date());
			$id = $product->get_id();
			CatalogService::update($product);
		}

		$this->contribution_actions($product, $id);

		CatalogService::get_keywords_manager()->put_relations($id, $this->form->get_value('keywords'));

		Feed::clear_cache('catalog');
		CatalogCache::invalidate();
		CatalogCategoriesCache::invalidate();
	}

	private function contribution_actions(Product $product, $id)
	{
		if ($product->get_id() === null)
		{
			if ($this->is_contributor_member())
			{
				$contribution = new Contribution();
				$contribution->set_id_in_module($id);
				$contribution->set_description(stripslashes($this->form->get_value('contribution_description')));
				$contribution->set_entitled($product->get_name());
				$contribution->set_fixing_url(CatalogUrlBuilder::edit($id)->relative());
				$contribution->set_poster_id(AppContext::get_current_user()->get_id());
				$contribution->set_module('catalog');
				$contribution->set_auth(
					Authorizations::capture_and_shift_bit_auth(
						CatalogService::get_categories_manager()->get_heritated_authorizations($product->get_id_category(), Category::MODERATION_AUTHORIZATIONS, Authorizations::AUTH_CHILD_PRIORITY),
						Category::MODERATION_AUTHORIZATIONS, Contribution::CONTRIBUTION_AUTH_BIT
					)
				);
				ContributionService::save_contribution($contribution);
			}
		}
		else
		{
			$corresponding_contributions = ContributionService::find_by_criteria('catalog', $id);
			if (count($corresponding_contributions) > 0)
			{
				foreach ($corresponding_contributions as $contribution)
				{
					$contribution->set_status(Event::EVENT_STATUS_PROCESSED);
					ContributionService::save_contribution($contribution);
				}
			}
		}
		$product->set_id($id);
	}

	private function redirect()
	{
		$product = $this->get_product();
		$category = $product->get_category();

		if ($this->is_new_product && $this->is_contributor_member() && !$product->is_visible())
		{
			DispatchManager::redirect(new UserContributionSuccessController());
		}
		elseif ($product->is_visible())
		{
			if ($this->is_new_product)
				AppContext::get_response()->redirect(CatalogUrlBuilder::display($category->get_id(), $category->get_rewrited_name(), $product->get_id(), $product->get_rewrited_name()), StringVars::replace_vars($this->lang['catalog.message.success.add'], array('name' => $product->get_name())));
			else
				AppContext::get_response()->redirect(($this->form->get_value('referrer') ? $this->form->get_value('referrer') : CatalogUrlBuilder::display($category->get_id(), $category->get_rewrited_name(), $product->get_id(), $product->get_rewrited_name())), StringVars::replace_vars($this->lang['catalog.message.success.edit'], array('name' => $product->get_name())));
		}
		else
		{
			if ($this->is_new_product)
				AppContext::get_response()->redirect(CatalogUrlBuilder::display_pending(), StringVars::replace_vars($this->lang['catalog.message.success.add'], array('name' => $product->get_name())));
			else
				AppContext::get_response()->redirect(($this->form->get_value('referrer') ? $this->form->get_value('referrer') : CatalogUrlBuilder::display_pending()), StringVars::replace_vars($this->lang['catalog.message.success.edit'], array('name' => $product->get_name())));
		}
	}

	private function generate_response(View $tpl)
	{
		$product = $this->get_product();

		$response = new SiteDisplayResponse($tpl);
		$graphical_environment = $response->get_graphical_environment();

		$breadcrumb = $graphical_environment->get_breadcrumb();
		$breadcrumb->add($this->lang['module_title'], CatalogUrlBuilder::home());

		if ($product->get_id() === null)
		{
			$graphical_environment->set_page_title($this->lang['catalog.add'], $this->lang['module_title']);
			$breadcrumb->add($this->lang['catalog.add'], CatalogUrlBuilder::add($product->get_id_category()));
			$graphical_environment->get_seo_meta_data()->set_description($this->lang['catalog.add']);
			$graphical_environment->get_seo_meta_data()->set_canonical_url(CatalogUrlBuilder::add($product->get_id_category()));
		}
		else
		{
			$graphical_environment->set_page_title($this->lang['catalog.edit'], $this->lang['module_title']);
			$graphical_environment->get_seo_meta_data()->set_description($this->lang['catalog.edit']);
			$graphical_environment->get_seo_meta_data()->set_canonical_url(CatalogUrlBuilder::edit($product->get_id()));

			$categories = array_reverse(CatalogService::get_categories_manager()->get_parents($product->get_id_category(), true));
			foreach ($categories as $id => $category)
			{
				if ($category->get_id() != Category::ROOT_CATEGORY)
					$breadcrumb->add($category->get_name(), CatalogUrlBuilder::display_category($category->get_id(), $category->get_rewrited_name()));
			}
			$category = $product->get_category();
			$breadcrumb->add($product->get_name(), CatalogUrlBuilder::display($category->get_id(), $category->get_rewrited_name(), $product->get_id(), $product->get_rewrited_name()));
			$breadcrumb->add($this->lang['catalog.edit'], CatalogUrlBuilder::edit($product->get_id()));
		}

		return $response;
	}
}
?>
