<?php
/*##################################################
 *                          AgendaExtensionPointProvider.class.php
 *                            -------------------
 *   begin                : July 7, 2008
 *   copyright            : (C) 2008 R�gis Viarre
 *   email                : crowkait@phpboost.com
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

class AgendaExtensionPointProvider extends ExtensionPointProvider
{
	public function __construct()
	{
		parent::__construct('agenda');
	}

	public function comments()
	{
		return new CommentsTopics(array(new AgendaCommentsTopic()));
	}

	public function css_files()
	{
		$module_css_files = new ModuleCssFiles();
		$module_css_files->adding_running_module_displayed_file('agenda.css');
		return $module_css_files;
	}

	public function feeds()
	{
		return new AgendaFeedProvider();
	}

	public function home_page()
	{
		return new AgendaHomePageExtensionPoint();
	}

	public function menus()
	{
		return new ModuleMenus(array(new AgendaModuleMiniMenu()));
	}

	public function scheduled_jobs()
	{
		return new AgendaScheduledJobs();
	}

	public function search()
	{
		return new AgendaSearchable();
	}

	public function sitemap()
	{
		return new AgendaSitemapExtensionPoint();
	}

	public function tree_links()
	{
		return new AgendaTreeLinks();
	}

	public function url_mappings()
	{
		return new UrlMappings(array(new DispatcherUrlMapping('/agenda/index.php')));
	}
}
?>
