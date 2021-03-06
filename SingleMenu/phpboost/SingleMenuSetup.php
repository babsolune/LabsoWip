<?php

/**
 * @package 	SingleMenu
 * @subpackage 	PHPBoost
 * @category 	Modules
 * @copyright 	&copy; 2005-2019 PHPBoost
 * @license 	https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @version   	PHPBoost 5.2 - last update: 2017 05 30
 * @since   	PHPBoost 5.0 - 2016 04 21
 */

class SingleMenuSetup extends DefaultModuleSetup
{
	public function upgrade($installed_version)
	{
		return '5.2.0';
	}

	public function uninstall()
	{
		ConfigManager::delete('single-menu', 'config');
	}
}
?>
