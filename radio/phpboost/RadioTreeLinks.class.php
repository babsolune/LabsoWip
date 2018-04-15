<?php
/*##################################################
 *		                         RadioTreeLinks.class.php
 *                            -------------------
 *   begin                : May, 02, 2017
 *   copyright            : (C) 2017 Sebastien LARTIGUE
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

class RadioTreeLinks implements ModuleTreeLinksExtensionPoint
{
	public function get_actions_tree_links()
	{
		$lang = LangLoader::get('common', 'radio');
		$tree = new ModuleTreeLinks();

		$manage_categories_link = new ModuleLink(LangLoader::get_message('categories.manage', 'categories-common'), RadioUrlBuilder::manage_categories(), RadioAuthorizationsService::check_authorizations()->manage_categories());
		$manage_categories_link->add_sub_link(new ModuleLink(LangLoader::get_message('categories.manage', 'categories-common'), RadioUrlBuilder::manage_categories(), RadioAuthorizationsService::check_authorizations()->manage_categories()));
		$manage_categories_link->add_sub_link(new ModuleLink(LangLoader::get_message('category.add', 'categories-common'), RadioUrlBuilder::add_category(AppContext::get_request()->get_getint('id_category', Category::ROOT_CATEGORY)), RadioAuthorizationsService::check_authorizations()->manage_categories()));
		$tree->add_link($manage_categories_link);

		$manage_radio_link = new ModuleLink($lang['radio.manage'], RadioUrlBuilder::manage_radio(), RadioAuthorizationsService::check_authorizations()->moderation());
		$manage_radio_link->add_sub_link(new ModuleLink($lang['radio.manage'], RadioUrlBuilder::manage_radio(), RadioAuthorizationsService::check_authorizations()->moderation()));
		$manage_radio_link->add_sub_link(new ModuleLink($lang['radio.add'], RadioUrlBuilder::add_radio(AppContext::get_request()->get_getint('id_category', Category::ROOT_CATEGORY)), RadioAuthorizationsService::check_authorizations()->moderation()));
		$tree->add_link($manage_radio_link);

		$tree->add_link(new AdminModuleLink(LangLoader::get_message('configuration', 'admin-common'), RadioUrlBuilder::configuration()));

		if (!RadioAuthorizationsService::check_authorizations()->moderation())
		{
			$tree->add_link(new ModuleLink($lang['radio.add'], RadioUrlBuilder::add_radio(AppContext::get_request()->get_getint('id_category', Category::ROOT_CATEGORY)), RadioAuthorizationsService::check_authorizations()->write() || RadioAuthorizationsService::check_authorizations()->contribution()));
		}

		$tree->add_link(new ModuleLink($lang['radio.pending'], RadioUrlBuilder::display_pending_radio(), RadioAuthorizationsService::check_authorizations()->write() || RadioAuthorizationsService::check_authorizations()->contribution() || RadioAuthorizationsService::check_authorizations()->moderation()));

		$tree->add_link(new ModuleLink(LangLoader::get_message('module.documentation', 'admin-modules-common'), RadioUrlBuilder::documentation(), RadioAuthorizationsService::check_authorizations()->write() || RadioAuthorizationsService::check_authorizations()->contribution() || RadioAuthorizationsService::check_authorizations()->moderation()));

		return $tree;
	}
}
?>
