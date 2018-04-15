<?php
/*##################################################
 *                               StaffExtensionPointProvider.class.php
 *                            -------------------
 *   begin                : June 29, 2017
 *   copyright            : (C) 2017 Sebastien LARTIGUE
 *   email                : babsolune@phpboost.com
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
 * @author Seabstien LARTIGUE <babsolune@phpboost.com>
 */

class StaffExtensionPointProvider extends ExtensionPointProvider
{
	public function __construct()
	{
		parent::__construct('staff');
	}

	public function css_files()
	{
		$module_css_files = new ModuleCssFiles();
		$module_css_files->adding_running_module_displayed_file('staff.css');
		return $module_css_files;
	}

	public function feeds()
	{
		return new StaffFeedProvider();
	}

	public function home_page()
	{
		return new StaffHomePageExtensionPoint();
	}

	public function newcontent()
	{
		return new StaffNewContent();
	}

	public function search()
	{
		return new StaffSearchable();
	}

	public function sitemap()
	{
		return new StaffSitemapExtensionPoint();
	}

	public function tree_links()
	{
		return new StaffTreeLinks();
	}

	public function url_mappings()
	{
		return new UrlMappings(array(new DispatcherUrlMapping('/staff/index.php')));
	}
}
?>
