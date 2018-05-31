<?php
/*##################################################
 *                        SponsorsExtensionPointProvider.class.php
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

class SponsorsExtensionPointProvider extends ExtensionPointProvider
{
	public function __construct()
	{
		parent::__construct('sponsors');
	}

	public function css_files()
	{
		$module_css_files = new ModuleCssFiles();
		$module_css_files->adding_running_module_displayed_file('sponsors.css');
		$module_css_files->adding_always_displayed_file('sponsors_mini.css');
		$module_css_files->adding_always_displayed_file('pbt_tabs.css');
		return $module_css_files;
	}

	public function menus()
	{
		return new ModuleMenus(array(new SponsorsModuleMiniMenu()));
	}

	public function feeds()
	{
		return new SponsorsFeedProvider();
	}

	public function home_page()
	{
		return new SponsorsHomePageExtensionPoint();
	}

	public function newcontent()
	{
		return new SponsorsNewContent();
	}

	public function scheduled_jobs()
	{
		return new SponsorsScheduledJobs();
	}

	public function search()
	{
		return new SponsorsSearchable();
	}

	public function sitemap()
	{
		return new SponsorsSitemapExtensionPoint();
	}

	public function tree_links()
	{
		return new SponsorsTreeLinks();
	}

	public function url_mappings()
	{
		return new UrlMappings(array(new DispatcherUrlMapping('/sponsors/index.php')));
	}
}
?>
