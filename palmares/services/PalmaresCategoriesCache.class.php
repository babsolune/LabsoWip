<?php
/*##################################################
 *                        PalmaresCategoriesCache.class.php
 *                            -------------------
 *   begin                : April 13, 2016
 *   copyright            : (C) 2016 Sebastien Lartigue
 *   email                : babso@web33.fr
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
 * @author Sebastien Lartigue <babso@web33.fr>
 */

class PalmaresCategoriesCache extends CategoriesCache
{
	public function get_table_name()
	{
		return PalmaresSetup::$palmares_cats_table;
	}

	public function get_category_class()
	{
		return CategoriesManager::RICH_CATEGORY_CLASS;
	}

	public function get_module_identifier()
	{
		return 'palmares';
	}

	public function get_root_category()
	{
		$root = new RichRootCategory();
		$root->set_authorizations(PalmaresConfig::load()->get_authorizations());
		$root->set_description(
			StringVars::replace_vars(LangLoader::get_message('palmares.seo.description.root', 'common', 'palmares'),
			array('site' => GeneralConfig::load()->get_site_name()
		)));
		return $root;
	}
}
?>
