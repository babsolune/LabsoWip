<?php
/*##################################################
 *		    WikiTreeLinks.class.php
 *                            -------------------
 *   begin                : May 25, 2018
 *   copyright            : (C) 2018 Sebastien LARTIGUE
 *   email                : babsolune@phpboost.com
 *
 *
 ###################################################
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
 ###################################################*/

/**
 * @author Sebastien LARTIGUE <babsolune@phpboost.com>
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
