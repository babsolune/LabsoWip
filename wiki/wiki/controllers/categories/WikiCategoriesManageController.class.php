<?php
/**
 * @copyright 	&copy; 2005-2019 PHPBoost
 * @license 	https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @version   	PHPBoost 5.2 - last update: 2018 05 25
 * @since   	PHPBoost 5.1 - 2018 05 25
*/

class WikiCategoriesManageController extends AbstractCategoriesManageController
{
	protected function get_categories_manager()
	{
		return WikiService::get_categories_manager();
	}

	protected function get_display_category_url(Category $category)
	{
		return WikiUrlBuilder::display_category($category->get_id(), $category->get_rewrited_name());
	}

	protected function get_edit_category_url(Category $category)
	{
		return WikiUrlBuilder::edit_category($category->get_id());
	}

	protected function get_delete_category_url(Category $category)
	{
		return WikiUrlBuilder::delete_category($category->get_id());
	}

	protected function get_categories_management_url()
	{
		return WikiUrlBuilder::manage_categories();
	}

	protected function get_module_home_page_url()
	{
		return WikiUrlBuilder::home();
	}

	protected function get_module_home_page_title()
	{
		return LangLoader::get_message('module.title', 'common', 'wiki');
	}

	protected function check_authorizations()
	{
		if (!WikiAuthorizationsService::check_authorizations()->manage_categories())
		{
			$error_controller = PHPBoostErrors::user_not_authorized();
			DispatchManager::redirect($error_controller);
		}
	}
}
?>
