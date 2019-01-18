<?php
/**
 * @copyright 	&copy; 2005-2019 PHPBoost https://www.phpboost.com
 * @license 	https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Loic ROUCHON <horn@phpboost.com>
 * @version   	PHPBoost 5.2 - last update: 2018 05 25
 * @since   	PHPBoost 2.0 - 2008 02 24
 * @contributor Kevin MASSY [reidlos@phpboost.com]
 * @contributor Julien BRISWALTER [j1.seth@phpboost.com]
 * @contributor Arnaud GENET [elenwii@phpboost.com]
 * @contributor Sebastien LARTIGUE [babsolune@phpboost.com]
*/

namespace Wiki\phpboost;
use \Wiki\phpboost\ModFeedProvider;
use \Wiki\phpboost\ModHomePageExtensionPoint;
use \Wiki\phpboost\ModScheduledJobs;
use \Wiki\phpboost\ModSearchable;
use \Wiki\phpboost\ModSitemapExtensionPoint;
use \Wiki\phpboost\ModActionsLinks;

class ModExtensionPointProvider extends ExtensionPointProvider
{
	public function __construct()
	{
		parent::__construct('wiki');
	}

	public function css_files()
	{
		$module_css_files = new ModuleCssFiles();
		$module_css_files->adding_running_module_displayed_file('module.css');
		return $module_css_files;
	}

	public function feeds()
	{
		return new ModFeedProvider();
	}

	public function home_page()
	{
		return new ModHomePageExtensionPoint();
	}

	public function scheduled_jobs()
	{
		return new ModScheduledJobs();
	}

	public function search()
	{
		return new ModSearchable();
	}

	public function sitemap()
	{
		return new ModSitemapExtensionPoint();
	}

	public function tree_links()
	{
		return new ModActionsLinks();
	}

	public function url_mappings()
	{
		return new UrlMappings(array(new DispatcherUrlMapping('/wiki/index.php')));
	}
}
?>
