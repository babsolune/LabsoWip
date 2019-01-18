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

class FavoriteItemsListCtrl extends ModuleController
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
		$this->view = new FileTemplate('wiki/ItemsListCtrl.tpl');
		$this->view->add_lang($this->lang);
		$this->config = ModConfig::load();

		$this->comments_config = CommentsConfig::load();
		$this->content_management_config = ContentManagementConfig::load();
	}

	private function build_view()
	{
		$now = new Date();
		$authorized_categories = ModServices::get_authorized_categories($this->get_category()->get_id());

		$condition = 'WHERE (item.published = 1 OR (item.published = 2 AND item.publishing_start_date < :timestamp_now AND (item.publishing_end_date > :timestamp_now OR publishing_end_date = 0)))';
		$parameters = array(
			'authorized_categories' => $authorized_categories,
			'timestamp_now' => $now->get_timestamp(),
			'user_id' => AppContext::get_current_user()->get_id()
		);

		$result = PersistenceContext::get_querier()->select('SELECT item_fav.id, item.id, item.title, item.rewrited_title
		FROM '. ModSetup::$favorite_items_table .' item_fav
		LEFT JOIN '. ModSetup::$items_table .' item ON item.id = item_fav.item_id
		' . $condition . '
		ORDER BY item.date_created DESC'
		);

		$this->view->put_all(array(
			'C_FAVORITES' => true,
			'NO_FAVORITE' => $result->get_rows_count() == 0,
		));

		while ($row = $result->fetch())
		{
			$moditem = new ModItem();
			$moditem->set_properties($row);
			$this->view->assign_block_vars('items', $moditem->get_array_tpl_vars());
		}
		$result->dispose();
	}

	private function get_category()
	{
		if ($this->category === null)
		{
			$id = AppContext::get_request()->get_getstring('category_id', 0);
			if (!empty($id))
			{
				try {
					$this->category = ModServices::get_categories_manager()->get_categories_cache()->get_category($id);
				} catch (CategoryNotFoundException $e) {
					$error_controller = PHPBoostErrors::unexisting_page();
					DispatchManager::redirect($error_controller);
				}
			}
			else
			{
				$this->category = ModServices::get_categories_manager()->get_categories_cache()->get_category(Category::ROOT_CATEGORY);
			}
		}
		return $this->category;
	}

	private function check_authorizations()
	{
		if (AppContext::get_current_user()->is_guest())
		{
			if (($this->config->are_descriptions_displayed_to_guests() && !Authorizations::check_auth(RANK_TYPE, User::MEMBER_LEVEL, $this->get_category()->get_authorizations(), Category::READ_AUTHORIZATIONS)) || (!$this->config->are_descriptions_displayed_to_guests() && !ModAuthorizations::check_authorizations($this->get_category()->get_id())->read()))
			{
				$error_controller = PHPBoostErrors::user_not_authorized();
				DispatchManager::redirect($error_controller);
			}
		}
		else
		{
			if (!ModAuthorizations::check_authorizations($this->get_category()->get_id())->read())
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

		$graphical_environment->set_page_title($this->lang['favorite.items'] . ' - ' . $this->category->get_name(), $this->lang['module.title']);


		$graphical_environment->get_seo_meta_data()->set_description($this->category->get_description());
		$graphical_environment->get_seo_meta_data()->set_canonical_url(ModUrlBuilder::display_favorites($this->category->get_id(), $this->category->get_rewrited_name()));

		$breadcrumb = $graphical_environment->get_breadcrumb();
		$breadcrumb->add($this->lang['module.title'], ModUrlBuilder::home());
		$breadcrumb->add($this->lang['favorite.items'], ModUrlBuilder::display_favorites());

		$categories = array_reverse(ModServices::get_categories_manager()->get_parents($this->category->get_id(), true));
		foreach ($categories as $id => $category)
		{
			if ($category->get_id() != Category::ROOT_CATEGORY)
				$breadcrumb->add($category->get_name(), ModUrlBuilder::display_favorites($category->get_id(), $category->get_rewrited_name()));
		}

		return $response;
	}

	public static function get_view()
	{
		$object = new self();
		$object->init();
		$object->check_authorizations();
		$object->build_view(AppContext::get_request());
		return $object->view;
	}
}
?>
