<?php
/*##################################################
 *		        ModcatartCategoriesManagerController.class.php
 *                            -------------------
 *   begin                : Month XX, 2017
 *   copyright            : (C) 2017 Firstname LASTNAME
 *   email                : nickname@phpboost.com
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
 * @author Firstname LASTNAME <nickname@phpboost.com>
 */

class ModcatartCategoriesManagerController extends AbstractCategoriesManageController
{
	protected function get_categories_manager()
	{
		return ModcatartService::get_categories_manager();
	}

	protected function get_display_category_url(Category $category)
	{
		return ModcatartUrlBuilder::display_category($category->get_id(), $category->get_rewrited_name());
	}

	protected function get_edit_category_url(Category $category)
	{
		return ModcatartUrlBuilder::edit_category($category->get_id());
	}

	protected function get_delete_category_url(Category $category)
	{
		return ModcatartUrlBuilder::delete_category($category->get_id());
	}

	protected function get_categories_management_url()
	{
		return ModcatartUrlBuilder::manage_categories();
	}

	protected function get_module_home_page_url()
	{
		return ModcatartUrlBuilder::home();
	}

	protected function get_module_home_page_title()
	{
		return LangLoader::get_message('modcatart.module.title', 'common', 'modcatart');
	}

	protected function check_authorizations()
	{
		if (!ModcatartAuthorizationsService::check_authorizations()->manage_categories())
		{
			$error_controller = PHPBoostErrors::user_not_authorized();
			DispatchManager::redirect($error_controller);
		}
	}
}
?>
