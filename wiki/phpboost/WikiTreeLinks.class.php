<?php
/**
 * @copyright 	&copy; 2005-2019 PHPBoost
 * @license 	https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version   	PHPBoost 5.2 - last update: 2018 05 25
 * @since   	PHPBoost 3.0 - 2013 12 03
 * @contributor xela <xela@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class WikiTreeLinks implements ModuleTreeLinksExtensionPoint
{
	public function get_actions_tree_links()
	{
		$lang = LangLoader::get('common', 'wiki');
		$tree = new ModuleTreeLinks();

		$manage_categories_link = new ModuleLink(LangLoader::get_message('categories.manage', 'categories-common'), WikiUrlBuilder::manage_categories(), WikiAuthorizationsService::check_authorizations()->manage_categories());
		$manage_categories_link->add_sub_link(new ModuleLink(LangLoader::get_message('categories.manage', 'categories-common'), WikiUrlBuilder::manage_categories(), WikiAuthorizationsService::check_authorizations()->manage_categories()));
		$manage_categories_link->add_sub_link(new ModuleLink(LangLoader::get_message('category.add', 'categories-common'), WikiUrlBuilder::add_category(AppContext::get_request()->get_getint('id_category', Category::ROOT_CATEGORY)), WikiAuthorizationsService::check_authorizations()->manage_categories()));
		$tree->add_link($manage_categories_link);

		$manage_items_link = new ModuleLink($lang['wiki_management'], WikiUrlBuilder::manage_items(), WikiAuthorizationsService::check_authorizations()->moderation());
		$manage_items_link->add_sub_link(new ModuleLink($lang['wiki_management'], WikiUrlBuilder::manage_items(), WikiAuthorizationsService::check_authorizations()->moderation()));
		$manage_items_link->add_sub_link(new ModuleLink($lang['wiki.add'], WikiUrlBuilder::add_item(AppContext::get_request()->get_getint('id_category', Category::ROOT_CATEGORY)), WikiAuthorizationsService::check_authorizations()->moderation()));
		$tree->add_link($manage_items_link);

		$tree->add_link(new AdminModuleLink(LangLoader::get_message('configuration', 'admin-common'), WikiUrlBuilder::configuration()));

		if (!WikiAuthorizationsService::check_authorizations()->moderation())
		{
			$tree->add_link(new ModuleLink($lang['wiki.add'], WikiUrlBuilder::add_item(AppContext::get_request()->get_getint('id_category', Category::ROOT_CATEGORY)), WikiAuthorizationsService::check_authorizations()->write() || WikiAuthorizationsService::check_authorizations()->contribution()));
		}

		$tree->add_link(new ModuleLink($lang['wiki.favorites.items'], WikiUrlBuilder::display_favorites(), WikiAuthorizationsService::check_authorizations()->read() || WikiAuthorizationsService::check_authorizations()->contribution() || WikiAuthorizationsService::check_authorizations()->moderation()));
		$tree->add_link(new ModuleLink($lang['wiki.pending_documents'], WikiUrlBuilder::display_pending_items(), WikiAuthorizationsService::check_authorizations()->write() || WikiAuthorizationsService::check_authorizations()->contribution() || WikiAuthorizationsService::check_authorizations()->moderation()));

		$tree->add_link(new ModuleLink(LangLoader::get_message('module.documentation', 'admin-modules-common'), ModulesManager::get_module('wiki')->get_configuration()->get_documentation(), WikiAuthorizationsService::check_authorizations()->write() || WikiAuthorizationsService::check_authorizations()->contribution() || WikiAuthorizationsService::check_authorizations()->moderation()));

		return $tree;
	}
}
?>
