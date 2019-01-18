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
use \Wiki\services\ModItem;
use \Wiki\services\ModServices;
use \Wiki\services\ModAuthorizations;
use \Wiki\util\ModUrlBuilder;

class ItemHistoryCtrl extends ModuleController
{
	private $lang;
	private $view;
	private $moditem;

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
		$this->view = new FileTemplate('wiki/ItemHistoryCtrl.tpl');
		$this->view->add_lang($this->lang);
	}

	private function build_view(HTTPRequestCustom $request)
	{
		$config = ModConfig::load();
		$content_management_config = ContentManagementConfig::load();
	}

	private function check_authorizations()
	{
		$moditem = $this->get_moditem();

		$current_user = AppContext::get_current_user();

		// Code goes here

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

	private function generate_response()
	{
		$moditem = $this->get_moditem();
		$response = new SiteDisplayResponse($this->view);

		$graphical_environment = $response->get_graphical_environment();
		$graphical_environment->set_page_title($this->moditem->get_title(), $this->lang['module.title']);

		$breadcrumb = $graphical_environment->get_breadcrumb();
		$breadcrumb->add($this->lang['module.title'], ModUrlBuilder::home());
		$categories = array_reverse(ModServices::get_categories_manager()->get_parents($moditem->get_category_id(), true));
		foreach ($categories as $id => $category)
		{
			if ($category->get_id() != Category::ROOT_CATEGORY)
				$breadcrumb->add($category->get_name(), ModUrlBuilder::display_category($category->get_id(), $category->get_rewrited_name()));
		}
		$breadcrumb->add($moditem->get_title(), ModUrlBuilder::display_item($category->get_id(), $category->get_rewrited_name(), $moditem->get_id(), $moditem->get_rewrited_title()));

		$breadcrumb->add($this->lang['item.history'], ModUrlBuilder::display_item_history($moditem->get_id()));
		$graphical_environment->set_page_title($this->lang['item.history'], $this->lang['module.title']);
		$graphical_environment->get_seo_meta_data()->set_description($this->lang['item.history']);
		$graphical_environment->get_seo_meta_data()->set_canonical_url(ModUrlBuilder::display_item_history($moditem->get_id()));

		return $response;
	}
}
?>
