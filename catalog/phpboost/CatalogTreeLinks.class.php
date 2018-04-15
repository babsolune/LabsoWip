<?php
/*##################################################
 *                               CatalogTreeLinks.class.php
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

class CatalogTreeLinks implements ModuleTreeLinksExtensionPoint
{
	public function get_actions_tree_links()
	{
		$lang = LangLoader::get('common', 'catalog');
		$tree = new ModuleTreeLinks();

		$manage_categories_link = new ModuleLink(LangLoader::get_message('categories.manage', 'categories-common'), CatalogUrlBuilder::manage_categories(), CatalogAuthorizationsService::check_authorizations()->manage_categories());
		$manage_categories_link->add_sub_link(new ModuleLink(LangLoader::get_message('categories.manage', 'categories-common'), CatalogUrlBuilder::manage_categories(), CatalogAuthorizationsService::check_authorizations()->manage_categories()));
		$manage_categories_link->add_sub_link(new ModuleLink(LangLoader::get_message('category.add', 'categories-common'), CatalogUrlBuilder::add_category(AppContext::get_request()->get_getint('id_category', Category::ROOT_CATEGORY)), CatalogAuthorizationsService::check_authorizations()->manage_categories()));
		$tree->add_link($manage_categories_link);

		$manage_link = new ModuleLink($lang['catalog.manage'], CatalogUrlBuilder::manage(), CatalogAuthorizationsService::check_authorizations()->moderation());
		$manage_link->add_sub_link(new ModuleLink($lang['catalog.manage'], CatalogUrlBuilder::manage(), CatalogAuthorizationsService::check_authorizations()->moderation()));
		$manage_link->add_sub_link(new ModuleLink($lang['catalog.actions.add'], CatalogUrlBuilder::add(AppContext::get_request()->get_getint('id_category', Category::ROOT_CATEGORY)), CatalogAuthorizationsService::check_authorizations()->moderation()));
		$tree->add_link($manage_link);

		$tree->add_link(new AdminModuleLink(LangLoader::get_message('configuration', 'admin-common'), CatalogUrlBuilder::configuration()));
		$tree->add_link(new AdminModuleLink(LangLoader::get_message('catalog.gcs.manage', 'gcs', 'catalog'), CatalogUrlBuilder::gcs_manage()));

		$tree->add_link(new ModuleLink(LangLoader::get_message('catalog.gcs.title', 'gcs', 'catalog'), CatalogUrlBuilder::gcs()));

		if (!CatalogAuthorizationsService::check_authorizations()->moderation())
		{
			$tree->add_link(new ModuleLink($lang['catalog.actions.add'], CatalogUrlBuilder::add(AppContext::get_request()->get_getint('id_category', Category::ROOT_CATEGORY)), CatalogAuthorizationsService::check_authorizations()->write() || CatalogAuthorizationsService::check_authorizations()->contribution()));
		}

		$tree->add_link(new ModuleLink($lang['catalog.pending'], CatalogUrlBuilder::display_pending(), CatalogAuthorizationsService::check_authorizations()->write() || CatalogAuthorizationsService::check_authorizations()->contribution() || CatalogAuthorizationsService::check_authorizations()->moderation()));

		$tree->add_link(new ModuleLink(LangLoader::get_message('module.documentation', 'admin-modules-common'), ModulesManager::get_module('catalog')->get_configuration()->get_documentation(), CatalogAuthorizationsService::check_authorizations()->write() || CatalogAuthorizationsService::check_authorizations()->contribution() || CatalogAuthorizationsService::check_authorizations()->moderation()));

		return $tree;
	}
}
?>
