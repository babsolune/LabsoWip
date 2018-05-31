<?php
/*##################################################
 *		    SponsorsTreeLinks.class.php
 *                            -------------------
 *   begin                : May 20, 2018
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

class SponsorsTreeLinks implements ModuleTreeLinksExtensionPoint
{
	public function get_actions_tree_links()
	{
		$lang = LangLoader::get('common', 'sponsors');
		$tree = new ModuleTreeLinks();

		$config_link = new AdminModuleLink(LangLoader::get_message('configuration', 'admin-common'), SponsorsUrlBuilder::configuration());
		$config_link->add_sub_link(new AdminModuleLink(LangLoader::get_message('configuration', 'admin-common'), SponsorsUrlBuilder::configuration()));
		$config_link->add_sub_link(new AdminModuleLink($lang['config.mini.title'], SponsorsUrlBuilder::mini_configuration()));
		$config_link->add_sub_link(new AdminModuleLink($lang['config.membership.terms'], SponsorsUrlBuilder::membership_terms_configuration()));
		$tree->add_link($config_link);

		$manage_categories_link = new ModuleLink(LangLoader::get_message('categories.manage', 'categories-common'), SponsorsUrlBuilder::manage_categories(), SponsorsAuthorizationsService::check_authorizations()->manage_categories());
		$manage_categories_link->add_sub_link(new ModuleLink(LangLoader::get_message('categories.manage', 'categories-common'), SponsorsUrlBuilder::manage_categories(), SponsorsAuthorizationsService::check_authorizations()->manage_categories()));
		$manage_categories_link->add_sub_link(new ModuleLink(LangLoader::get_message('category.add', 'categories-common'), SponsorsUrlBuilder::add_category(AppContext::get_request()->get_getint('id_category', Category::ROOT_CATEGORY)), SponsorsAuthorizationsService::check_authorizations()->manage_categories()));
		$tree->add_link($manage_categories_link);

		$manage_sponsors_link = new ModuleLink($lang['sponsors.management'], SponsorsUrlBuilder::manage_items(), SponsorsAuthorizationsService::check_authorizations()->moderation());
		$manage_sponsors_link->add_sub_link(new ModuleLink($lang['sponsors.management'], SponsorsUrlBuilder::manage_items(), SponsorsAuthorizationsService::check_authorizations()->moderation()));
		$manage_sponsors_link->add_sub_link(new ModuleLink($lang['sponsors.add'], SponsorsUrlBuilder::add_item(AppContext::get_request()->get_getint('id_category', Category::ROOT_CATEGORY)), SponsorsAuthorizationsService::check_authorizations()->moderation()));
		$tree->add_link($manage_sponsors_link);

		if (!SponsorsAuthorizationsService::check_authorizations()->moderation())
		{
			$tree->add_link(new ModuleLink($lang['sponsors.add'], SponsorsUrlBuilder::add_item(AppContext::get_request()->get_getint('id_category', Category::ROOT_CATEGORY)), SponsorsAuthorizationsService::check_authorizations()->write() || SponsorsAuthorizationsService::check_authorizations()->contribution()));
		}

		$tree->add_link(new ModuleLink($lang['sponsors.pending.items'], SponsorsUrlBuilder::display_pending_items(), SponsorsAuthorizationsService::check_authorizations()->write() || SponsorsAuthorizationsService::check_authorizations()->contribution() || SponsorsAuthorizationsService::check_authorizations()->moderation()));

		$tree->add_link(new ModuleLink($lang['sponsors.member.items'], SponsorsUrlBuilder::display_member_items(), SponsorsAuthorizationsService::check_authorizations()->write() || SponsorsAuthorizationsService::check_authorizations()->contribution() || SponsorsAuthorizationsService::check_authorizations()->moderation()));

		$tree->add_link(new ModuleLink(LangLoader::get_message('module.documentation', 'admin-modules-common'), ModulesManager::get_module('sponsors')->get_configuration()->get_documentation(), SponsorsAuthorizationsService::check_authorizations()->write() || SponsorsAuthorizationsService::check_authorizations()->contribution() || SponsorsAuthorizationsService::check_authorizations()->moderation()));

		return $tree;
	}
}
?>
