<?php

/**
 * @package 	SingleMenu
 * @subpackage 	PHPBoost
 * @category 	Modules
 * @copyright 	&copy; 2005-2019 PHPBoost
 * @license 	https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @version   	PHPBoost 5.2 - last update: 2016 04 21
 * @since   	PHPBoost 5.0 - 2016 04 21
 */

class SingleMenuExtensionPointProvider extends ExtensionPointProvider
{
    function __construct()
    {
        parent::__construct('SingleMenu');
    }

	public function menus()
	{
		return new ModuleMenus(array(
			new SingleMenuModuleMiniMenu()
		));
	}

	public function css_files()
	{
		$module_css_files = new ModuleCssFiles();
		$module_css_files->adding_always_displayed_file('singlemenu.css');
		return $module_css_files;
	}

	public function url_mappings()
	{
		return new UrlMappings(array(new DispatcherUrlMapping('/SingleMenu/index.php')));
	}

}
?>
