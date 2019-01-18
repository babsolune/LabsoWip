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
use \Wiki\services\ModSetup;
use \Wiki\services\ModItem;
use \Wiki\services\ModServices;
use \Wiki\services\ModAuthorizations;
use \Wiki\util\ModUrlBuilder;

class KeywordCtrl extends ModuleController
{
	private $lang;
	private $view;
	private $keyword;

	private $config;
	private $content_management_config;

	public function execute(HTTPRequestCustom $request)
	{
		$this->check_authorizations();

		$this->init();

		$this->build_view($request);

		return $this->generate_response();
	}

	private function init()
	{
		$this->lang = LangLoader::get('common', 'wiki');
		$this->view = new FileTemplate('wiki/ItemsListCtrl.tpl');
		$this->view->add_lang($this->lang);
		$this->config = ModConfig::load();
		$this->content_management_config = ContentManagementConfig::load();
	}

	private function get_keyword()
	{
		if ($this->keyword === null)
		{
			$rewrited_name = AppContext::get_request()->get_getstring('keyword', '');
			if (!empty($rewrited_name))
			{
				try {
					$this->keyword = ModServices::get_keywords_manager()->get_keyword('WHERE rewrited_name=:rewrited_name', array('rewrited_name' => $rewrited_name));
				} catch (RowNotFoundException $e) {
					$error_controller = PHPBoostErrors::unexisting_page();
   					DispatchManager::redirect($error_controller);
				}
			}
			else
			{
				$error_controller = PHPBoostErrors::unexisting_page();
   				DispatchManager::redirect($error_controller);
			}
		}
		return $this->keyword;
	}

	private function build_view($request)
	{
		$now = new Date();

		$authorized_categories = ModServices::get_authorized_categories(Category::ROOT_CATEGORY);

		$condition = 'WHERE relation.id_keyword = :id_keyword
		AND category_id IN :authorized_categories
		AND (published = 1 OR (published = 2 AND publishing_start_date < :timestamp_now AND (publishing_end_date > :timestamp_now OR publishing_end_date = 0)))';
		$parameters = array(
			'id_keyword' => $this->get_keyword()->get_id(),
			'authorized_categories' => $authorized_categories,
			'timestamp_now' => $now->get_timestamp()
		);

		$page = AppContext::get_request()->get_getint('page', 1);
		$pagination = $this->get_pagination($condition, $parameters, $page);

		$result = PersistenceContext::get_querier()->select('SELECT item.*, member.*
		FROM ' . ModSetup::$items_table . ' item
		LEFT JOIN ' . DB_TABLE_KEYWORDS_RELATIONS . ' relation ON relation.module_id = \'wiki\' AND relation.id_in_module = item.id
		LEFT JOIN ' . DB_TABLE_MEMBER . ' member ON member.user_id = item.author_user_id
		' . $condition . '
		ORDER BY order_id
		LIMIT :items_number_per_page OFFSET :display_from', array_merge($parameters, array(
			'items_number_per_page' => $pagination->get_items_number_per_page(),
			'display_from' => $pagination->get_display_from()
		)));

		$number_columns_display_per_line = $this->config->get_number_cols_display_per_line();

		$this->view->put_all(array(
			'C_ITEMS' => $result->get_rows_count() > 0,
			'C_MORE_THAN_ONE_ITEM' => $result->get_rows_count() > 1,
			'C_PAGINATION' => $pagination->has_several_pages(),
			'PAGINATION' => $pagination->display(),
			'C_NO_ITEM_AVAILABLE' => $result->get_rows_count() == 0,
			'C_MOSAIC' => $this->config->get_display_type() == ModConfig::DISPLAY_MOSAIC,
			// 'C_CAT' => false,
			'C_COMMENTS_ENABLED' => $this->comments_config->module_comments_is_enabled('wiki'),
			'C_NOTATION_ENABLED' => $this->content_management_config->module_notation_is_enabled('wiki'),
			'C_ITEMS_FILTERS' => true,
			'CATEGORY_NAME' => $this->get_keyword()->get_name(),
			'C_SEVERAL_COLUMNS' => $number_columns_display_per_line > 1,
			'NUMBER_COLUMNS' => $number_columns_display_per_line
		));

		while ($row = $result->fetch())
		{
			$moditem = new ModItem();
			$moditem->set_properties($row);

			$this->build_keywords_view($moditem);

			$this->view->assign_block_vars('items', $moditem->get_array_tpl_vars());
		}
		$result->dispose();
	}

	private function build_keywords_view(ModItem $moditem)
	{
		$keywords = $moditem->get_keywords();
		$nbr_keywords = count($keywords);
		$this->view->put('C_KEYWORDS', $nbr_keywords > 0);

		$i = 1;
		foreach ($keywords as $keyword)
		{
			$this->view->assign_block_vars('keywords', array(
				'C_SEPARATOR' => $i < $nbr_keywords,
				'NAME' => $keyword->get_name(),
				'URL' => ModUrlBuilder::display_keyword($keyword->get_rewrited_name())->rel(),
			));
			$i++;
		}
	}

	private function get_pagination($condition, $parameters, $page)
	{
		$result = PersistenceContext::get_querier()->select_single_row_query('SELECT COUNT(*) AS items_number
		FROM '. ModSetup::$items_table .' item
		LEFT JOIN '. DB_TABLE_KEYWORDS_RELATIONS .' relation ON relation.module_id = \'wiki\' AND relation.id_in_module = item.id
		' . $condition, $parameters);

		$pagination = new ModulePagination($page, $result['items_number'], ModConfig::load()->get_items_number_per_page());
		$pagination->set_url(ModUrlBuilder::display_keyword($this->get_keyword()->get_rewrited_name(), '%d'));

		if ($pagination->current_page_is_empty() && $page > 1)
		{
			$error_controller = PHPBoostErrors::unexisting_page();
			DispatchManager::redirect($error_controller);
		}

		return $pagination;
	}

	private function check_authorizations()
	{
		if (!(ModAuthorizations::check_authorizations()->read()))
		{
			$error_controller = PHPBoostErrors::user_not_authorized();
			DispatchManager::redirect($error_controller);
		}
	}

	private function generate_response()
	{
		$response = new SiteDisplayResponse($this->view);

		$graphical_environment = $response->get_graphical_environment();
		$graphical_environment->set_page_title($this->get_keyword()->get_name(), $this->lang['module.title']);
		$graphical_environment->get_seo_meta_data()->set_description(StringVars::replace_vars($this->lang['seo.description.keyword'], array('subject' => $this->get_keyword()->get_name())));
		$graphical_environment->get_seo_meta_data()->set_canonical_url(ModUrlBuilder::display_keyword($this->get_keyword()->get_rewrited_name(), AppContext::get_request()->get_getint('page', 1)));

		$breadcrumb = $graphical_environment->get_breadcrumb();
		$breadcrumb->add($this->lang['module.title'], ModUrlBuilder::home());
		$breadcrumb->add($this->get_keyword()->get_name(), ModUrlBuilder::display_keyword($this->get_keyword()->get_rewrited_name(), AppContext::get_request()->get_getint('page', 1)));

		return $response;
	}
}
?>
