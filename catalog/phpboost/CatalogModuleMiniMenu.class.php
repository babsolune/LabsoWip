<?php
/*##################################################
 *                               CatalogModuleMiniMenu.class.php
 *                            -------------------
 *   begin                : August 24, 2014
 *   copyright            : (C) 2014 Julien BRISWALTER
 *   email                : j1.seth@phpboost.com
 *
 *
 ###################################################
 *
 * This program is a free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
 ###################################################*/

 /**
 * @author Julien BRISWALTER <j1.seth@phpboost.com>
 */

class CatalogModuleMiniMenu extends ModuleMiniMenu
{
	public function get_default_block()
	{
		return self::BLOCK_POSITION__RIGHT;
	}

	public function get_menu_id()
	{
		return 'module-mini-catalog';
	}

	public function get_menu_title()
	{
		return CatalogConfig::load()->is_sort_type_date() ? LangLoader::get_message('last_catalog_products', 'common', 'catalog') : LangLoader::get_message('most_downloaded_products', 'common', 'catalog');
	}

	public function is_displayed()
	{
		return CatalogAuthorizationsService::check_authorizations()->read();
	}

	public function get_menu_content()
	{
		//Create file template
		$tpl = new FileTemplate('catalog/CatalogModuleMiniMenu.tpl');

		//Assign the lang file to the tpl
		$tpl->add_lang(LangLoader::get('common', 'catalog'));

		//Assign common menu variables to the tpl
		MenuService::assign_positions_conditions($tpl, $this->get_block());

		//Load module config
		$config = CatalogConfig::load();

		//Load module cache
		$catalog_cache = CatalogCache::load();

		//Load categories cache
		$categories_cache = CatalogService::get_categories_manager()->get_categories_cache();

		$products = $catalog_cache->get_products();

		$tpl->put_all(array(
			'C_PRODUCTS' => !empty($products),
			'C_SORT_BY_DATE' => $config->is_sort_type_date(),
			'C_SORT_BY_NOTATION' => $config->is_sort_type_notation(),
			'C_SORT_BY_NUMBER_DOWNLOADS' => $config->is_sort_type_number_downloads(),
			'C_SORT_BY_NUMBER_VIEWS' => $config->is_sort_type_number_views()
		));

		$displayed_position = 1;
		foreach ($products as $file)
		{
			$product = new Product();
			$product->set_properties($file);

			$tpl->assign_block_vars('products', array_merge($product->get_array_tpl_vars(), array(
				'DISPLAYED_POSITION' => $displayed_position
			)));

			$displayed_position++;
		}

		return $tpl->render();
	}
}
?>
