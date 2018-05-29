<?php
/*##################################################
 *		        PbtdocCategoriesManageController.class.php
 *                            -------------------
 *   begin                : March 04, 2013
 *   copyright            : (C) 2013 Patrick DUBEAU
 *   email                : daaxwizeman@gmail.com
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

class PbtdocCategoriesManageController extends AbstractCategoriesManageController
{
	protected function get_categories_manager()
	{
		return PbtdocService::get_categories_manager();
	}

	protected function get_display_category_url(Category $category)
	{
		return PbtdocUrlBuilder::display_category($category->get_id(), $category->get_rewrited_name());
	}

	protected function get_edit_category_url(Category $category)
	{
		return PbtdocUrlBuilder::edit_category($category->get_id());
	}

	protected function get_delete_category_url(Category $category)
	{
		return PbtdocUrlBuilder::delete_category($category->get_id());
	}

	protected function get_categories_management_url()
	{
		return PbtdocUrlBuilder::manage_categories();
	}

	protected function get_module_home_page_url()
	{
		return PbtdocUrlBuilder::home();
	}

	protected function get_module_home_page_title()
	{
		return LangLoader::get_message('module.title', 'common', 'pbtdoc');
	}

	protected function check_authorizations()
	{
		if (!PbtdocAuthorizationsService::check_authorizations()->manage_categories())
		{
			$error_controller = PHPBoostErrors::user_not_authorized();
			DispatchManager::redirect($error_controller);
		}
	}
}
?>
