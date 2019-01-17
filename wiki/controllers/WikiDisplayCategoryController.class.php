<?php
/**
 * @copyright 	&copy; 2005-2019 PHPBoost
 * @license 	https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @version   	PHPBoost 5.2 - last update: 2018 05 25
 * @since   	PHPBoost 5.1 - 2018 05 25
*/

class WikiDisplayCategoryController extends ModuleController
{
	private $lang;
	private $config;
	private $category;
	private $content_management_config;

	public function execute(HTTPRequestCustom $request)
	{
		$this->init();

		$this->check_authorizations();

		$this->build_view();

		return $this->generate_response();
	}

	private function init()
	{
		$this->lang = LangLoader::get('common', 'wiki');
		$this->view = new FileTemplate('wiki/WikiDisplayCategoryController.tpl');
		$this->view->add_lang($this->lang);
		$this->config = WikiConfig::load();

		$this->comments_config = CommentsConfig::load();
		$this->content_management_config = ContentManagementConfig::load();
	}

	private function build_view()
	{
		$now = new Date();

		$this->build_categories_listing_view($now);
		$this->build_documents_listing_view($now);
	}

	private function build_documents_listing_view(Date $now)
	{
		$condition = 'WHERE id_category = :id_category
		AND (published = 1 OR (published = 2 AND publishing_start_date < :timestamp_now AND (publishing_end_date > :timestamp_now OR publishing_end_date = 0)))';
		$parameters = array(
			'id_category' => $this->get_category()->get_id(),
			'timestamp_now' => $now->get_timestamp()
		);

		$result = PersistenceContext::get_querier()->select('SELECT wiki.*, member.*
		FROM ' . WikiSetup::$wiki_table . ' wiki
		LEFT JOIN ' . DB_TABLE_MEMBER . ' member ON member.user_id = wiki.author_user_id
		' . $condition . '
		ORDER BY order_id', array_merge($parameters, array(
			'user_id' => AppContext::get_current_user()->get_id()
		)));

		$number_columns_display_per_line = $this->config->get_number_cols_display_per_line();

		$this->view->put_all(array(
			'C_MODERATION' => WikiAuthorizationsService::check_authorizations($this->get_category()->get_id())->moderation(),
			'C_DOCUMENTS' => $result->get_rows_count() > 0,
			'DOCUMENTS_NUMBER' => $result->get_rows_count(),
			'C_MORE_THAN_ONE_DOCUMENT' => $result->get_rows_count() > 1,
			'C_TABLE' => $this->config->get_display_type() == WikiConfig::DISPLAY_TABLE,
			'C_MOSAIC' => $this->config->get_display_type() == WikiConfig::DISPLAY_MOSAIC,
			'C_DISPLAY_REORDER_LINK' => $result->get_rows_count() > 1 && WikiAuthorizationsService::check_authorizations($this->get_category()->get_id())->moderation(),
			'C_DISPLAY_CATS_ICON' => $this->config->are_cats_icon_enabled(),
			'C_DISPLAY_CATS_COLOR' => $this->config->are_cats_color_enabled(),
			'C_NO_DOCUMENT_AVAILABLE' => $result->get_rows_count() == 0,
			'C_SEVERAL_COLUMNS' => $number_columns_display_per_line > 1,
			'NUMBER_COLUMNS' => $number_columns_display_per_line,
			'C_ONE_DOCUMENT_AVAILABLE' => $result->get_rows_count() == 1,
			'C_TWO_DOCUMENTS_AVAILABLE' => $result->get_rows_count() == 2,
			'ID_CAT' => $this->get_category()->get_id(),
			'U_EDIT_CATEGORY' => $this->get_category()->get_id() == Category::ROOT_CATEGORY ? WikiUrlBuilder::configuration()->rel() : WikiUrlBuilder::edit_category($this->get_category()->get_id())->rel(),
			'U_REORDER_ITEMS' => WikiUrlBuilder::reorder_items($this->get_category()->get_id(), $this->get_category()->get_rewrited_name())->rel(),
		));

		while($row = $result->fetch())
		{
			$document = new Document();
			$document->set_properties($row);

			$keywords = $document->get_keywords();
			$has_keywords = count($keywords) > 0;

			if ($has_keywords)
				$this->build_keywords_view($keywords);

			$this->view->assign_block_vars('items', $document->get_array_tpl_vars(), array(
				'C_KEYWORDS' => $has_keywords
			));
		}
		$result->dispose();
	}

	private function build_categories_listing_view(Date $now)
	{
		$subcategories = WikiService::get_categories_manager()->get_categories_cache()->get_children($this->get_category()->get_id(), WikiService::get_authorized_categories($this->get_category()->get_id()));

		$nbr_cat_displayed = 0;
		foreach ($subcategories as $id => $category)
		{
			$nbr_cat_displayed++;

			$category_image = $category->get_image()->rel();

			$this->view->assign_block_vars('sub_categories_list', array(
				'C_CATEGORY_IMAGE' => !empty($category_image),
				'C_MORE_THAN_ONE_DOCUMENT' => $category->get_elements_number() > 1,
				'C_CATEGORY_DESCRIPTION' => !empty(FormatingHelper::second_parse($category->get_description())),
				'CATEGORY_ID' => $category->get_id(),
				'CATEGORY_NAME' => $category->get_name(),
				'CATEGORY_COLOR' => $category->get_color(),
				'CATEGORY_DESCRIPTION' => FormatingHelper::second_parse($category->get_description()),
				'CATEGORY_IMAGE' => $category_image,
				'DOCUMENTS_NUMBER' => $category->get_elements_number(),
				'U_CATEGORY' => WikiUrlBuilder::display_category($category->get_id(), $category->get_rewrited_name())->rel()
			));
		}

		$nbr_column_cats_per_line = ($nbr_cat_displayed > $this->config->get_number_cols_display_per_line()) ? $this->config->get_number_cols_display_per_line() : $nbr_cat_displayed;
		$nbr_column_cats_per_line = !empty($nbr_column_cats_per_line) ? $nbr_column_cats_per_line : 1;

		$category_description = FormatingHelper::second_parse($this->get_category()->get_description());

		$this->view->put_all(array(
			'C_CATEGORY' => true,
			'C_ROOT_CATEGORY' => $this->get_category()->get_id() == Category::ROOT_CATEGORY,
			'C_HIDE_NO_ITEM_MESSAGE' => $this->get_category()->get_id() == Category::ROOT_CATEGORY && ($nbr_cat_displayed != 0 || !empty($category_description)),
			'C_CATEGORY_DESCRIPTION' => !empty($category_description),
			'C_SUB_CATEGORIES' => $nbr_cat_displayed > 0,
			'CATEGORY_NAME' => $this->get_category()->get_name(),
			'CATEGORY_IMAGE' => $this->get_category()->get_image()->rel(),
			'CATEGORY_DESCRIPTION' => $category_description,
			'C_SEVERAL_CATS_COLUMNS' => $nbr_column_cats_per_line > 1,
			'NUMBER_CATS_COLUMNS' => $nbr_column_cats_per_line
		));
	}

	private function get_category()
	{
		if ($this->category === null)
		{
			$id = AppContext::get_request()->get_getstring('id_category', 0);
			if (!empty($id))
			{
				try {
					$this->category = WikiService::get_categories_manager()->get_categories_cache()->get_category($id);
				} catch (CategoryNotFoundException $e) {
					$error_controller = PHPBoostErrors::unexisting_page();
					DispatchManager::redirect($error_controller);
				}
			}
			else
			{
				$this->category = WikiService::get_categories_manager()->get_categories_cache()->get_category(Category::ROOT_CATEGORY);
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
			$this->view->assign_block_vars('keywords', array(
				'C_SEPARATOR' => $i < $nbr_keywords,
				'NAME' => $keyword->get_name(),
				'URL' => WikiUrlBuilder::display_tag($keyword->get_rewrited_name())->rel(),
			));
			$i++;
		}
	}

	private function check_authorizations()
	{
		if (AppContext::get_current_user()->is_guest())
		{
			if (($this->config->are_descriptions_displayed_to_guests() && !Authorizations::check_auth(RANK_TYPE, User::MEMBER_LEVEL, $this->get_category()->get_authorizations(), Category::READ_AUTHORIZATIONS)) || (!$this->config->are_descriptions_displayed_to_guests() && !WikiAuthorizationsService::check_authorizations($this->get_category()->get_id())->read()))
			{
				$error_controller = PHPBoostErrors::user_not_authorized();
				DispatchManager::redirect($error_controller);
			}
		}
		else
		{
			if (!WikiAuthorizationsService::check_authorizations($this->get_category()->get_id())->read())
			{
				$error_controller = PHPBoostErrors::user_not_authorized();
				DispatchManager::redirect($error_controller);
			}
		}
	}

	private function generate_response()
	{
		$response = new SiteDisplayResponse($this->view);

		$graphical_environment = $response->get_graphical_environment();

		if ($this->category->get_id() != Category::ROOT_CATEGORY)
			$graphical_environment->set_page_title($this->category->get_name(), $this->lang['module.title']);
		else
			$graphical_environment->set_page_title($this->lang['module.title']);

		$graphical_environment->get_seo_meta_data()->set_description($this->category->get_description());
		$graphical_environment->get_seo_meta_data()->set_canonical_url(WikiUrlBuilder::display_category($this->category->get_id(), $this->category->get_rewrited_name()));

		$breadcrumb = $graphical_environment->get_breadcrumb();
		$breadcrumb->add($this->lang['module.title'], WikiUrlBuilder::home());

		$categories = array_reverse(WikiService::get_categories_manager()->get_parents($this->category->get_id(), true));
		foreach ($categories as $id => $category)
		{
			if ($category->get_id() != Category::ROOT_CATEGORY)
				$breadcrumb->add($category->get_name(), WikiUrlBuilder::display_category($category->get_id(), $category->get_rewrited_name()));
		}

		return $response;
	}

	public static function get_view()
	{
		$object = new self();
		$object->init();
		$object->check_authorizations();
		$object->build_view();
		return $object->view;
	}
}
?>
