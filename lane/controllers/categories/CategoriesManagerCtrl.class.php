<?php
/**
 * @copyright 	&copy; 2005-2019 PHPBoost https://www.phpboost.com
 * @license 	https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE [babsolune@phpboost.com]
 * @version   	PHPBoost 5.2 - last update: 2018 05 25
 * @since   	PHPBoost 5.1 - 2018 05 25
*/

namespace Wiki\controllers\categories;
use \Wiki\phpboost\ModConfig;
use \Wiki\services\ModAuthorizations;
use \Wiki\services\ModServives;
use \Wiki\util\ModUrlBuilder;

class CategoriesManagerCtrl extends AbstractCategoriesManageController
{
	protected function get_categories_manager()
	{
		return ModServives::get_categories_manager();
	}

	protected function get_display_category_url(Category $category)
	{
		return ModUrlBuilder::display_category($category->get_id(), $category->get_rewrited_name());
	}

	protected function get_edit_category_url(Category $category)
	{
		return ModUrlBuilder::edit_category($category->get_id());
	}

	protected function get_delete_category_url(Category $category)
	{
		return ModUrlBuilder::delete_category($category->get_id());
	}

	protected function get_categories_management_url()
	{
		return ModUrlBuilder::manage_categories();
	}

	protected function get_module_home_page_url()
	{
		return ModUrlBuilder::home();
	}

	protected function get_module_home_page_title()
	{
		return LangLoader::get_message('module.title', 'common', 'wiki');
	}

	protected function check_authorizations()
	{
		if (!ModAuthorizations::check_authorizations()->manage_categories())
		{
			$error_controller = PHPBoostErrors::user_not_authorized();
			DispatchManager::redirect($error_controller);
		}
	}
}
?>
