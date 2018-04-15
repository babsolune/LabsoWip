<?php
/*##################################################
 *		                         PalmaresTreeLinks.class.php
 *                            -------------------
 *   begin                : November 15, 2013
 *   copyright            : (C) 2013 Kevin MASSY
 *   email                : kevin.massy@phpboost.com
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
 * @author Kevin MASSY <kevin.massy@phpboost.com>
 */
class PalmaresTreeLinks implements ModuleTreeLinksExtensionPoint
{
	public function get_actions_tree_links()
	{
		$lang = LangLoader::get('common', 'palmares');
		$tree = new ModuleTreeLinks();

		$manage_categories_link = new ModuleLink(LangLoader::get_message('categories.manage', 'categories-common'), PalmaresUrlBuilder::manage_categories(), PalmaresAuthorizationsService::check_authorizations()->manage_categories());
		$manage_categories_link->add_sub_link(new ModuleLink(LangLoader::get_message('categories.manage', 'categories-common'), PalmaresUrlBuilder::manage_categories(), PalmaresAuthorizationsService::check_authorizations()->manage_categories()));
		$manage_categories_link->add_sub_link(new ModuleLink(LangLoader::get_message('category.add', 'categories-common'), PalmaresUrlBuilder::add_category(AppContext::get_request()->get_getint('id_category', Category::ROOT_CATEGORY)), PalmaresAuthorizationsService::check_authorizations()->manage_categories()));
		$tree->add_link($manage_categories_link);

		$manage_palmares_link = new ModuleLink($lang['palmares.manage'], PalmaresUrlBuilder::manage_palmares(), PalmaresAuthorizationsService::check_authorizations()->moderation());
		$manage_palmares_link->add_sub_link(new ModuleLink($lang['palmares.manage'], PalmaresUrlBuilder::manage_palmares(), PalmaresAuthorizationsService::check_authorizations()->moderation()));
		$manage_palmares_link->add_sub_link(new ModuleLink($lang['palmares.add'], PalmaresUrlBuilder::add_palmares(AppContext::get_request()->get_getint('id_category', Category::ROOT_CATEGORY)), PalmaresAuthorizationsService::check_authorizations()->moderation()));
		$tree->add_link($manage_palmares_link);

		$tree->add_link(new AdminModuleLink(LangLoader::get_message('configuration', 'admin-common'), PalmaresUrlBuilder::configuration()));

		if (!PalmaresAuthorizationsService::check_authorizations()->moderation())
		{
			$tree->add_link(new ModuleLink($lang['palmares.add'], PalmaresUrlBuilder::add_palmares(AppContext::get_request()->get_getint('id_category', Category::ROOT_CATEGORY)), PalmaresAuthorizationsService::check_authorizations()->write() || PalmaresAuthorizationsService::check_authorizations()->contribution()));
		}

		$tree->add_link(new ModuleLink($lang['palmares.pending'], PalmaresUrlBuilder::display_pending_palmares(), PalmaresAuthorizationsService::check_authorizations()->write() || PalmaresAuthorizationsService::check_authorizations()->contribution() || PalmaresAuthorizationsService::check_authorizations()->moderation()));

		$tree->add_link(new ModuleLink(LangLoader::get_message('module.documentation', 'admin-modules-common'), ModulesManager::get_module('palmares')->get_configuration()->get_documentation(), PalmaresAuthorizationsService::check_authorizations()->write() || PalmaresAuthorizationsService::check_authorizations()->contribution() || PalmaresAuthorizationsService::check_authorizations()->moderation()));

		return $tree;
	}
}
?>
