<?php
/**
 * @copyright 	&copy; 2005-2019 PHPBoost
 * @license 	https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @version   	PHPBoost 5.2 - last update: 2017 11 05
 * @since   	PHPBoost 5.1 - 2017 06 29
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
