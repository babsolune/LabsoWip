<?php
/*##################################################
 *                               SponsorsTreeLinks.class.php
 *                            -------------------
 *   begin                : September 13, 2017
 *   copyright            : (C) 2017 Sebastien LARTIGUE
 *   email                : babsolune@phpboost.com
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
 * @author Sebastien LARTIGUE <babsolune@phpboost.com>
 */

class SponsorsTreeLinks implements ModuleTreeLinksExtensionPoint
{
	public function get_actions_tree_links()
	{
		$lang = LangLoader::get('common', 'sponsors');
		$tree = new ModuleTreeLinks();

		$manage_categories_link = new ModuleLink(LangLoader::get_message('categories.manage', 'categories-common'), SponsorsUrlBuilder::manage_categories(), SponsorsAuthorizationsService::check_authorizations()->manage_categories());
		$manage_categories_link->add_sub_link(new ModuleLink(LangLoader::get_message('categories.manage', 'categories-common'), SponsorsUrlBuilder::manage_categories(), SponsorsUrlBuilder::manage_categories(), SponsorsAuthorizationsService::check_authorizations()->manage_categories()));
		$manage_categories_link->add_sub_link(new ModuleLink(LangLoader::get_message('category.add', 'categories-common'), SponsorsUrlBuilder::add_category(AppContext::get_request()->get_getint('id_category', Category::ROOT_CATEGORY)), SponsorsUrlBuilder::manage_categories(), SponsorsAuthorizationsService::check_authorizations()->manage_categories()));
		$tree->add_link($manage_categories_link);

		$manage_link = new ModuleLink($lang['sponsors.manage'], SponsorsUrlBuilder::manage(), SponsorsAuthorizationsService::check_authorizations()->moderation());
		$manage_link->add_sub_link(new ModuleLink($lang['sponsors.manage'], SponsorsUrlBuilder::manage(), SponsorsAuthorizationsService::check_authorizations()->moderation()));
		$manage_link->add_sub_link(new ModuleLink($lang['sponsors.actions.add'], SponsorsUrlBuilder::add(AppContext::get_request()->get_getint('id_category', Category::ROOT_CATEGORY)), SponsorsAuthorizationsService::check_authorizations()->moderation()));
		$tree->add_link($manage_link);

		$tree->add_link(new AdminModuleLink(LangLoader::get_message('configuration', 'admin-common'), SponsorsUrlBuilder::configuration()));

		if (!SponsorsAuthorizationsService::check_authorizations()->moderation())
		{
			$tree->add_link(new ModuleLink($lang['sponsors.actions.add'], SponsorsUrlBuilder::add(AppContext::get_request()->get_getint('id_category', Category::ROOT_CATEGORY)), SponsorsAuthorizationsService::check_authorizations()->write() || SponsorsAuthorizationsService::check_authorizations()->contribution()));
		}

		$tree->add_link(new ModuleLink($lang['sponsors.pending'], SponsorsUrlBuilder::display_pending(), SponsorsAuthorizationsService::check_authorizations()->write() || SponsorsAuthorizationsService::check_authorizations()->contribution() || SponsorsAuthorizationsService::check_authorizations()->moderation()));

		$tree->add_link(new ModuleLink(LangLoader::get_message('module.documentation', 'admin-modules-common'), ModulesManager::get_module('sponsors')->get_configuration()->get_documentation(), SponsorsAuthorizationsService::check_authorizations()->write() || SponsorsAuthorizationsService::check_authorizations()->contribution() || SponsorsAuthorizationsService::check_authorizations()->moderation()));

		return $tree;
	}
}
?>
