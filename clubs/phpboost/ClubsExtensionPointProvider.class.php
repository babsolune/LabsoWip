<?php
/*##################################################
 *                               ClubsExtensionPointProvider.class.php
 *                            -------------------
 *   begin                : June 23, 2017
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
 * @author Sebastien LARTIGUE <babsolune@phpboost.com>
 */

class ClubsExtensionPointProvider extends ExtensionPointProvider
{
	public function __construct()
	{
		parent::__construct('clubs');
	}

	public function comments()
	{
		return new CommentsTopics(array(new ClubsCommentsTopic()));
	}

	public function css_files()
	{
		$module_css_files = new ModuleCssFiles();
		$module_css_files->adding_running_module_displayed_file('clubs.css');
		return $module_css_files;
	}

	public function feeds()
	{
		return new ClubsFeedProvider();
	}

	public function home_page()
	{
		return new ClubsHomePageExtensionPoint();
	}

	public function newcontent()
	{
		return new ClubsNewContent();
	}

	public function notation()
	{
		return new ClubsNotation();
	}

	public function search()
	{
		return new ClubsSearchable();
	}

	public function sitemap()
	{
		return new ClubsSitemapExtensionPoint();
	}

	public function tree_links()
	{
		return new ClubsTreeLinks();
	}

	public function url_mappings()
	{
		return new UrlMappings(array(new DispatcherUrlMapping('/clubs/index.php')));
	}
}
?>
