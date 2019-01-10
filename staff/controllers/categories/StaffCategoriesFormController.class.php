<?php
/**
 * @copyright 	&copy; 2005-2019 PHPBoost
 * @license 	https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @version   	PHPBoost 5.2 - last update: 2017 11 05
 * @since   	PHPBoost 5.1 - 2017 06 29
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
