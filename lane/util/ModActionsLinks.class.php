<?php
/**
 * @copyright 	&copy; 2005-2019 PHPBoost https://www.phpboost.com
 * @license 	https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER [j1.seth@phpboost.com]
 * @version   	PHPBoost 5.2 - last update: 2018 05 25
 * @since   	PHPBoost 3.0 - 2013 12 03
 * @contributor xela [xela@phpboost.com]
 * @contributor Sebastien LARTIGUE [babsolune@phpboost.com]
*/

namespace Wiki\util;
use \Wiki\services\ModAuthorizations;
use \Wiki\util\ModUrlBuilder;

class ModActionsLinks implements ModuleTreeLinksExtensionPoint
{
	public function get_actions_tree_links()
	{
		$lang = LangLoader::get('common', 'wiki');
		$tree = new ModuleTreeLinks();

		$manage_categories_link = new ModuleLink(LangLoader::get_message('categories.manage', 'categories-common'), ModUrlBuilder::manage_categories(), ModAuthorizations::check_authorizations()->manage_categories());
		$manage_categories_link->add_sub_link(new ModuleLink(LangLoader::get_message('categories.manage', 'categories-common'), ModUrlBuilder::manage_categories(), ModAuthorizations::check_authorizations()->manage_categories()));
		$manage_categories_link->add_sub_link(new ModuleLink(LangLoader::get_message('category.add', 'categories-common'), ModUrlBuilder::add_category(AppContext::get_request()->get_getint('category_id', Category::ROOT_CATEGORY)), ModAuthorizations::check_authorizations()->manage_categories()));
		$tree->add_link($manage_categories_link);

		$manage_items_link = new ModuleLink($lang['items.manager'], ModUrlBuilder::manage_items(), ModAuthorizations::check_authorizations()->moderation());
		$manage_items_link->add_sub_link(new ModuleLink($lang['items.manager'], ModUrlBuilder::manage_items(), ModAuthorizations::check_authorizations()->moderation()));
		$manage_items_link->add_sub_link(new ModuleLink($lang['add.item'], ModUrlBuilder::add_item(AppContext::get_request()->get_getint('category_id', Category::ROOT_CATEGORY)), ModAuthorizations::check_authorizations()->moderation()));
		$tree->add_link($manage_items_link);

		$tree->add_link(new AdminModuleLink(LangLoader::get_message('configuration', 'admin-common'), ModUrlBuilder::configuration()));

		if (!ModAuthorizations::check_authorizations()->moderation())
		{
			$tree->add_link(new ModuleLink($lang['add.item'], ModUrlBuilder::add_item(AppContext::get_request()->get_getint('category_id', Category::ROOT_CATEGORY)), ModAuthorizations::check_authorizations()->write() || ModAuthorizations::check_authorizations()->contribution()));
		}

		$tree->add_link(new ModuleLink($lang['favorite.items'], ModUrlBuilder::display_favorites(), ModAuthorizations::check_authorizations()->read() || ModAuthorizations::check_authorizations()->contribution() || ModAuthorizations::check_authorizations()->moderation()));
		$tree->add_link(new ModuleLink($lang['pending.items'], ModUrlBuilder::display_pending_items(), ModAuthorizations::check_authorizations()->write() || ModAuthorizations::check_authorizations()->contribution() || ModAuthorizations::check_authorizations()->moderation()));

		$tree->add_link(new ModuleLink(LangLoader::get_message('module.documentation', 'admin-modules-common'), ModulesManager::get_module('wiki')->get_configuration()->get_documentation(), ModAuthorizations::check_authorizations()->write() || ModAuthorizations::check_authorizations()->contribution() || ModAuthorizations::check_authorizations()->moderation()));

		return $tree;
	}
}
?>
