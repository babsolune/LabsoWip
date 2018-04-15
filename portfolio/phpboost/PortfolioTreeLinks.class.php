<?php
/*##################################################
 *		    PortfolioTreeLinks.class.php
 *                            -------------------
 *   begin                : November 29, 2017
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

class PortfolioTreeLinks implements ModuleTreeLinksExtensionPoint
{
	public function get_actions_tree_links()
	{
		$lang = LangLoader::get('common', 'portfolio');
		$tree = new ModuleTreeLinks();

		$manage_categories_link = new ModuleLink(LangLoader::get_message('categories.manage', 'categories-common'), PortfolioUrlBuilder::manage_categories(), PortfolioAuthorizationsService::check_authorizations()->manage_categories());
		$manage_categories_link->add_sub_link(new ModuleLink(LangLoader::get_message('categories.manage', 'categories-common'), PortfolioUrlBuilder::manage_categories(), PortfolioAuthorizationsService::check_authorizations()->manage_categories()));
		$manage_categories_link->add_sub_link(new ModuleLink(LangLoader::get_message('category.add', 'categories-common'), PortfolioUrlBuilder::add_category(AppContext::get_request()->get_getint('category_id', Category::ROOT_CATEGORY)), PortfolioAuthorizationsService::check_authorizations()->manage_categories()));
		$tree->add_link($manage_categories_link);

		$manage_portfolio_link = new ModuleLink($lang['portfolio.management'], PortfolioUrlBuilder::manage_items(), PortfolioAuthorizationsService::check_authorizations()->moderation());
		$manage_portfolio_link->add_sub_link(new ModuleLink($lang['portfolio.management'], PortfolioUrlBuilder::manage_items(), PortfolioAuthorizationsService::check_authorizations()->moderation()));
		$manage_portfolio_link->add_sub_link(new ModuleLink($lang['portfolio.add'], PortfolioUrlBuilder::add_item(AppContext::get_request()->get_getint('category_id', Category::ROOT_CATEGORY)), PortfolioAuthorizationsService::check_authorizations()->moderation()));
		$tree->add_link($manage_portfolio_link);

		$tree->add_link(new AdminModuleLink(LangLoader::get_message('configuration', 'admin-common'), PortfolioUrlBuilder::configuration()));

		if (!PortfolioAuthorizationsService::check_authorizations()->moderation())
		{
			$tree->add_link(new ModuleLink($lang['portfolio.add'], PortfolioUrlBuilder::add_item(AppContext::get_request()->get_getint('category_id', Category::ROOT_CATEGORY)), PortfolioAuthorizationsService::check_authorizations()->write() || PortfolioAuthorizationsService::check_authorizations()->contribution()));
		}

		$tree->add_link(new ModuleLink($lang['portfolio.pending.items'], PortfolioUrlBuilder::display_pending_items(), PortfolioAuthorizationsService::check_authorizations()->write() || PortfolioAuthorizationsService::check_authorizations()->contribution() || PortfolioAuthorizationsService::check_authorizations()->moderation()));

		$tree->add_link(new ModuleLink(LangLoader::get_message('module.documentation', 'admin-modules-common'), ModulesManager::get_module('portfolio')->get_configuration()->get_documentation(), PortfolioAuthorizationsService::check_authorizations()->write() || PortfolioAuthorizationsService::check_authorizations()->contribution() || PortfolioAuthorizationsService::check_authorizations()->moderation()));

		return $tree;
	}
}
?>
