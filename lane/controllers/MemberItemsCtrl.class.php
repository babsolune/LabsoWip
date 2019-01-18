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

class MemberItemsListCtrl extends ModuleController
{
	private $lang;
	private $view;
	private $form;

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
	}

	private function build_view($request)
	{
		$now = new Date();
		$authorized_categories = ModServices::get_authorized_categories(Category::ROOT_CATEGORY);
		$config = ModConfig::load();
		$content_management_config = ContentManagementConfig::load();

		$condition = 'WHERE category_id IN :authorized_categories
		' . (!ModAuthorizations::check_authorizations()->moderation() ? ' AND author_user_id = :user_id' : '') . '
		AND (published = 0 OR (published = 2 AND (publishing_start_date > :timestamp_now OR (publishing_end_date != 0 AND publishing_end_date < :timestamp_now))))';
		$parameters = array(
			'authorized_categories' => $authorized_categories,
			'user_id' => AppContext::get_current_user()->get_id(),
			'timestamp_now' => $now->get_timestamp()
		);

		$page = AppContext::get_request()->get_getint('page', 1);
		$pagination = $this->get_pagination($condition, $parameters, $page);
		$result = PersistenceContext::get_querier()->select('SELECT item.*, member.*
		FROM '. ModSetup::$items_table .' item
		LEFT JOIN '. DB_TABLE_MEMBER .' member ON member.user_id = item.author_user_id
		' . $condition . '
		ORDER BY order_id ASC
		LIMIT :items_number_per_page OFFSET :display_from', array_merge($parameters, array(
			'items_number_per_page' => $pagination->get_items_number_per_page(),
			'display_from' => $pagination->get_display_from()
		)));

		$pending_items_number = $result->get_rows_count();

		$this->view->put_all(array(
			'C_ITEMS' => $result->get_rows_count() > 0,
			'C_MORE_THAN_ONE_ITEM' => $result->get_rows_count() > 1,
			'C_MEMBER' => true,
			'C_MOSAIC' => $config->get_display_type() == ModConfig::DISPLAY_MOSAIC,
			'C_NO_ITEM_AVAILABLE' => $pending_items_number == 0
		));

		if ($pending_items_number > 0)
		{
			$number_columns_display_per_line = $config->get_number_cols_display_per_line();

			$this->view->put_all(array(
				'C_ITEMS_FILTERS' => true,
				'C_COMMENTS_ENABLED' => $comments_config->module_comments_is_enabled('wiki'),
				'C_NOTATION_ENABLED' => $content_management_config->module_notation_is_enabled('wiki'),
				'C_PAGINATION' => $pagination->has_several_pages(),
				'PAGINATION' => $pagination->display(),
				'C_SEVERAL_COLUMNS' => $number_columns_display_per_line > 1,
				'NUMBER_COLUMNS' => $number_columns_display_per_line
			));

			while($row = $result->fetch())
			{
				$moditem = new ModItem();
				$moditem->set_properties($row);

				$keywords = $moditem->get_keywords();
				$has_keywords = count($keywords) > 0;

				if ($has_keywords)
					$this->build_keywords_view($keywords);

				$this->view->assign_block_vars('items', $moditem->get_array_tpl_vars(), array(
					'C_KEYWORDS' => $has_keywords
				));
			}
		}
		$result->dispose();
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
				'URL' => ModUrlBuilder::display_keyword($keyword->get_rewrited_name())->rel(),
			));
			$i++;
		}
	}

	private function check_authorizations()
	{
		if (!(ModAuthorizations::check_authorizations()->write() || ModAuthorizations::check_authorizations()->contribution() || ModAuthorizations::check_authorizations()->moderation()))
		{
			$error_controller = PHPBoostErrors::user_not_authorized();
			DispatchManager::redirect($error_controller);
		}
	}

	private function get_pagination($condition, $parameters, $page)
	{
		$items_number = PersistenceContext::get_querier()->count(ModSetup::$items_table, $condition, $parameters);

		$pagination = new ModulePagination($page, $items_number, (int)ModConfig::load()->get_items_number_per_page());
		$pagination->set_url(ModUrlBuilder::display_pending_items('/%d'));

		if ($pagination->current_page_is_empty() && $page > 1)
		{
			$error_controller = PHPBoostErrors::unexisting_page();
			DispatchManager::redirect($error_controller);
		}

		return $pagination;
	}

	private function generate_response()
	{
		$response = new SiteDisplayResponse($this->view);

		$graphical_environment = $response->get_graphical_environment();
		$graphical_environment->set_page_title($this->lang['pending.items'], $this->lang['module.title']);
		$graphical_environment->get_seo_meta_data()->set_description($this->lang['seo.description.pending']);
		$graphical_environment->get_seo_meta_data()->set_canonical_url(ModUrlBuilder::display_pending_items(AppContext::get_request()->get_getint('page', 1)));

		$breadcrumb = $graphical_environment->get_breadcrumb();
		$breadcrumb->add($this->lang['module.title'], ModUrlBuilder::home());
		$breadcrumb->add($this->lang['pending.items'], ModUrlBuilder::display_pending_items(AppContext::get_request()->get_getint('page', 1)));

		return $response;
	}
}
?>
