<?php
/*##################################################
 *		      SponsorsDeleteCategoryController.class.php
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

class SponsorsDeleteCategoryController extends AbstractDeleteCategoryController
{
	protected function get_id_category()
	{
		return AppContext::get_request()->get_getint('id', 0);
	}

	protected function get_categories_manager()
	{
		return SponsorsService::get_categories_manager();
	}

	protected function get_categories_management_url()
	{
		return SponsorsUrlBuilder::manage_categories();
	}

	protected function get_delete_category_url(Category $category)
	{
		return SponsorsUrlBuilder::delete_category($category->get_id());
	}

	protected function get_module_home_page_url()
	{
		return SponsorsUrlBuilder::home();
	}

	protected function get_module_home_page_title()
	{
		return LangLoader::get_message('sponsors.module.title', 'common', 'sponsors');
	}

	protected function clear_cache()
	{
		return SponsorsCache::invalidate();
	}

	protected function check_authorizations()
	{
		if (!SponsorsAuthorizationsService::check_authorizations()->manage_categories())
		{
			$error_controller = PHPBoostErrors::user_not_authorized();
			DispatchManager::redirect($error_controller);
		}
	}
}
?>
