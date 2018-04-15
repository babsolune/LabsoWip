<?php
/*##################################################
 *                               CatalogDisplayCategoryController.class.php
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

class CatalogDisplayCategoryController extends ModuleController
{
	private $lang;
	private $tpl;
	private $config;
	private $notation_config;
	private $comments_config;

	private $category;

	public function execute(HTTPRequestCustom $request)
	{
		$this->init();

		$this->check_authorizations();

		$this->build_view($request);

		return $this->generate_response();
	}

	private function init()
	{
		$this->lang = LangLoader::get('common', 'catalog');
		$this->tpl = new FileTemplate('catalog/CatalogDisplaySeveralProductsController.tpl');
		$this->tpl->add_lang($this->lang);
		$this->config = CatalogConfig::load();
		$this->notation_config = new CatalogNotation();
		$this->comments_config = new CatalogComments();
	}

	private function build_view(HTTPRequestCustom $request)
	{
		$now = new Date();
		$mode = $request->get_getstring('sort', CatalogUrlBuilder::DEFAULT_SORT_MODE);
		$field = $request->get_getstring('field', CatalogUrlBuilder::DEFAULT_SORT_FIELD);
		$page = AppContext::get_request()->get_getint('page', 1);
		$subcategories_page = AppContext::get_request()->get_getint('subcategories_page', 1);

		$subcategories = CatalogService::get_categories_manager()->get_categories_cache()->get_children($this->get_category()->get_id(), CatalogService::get_authorized_categories($this->get_category()->get_id()));
		$subcategories_pagination = $this->get_subcategories_pagination(count($subcategories), $this->config->get_categories_number_per_page(), $field, $mode, $page, $subcategories_page);

		$nbr_cat_displayed = 0;
		foreach ($subcategories as $id => $category)
		{
			$nbr_cat_displayed++;

			if ($nbr_cat_displayed > $subcategories_pagination->get_display_from() && $nbr_cat_displayed <= ($subcategories_pagination->get_display_from() + $subcategories_pagination->get_number_items_per_page()))
			{
				$category_image = $category->get_image()->rel();

				$this->tpl->assign_block_vars('sub_categories_list', array(
					'C_CATEGORY_IMAGE' => !empty($category_image),
					'C_MORE_THAN_ONE_PRODUCT' => $category->get_elements_number() > 1,
					'CATEGORY_ID' => $category->get_id(),
					'CATEGORY_NAME' => $category->get_name(),
					'CATEGORY_IMAGE' => $category_image,
					'PRODUCTS_NUMBER' => $category->get_elements_number(),
					'U_CATEGORY' => CatalogUrlBuilder::display_category($category->get_id(), $category->get_rewrited_name())->rel()
				));
			}
		}

		$number_columns_cats_display_per_line_cats = ($nbr_cat_displayed > $this->config->get_columns_number_per_line()) ? $this->config->get_columns_number_per_line() : $nbr_cat_displayed;
		$number_columns_cats_display_per_line_cats = !empty($number_columns_cats_display_per_line_cats) ? $number_columns_cats_display_per_line_cats : 1;

		$condition = 'WHERE id_category = :id_category
		AND (approbation_type = 1 OR (approbation_type = 2 AND start_date < :timestamp_now AND (end_date > :timestamp_now OR end_date = 0)))';
		$parameters = array(
			'id_category' => $this->get_category()->get_id(),
			'timestamp_now' => $now->get_timestamp()
		);

		$pagination = $this->get_pagination($condition, $parameters, $field, $mode, $page, $subcategories_page);

		$sort_mode = ($mode == 'asc') ? 'ASC' : 'DESC';
		switch ($field)
		{
			case 'name':
				$sort_field = Product::SORT_ALPHABETIC;
				break;
			case 'download':
				$sort_field = Product::SORT_NUMBER_DOWNLOADS;
				break;
			case 'com':
				$sort_field = Product::SORT_NUMBER_COMMENTS;
				break;
			case 'note':
				$sort_field = Product::SORT_NOTATION;
				break;
			case 'author':
				$sort_field = Product::SORT_AUTHOR;
				break;
			case 'date':
				$sort_field = Product::SORT_DATE;
				break;
			default:
				$sort_field = Product::SORT_UPDATED_DATE;
				break;
		}

		$result = PersistenceContext::get_querier()->select('SELECT catalog.*, member.*, com.number_comments, notes.average_notes, notes.number_notes, note.note
		FROM ' . CatalogSetup::$catalog_table . ' catalog
		LEFT JOIN ' . DB_TABLE_MEMBER . ' member ON member.user_id = catalog.author_user_id
		LEFT JOIN ' . DB_TABLE_COMMENTS_TOPIC . ' com ON com.id_in_module = catalog.id AND com.module_id = \'catalog\'
		LEFT JOIN ' . DB_TABLE_AVERAGE_NOTES . ' notes ON notes.id_in_module = catalog.id AND notes.module_name = \'catalog\'
		LEFT JOIN ' . DB_TABLE_NOTE . ' note ON note.id_in_module = catalog.id AND note.module_name = \'catalog\' AND note.user_id = :user_id
		' . $condition . '
		ORDER BY ' . $sort_field . ' ' . $sort_mode . '
		LIMIT :number_items_per_page OFFSET :display_from', array_merge($parameters, array(
			'user_id' => AppContext::get_current_user()->get_id(),
			'number_items_per_page' => $pagination->get_number_items_per_page(),
			'display_from' => $pagination->get_display_from()
		)));

		$category_description = FormatingHelper::second_parse($this->get_category()->get_description());
		$number_columns_display_per_line = $this->config->get_columns_number_per_line();

		$this->tpl->put_all(array(
			'C_PRODUCT' => $result->get_rows_count() > 0,
			'C_MORE_THAN_ONE_PRODUCT' => $result->get_rows_count() > 1,
			'C_CATEGORY_DISPLAYED_SUMMARY' => $this->config->is_category_displayed_summary(),
			'C_CATEGORY_DISPLAYED_TABLE' => $this->config->is_category_displayed_table(),
			'C_CATEGORY_DESCRIPTION' => !empty($category_description),
			'C_SEVERAL_COLUMNS' => $number_columns_display_per_line > 1,
			'NUMBER_COLUMNS' => $number_columns_display_per_line,
			'C_AUTHOR_DISPLAYED' => $this->config->is_author_displayed(),
			'C_COMMENTS_ENABLED' => $this->comments_config->are_comments_enabled(),
			'C_NOTATION_ENABLED' => $this->notation_config->is_notation_enabled(),
			'C_NB_VIEW_ENABLED' => $this->config->get_nb_view_enabled(),
			'C_MODERATION' => CatalogAuthorizationsService::check_authorizations($this->get_category()->get_id())->moderation(),
			'C_PAGINATION' => $pagination->has_several_pages(),
			'C_CATEGORY' => true,
			'C_ROOT_CATEGORY' => $this->get_category()->get_id() == Category::ROOT_CATEGORY,
			'C_HIDE_NO_ITEM_MESSAGE' => $this->get_category()->get_id() == Category::ROOT_CATEGORY && ($nbr_cat_displayed != 0 || !empty($category_description)),
			'C_SUB_CATEGORIES' => $nbr_cat_displayed > 0,
			'C_SUBCATEGORIES_PAGINATION' => $subcategories_pagination->has_several_pages(),
			'SUBCATEGORIES_PAGINATION' => $subcategories_pagination->display(),
			'C_SEVERAL_CATS_COLUMNS' => $number_columns_cats_display_per_line_cats > 1,
			'NUMBER_CATS_COLUMNS' => $number_columns_cats_display_per_line_cats,
			'PAGINATION' => $pagination->display(),
			'TABLE_COLSPAN' => 4 + (int)$this->comments_config->are_comments_enabled() + (int)$this->notation_config->is_notation_enabled(),
			'ID_CAT' => $this->get_category()->get_id(),
			'CATEGORY_NAME' => $this->get_category()->get_name(),
			'CATEGORY_IMAGE' => $this->get_category()->get_image()->rel(),
			'CATEGORY_DESCRIPTION' => $category_description,
			'U_EDIT_CATEGORY' => $this->get_category()->get_id() == Category::ROOT_CATEGORY ? CatalogUrlBuilder::configuration()->rel() : CatalogUrlBuilder::edit_category($this->get_category()->get_id())->rel()
		));

		while ($row = $result->fetch())
		{
			$product = new Product();
			$product->set_properties($row);

			$keywords = $product->get_keywords();
			$has_keywords = count($keywords) > 0;

			$this->tpl->assign_block_vars('products', array_merge($product->get_array_tpl_vars(), array(
				'C_KEYWORDS' => $has_keywords
			)));

			if ($has_keywords)
				$this->build_keywords_view($keywords);
		}
		$result->dispose();

		$this->build_sorting_form($field, $mode);

		if($this->get_category()->get_id() == Category::ROOT_CATEGORY)
		{
			$now = new Date();
			$authorized_categories = CatalogService::get_authorized_categories($this->get_category()->get_id());

			$comments_config = new CatalogComments();

			$condition = 'WHERE id_category IN :authorized_categories
			AND (approbation_type = 1 OR (approbation_type = 2 AND start_date < :timestamp_now AND (end_date > :timestamp_now OR end_date = 0)))';
			$parameters = array(
				'authorized_categories' => $authorized_categories,
				'timestamp_now' => $now->get_timestamp()
			);

			// $home_page = AppContext::get_request()->get_getint('page', 1);
			// $home_pagination = $this->get_pagination($condition, $parameters, $page);

			$result_home = PersistenceContext::get_querier()->select('SELECT catalog.*, member.*, com.number_comments, notes.average_notes, notes.number_notes, note.note
			FROM '. CatalogSetup::$catalog_table .' catalog
			LEFT JOIN ' . DB_TABLE_MEMBER . ' member ON member.user_id = catalog.author_user_id
			LEFT JOIN ' . DB_TABLE_COMMENTS_TOPIC . ' com ON com.id_in_module = catalog.id AND com.module_id = \'catalog\'
			LEFT JOIN ' . DB_TABLE_AVERAGE_NOTES . ' notes ON notes.id_in_module = catalog.id AND notes.module_name = \'catalog\'
			LEFT JOIN ' . DB_TABLE_NOTE . ' note ON note.id_in_module = catalog.id AND note.module_name = \'catalog\' AND note.user_id = :user_id
			' . $condition . '
			ORDER BY catalog.creation_date DESC
			LIMIT 5', array_merge($parameters, array(
				'user_id' => AppContext::get_current_user()->get_id(),
				// 'number_items_per_page' => $home_pagination->get_number_items_per_page(),
				// 'display_from' => $home_pagination->get_display_from()
			)));

			// $number_columns_display_product = $this->config->get_number_columns_display_catalog();
			$this->tpl->put_all(array(
				'C_CATEGORY' => true,
				// 'C_DISPLAY_BLOCK_TYPE' => $this->config->get_display_type() == CatalogConfig::DISPLAY_BLOCK,
				// 'C_DISPLAY_LIST_TYPE' => $this->config->get_display_type() == CatalogConfig::DISPLAY_LIST,
				// 'C_DISPLAY_CONDENSED_CONTENT' => $this->config->get_display_condensed_enabled(),
				'C_COMMENTS_ENABLED' => $comments_config->are_comments_enabled(),
				'C_ROOT_CATEGORY' => $this->get_category()->get_id() == Category::ROOT_CATEGORY,
				'ID_CAT' => $this->get_category()->get_id(),
				'CATEGORY_NAME' => $this->get_category()->get_name(),
				'U_EDIT_CATEGORY' => $this->get_category()->get_id() == Category::ROOT_CATEGORY ? CatalogUrlBuilder::configuration()->rel() : CatalogUrlBuilder::edit_category($this->get_category()->get_id())->rel(),

				'C_NEWS_NO_AVAILABLE' => $result_home->get_rows_count() == 0,
				// 'C_PAGINATION' => $home_pagination->has_several_pages(),
				// 'PAGINATION' => $home_pagination->display(),

				// 'C_SEVERAL_COLUMNS' => $number_columns_display_product > 1,
				// 'NUMBER_COLUMNS' => $number_columns_display_product
			));

			while ($row = $result_home->fetch())
			{
				$product = new Product();
				$product->set_properties($row);

				$this->tpl->assign_block_vars('home_products', $product->get_array_tpl_vars());
			}
			$result_home->dispose();

		}

	}

	private function build_sorting_form($field, $mode)
	{
		$common_lang = LangLoader::get('common');

		$form = new HTMLForm(__CLASS__, '', false);
		$form->set_css_class('options');

		$fieldset = new FormFieldsetHorizontal('filters', array('description' => $common_lang['sort_by']));
		$form->add_fieldset($fieldset);

		$sort_options = array(
			new FormFieldSelectChoiceOption($common_lang['form.date.update'], 'updated_date'),
			new FormFieldSelectChoiceOption($common_lang['form.date.creation'], 'date'),
			new FormFieldSelectChoiceOption($common_lang['form.name'], 'name'),
			new FormFieldSelectChoiceOption($this->lang['downloads_number'], 'download'),
			new FormFieldSelectChoiceOption($common_lang['author'], 'author')
		);

		if ($this->comments_config->are_comments_enabled())
			$sort_options[] = new FormFieldSelectChoiceOption($common_lang['sort_by.number_comments'], 'com');

		if ($this->notation_config->is_notation_enabled())
			$sort_options[] = new FormFieldSelectChoiceOption($common_lang['sort_by.best_note'], 'note');

		$fieldset->add_field(new FormFieldSimpleSelectChoice('sort_fields', '', $field, $sort_options,
			array('events' => array('change' => 'document.location = "'. CatalogUrlBuilder::display_category($this->category->get_id(), $this->category->get_rewrited_name())->rel() .'" + HTMLForms.getField("sort_fields").getValue() + "/" + HTMLForms.getField("sort_mode").getValue();'))
		));

		$fieldset->add_field(new FormFieldSimpleSelectChoice('sort_mode', '', $mode,
			array(
				new FormFieldSelectChoiceOption($common_lang['sort.asc'], 'asc'),
				new FormFieldSelectChoiceOption($common_lang['sort.desc'], 'desc')
			),
			array('events' => array('change' => 'document.location = "' . CatalogUrlBuilder::display_category($this->category->get_id(), $this->category->get_rewrited_name())->rel() . '" + HTMLForms.getField("sort_fields").getValue() + "/" + HTMLForms.getField("sort_mode").getValue();'))
		));

		$this->tpl->put('SORT_FORM', $form->display());
	}

	private function get_pagination($condition, $parameters, $field, $mode, $page, $subcategories_page)
	{
		$products_number = CatalogService::count($condition, $parameters);

		$pagination = new ModulePagination($page, $products_number, (int)CatalogConfig::load()->get_items_number_per_page());
		$pagination->set_url(CatalogUrlBuilder::display_category($this->get_category()->get_id(), $this->get_category()->get_rewrited_name(), $field, $mode, '%d', $subcategories_page));

		if ($pagination->current_page_is_empty() && $page > 1)
		{
			$error_controller = PHPBoostErrors::unexisting_page();
			DispatchManager::redirect($error_controller);
		}

		return $pagination;
	}

	private function get_subcategories_pagination($subcategories_number, $categories_number_per_page, $field, $mode, $page, $subcategories_page)
	{
		$pagination = new ModulePagination($subcategories_page, $subcategories_number, (int)$categories_number_per_page);
		$pagination->set_url(CatalogUrlBuilder::display_category($this->get_category()->get_id(), $this->get_category()->get_rewrited_name(), $field, $mode, $page, '%d'));

		if ($pagination->current_page_is_empty() && $subcategories_page > 1)
		{
			$error_controller = PHPBoostErrors::unexisting_page();
			DispatchManager::redirect($error_controller);
		}

		return $pagination;
	}

	private function get_category()
	{
		if ($this->category === null)
		{
			$id = AppContext::get_request()->get_getint('id_category', 0);
			if (!empty($id))
			{
				try {
					$this->category = CatalogService::get_categories_manager()->get_categories_cache()->get_category($id);
				} catch (CategoryNotFoundException $e) {
					$error_controller = PHPBoostErrors::unexisting_page();
   					DispatchManager::redirect($error_controller);
				}
			}
			else
			{
				$this->category = CatalogService::get_categories_manager()->get_categories_cache()->get_category(Category::ROOT_CATEGORY);
			}
		}
		return $this->category;
	}

	private function build_keywords_view($keywords)
	{
		$nbr_keywords = count($keywords);

		$i = 1;
		foreach ($keywords as $keyword)
		{
			$this->tpl->assign_block_vars('products.keywords', array(
				'C_SEPARATOR' => $i < $nbr_keywords,
				'NAME' => $keyword->get_name(),
				'URL' => CatalogUrlBuilder::display_tag($keyword->get_rewrited_name())->rel(),
			));
			$i++;
		}
	}

	private function check_authorizations()
	{
		if (AppContext::get_current_user()->is_guest())
		{
			if (($this->config->are_descriptions_displayed_to_guests() && (!Authorizations::check_auth(RANK_TYPE, User::MEMBER_LEVEL, $this->get_category()->get_authorizations(), Category::READ_AUTHORIZATIONS) || $this->config->get_category_display_type() == CatalogConfig::DISPLAY_ALL_CONTENT)) || (!$this->config->are_descriptions_displayed_to_guests() && !CatalogAuthorizationsService::check_authorizations($this->get_category()->get_id())->read()))
			{
				$error_controller = PHPBoostErrors::user_not_authorized();
				DispatchManager::redirect($error_controller);
			}
		}
		else
		{
			if (!CatalogAuthorizationsService::check_authorizations($this->get_category()->get_id())->read())
			{
				$error_controller = PHPBoostErrors::user_not_authorized();
				DispatchManager::redirect($error_controller);
			}
		}
	}

	private function generate_response()
	{
		$response = new SiteDisplayResponse($this->tpl);

		$graphical_environment = $response->get_graphical_environment();

		if ($this->get_category()->get_id() != Category::ROOT_CATEGORY)
			$graphical_environment->set_page_title($this->get_category()->get_name(), $this->lang['module_title']);
		else
			$graphical_environment->set_page_title($this->lang['module_title']);

		$graphical_environment->get_seo_meta_data()->set_description($this->get_category()->get_description());
		$graphical_environment->get_seo_meta_data()->set_canonical_url(CatalogUrlBuilder::display_category($this->get_category()->get_id(), $this->get_category()->get_rewrited_name(), AppContext::get_request()->get_getstring('field', 'date'), AppContext::get_request()->get_getstring('sort', 'desc'), AppContext::get_request()->get_getint('page', 1)));

		$breadcrumb = $graphical_environment->get_breadcrumb();
		$breadcrumb->add($this->lang['module_title'], CatalogUrlBuilder::home());

		$categories = array_reverse(CatalogService::get_categories_manager()->get_parents($this->get_category()->get_id(), true));
		foreach ($categories as $id => $category)
		{
			if ($category->get_id() != Category::ROOT_CATEGORY)
				$breadcrumb->add($category->get_name(), CatalogUrlBuilder::display_category($category->get_id(), $category->get_rewrited_name()));
		}

		return $response;
	}

	public static function get_view()
	{
		$object = new self();
		$object->init();
		$object->check_authorizations();
		$object->build_view(AppContext::get_request());
		return $object->tpl;
	}
}
?>
