<?php
/**
 *				StaffCategoriesFormController.class.php
 *				------------------
 * @since 		PHPBoost 5.2 - 2017-06-29
 * @author 		Sebastien LARTIGUE - <babsolune@phpboost.com>
 *
 * 				This file is part of
 * @copyright 	2005-2019 PHPBoost
 * 				PHPBoost is free software: you can redistribute it and/or modify it
 * 				under the terms of the GNU General Public License as published by
 * 				the Free Software Foundation, either version 3 of the License, or
 * 				(at your option) any later version.
 * 				PHPBoost is distributed in the hope that it will be useful,
 * 				but WITHOUT ANY WARRANTY; without even the implied warranty of
 * 				MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * 				GNU General Public License for more details.
 * 				You should have received a copy of the GNU General Public License
 * 				along with PHPBoost.  If not, see
 * @license 	https://opensource.org/licenses/GPL-3.0
 *
 * @category 	module
 * @package 	staff
 * @subpackage	controllers
 * @desc 		add/edit category form
*/

class StaffCategoriesFormController extends AbstractRichCategoriesFormController
{
	protected function get_id_category()
	{
		return AppContext::get_request()->get_getint('id', 0);
	}

	protected function get_categories_manager()
	{
		return StaffService::get_categories_manager();
	}

	protected function get_categories_management_url()
	{
		return StaffUrlBuilder::manage_categories();
	}

	protected function get_add_category_url()
	{
		return StaffUrlBuilder::add_category(AppContext::get_request()->get_getint('id_parent', 0));
	}

	protected function get_edit_category_url(Category $category)
	{
		return StaffUrlBuilder::edit_category($category->get_id());
	}

	protected function get_module_home_page_url()
	{
		return StaffUrlBuilder::home();
	}

	protected function get_module_home_page_title()
	{
		return LangLoader::get_message('staff.module.title', 'common', 'staff');
	}

	protected function check_authorizations()
	{
		if (!StaffAuthorizationsService::check_authorizations()->manage_categories())
		{
			$error_controller = PHPBoostErrors::user_not_authorized();
			DispatchManager::redirect($error_controller);
		}
	}
}
?>
