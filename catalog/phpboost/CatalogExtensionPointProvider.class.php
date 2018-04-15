<?php
/*##################################################
 *                               CatalogExtensionPointProvider.class.php
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

class CatalogExtensionPointProvider extends ExtensionPointProvider
{
	public function __construct()
	{
		parent::__construct('catalog');
	}

	public function comments()
	{
		return new CommentsTopics(array(new CatalogCommentsTopic()));
	}

	public function css_products()
	{
		$module_css_products = new ModuleCssFiles();
		$module_css_files->adding_always_displayed_file('catalog_mini.css');
		$module_css_products->adding_running_module_displayed_product('catalog.css');
		$module_css_products->adding_running_module_displayed_product('carousel.css');
		return $module_css_products;
	}

	public function menus()
	{
		return new ModuleMenus(array(new CatalogModuleMiniMenu()));
	}

	public function feeds()
	{
		return new CatalogFeedProvider();
	}

	public function home_page()
	{
		return new CatalogHomePageExtensionPoint();
	}

	public function newcontent()
	{
		return new CatalogNewContent();
	}

	public function notation()
	{
		return new CatalogNotation();
	}

	public function scheduled_jobs()
	{
		return new CatalogScheduledJobs();
	}

	public function search()
	{
		return new CatalogSearchable();
	}

	public function sitemap()
	{
		return new CatalogSitemapExtensionPoint();
	}

	public function tree_links()
	{
		return new CatalogTreeLinks();
	}

	public function url_mappings()
	{
		return new UrlMappings(array(new DispatcherUrlMapping('/catalog/index.php')));
	}
}
?>
